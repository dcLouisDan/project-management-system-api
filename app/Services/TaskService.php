<?php

namespace App\Services;

use App\Enums\ProgressStatus;
use App\Enums\SoftDeleteStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TaskService
{
    // Task service methods would go here
    public function createTask(array $data, Project $project, User $createdBy)
    {
        return DB::transaction(function () use ($data, $project, $createdBy) {
            $task = Task::create([
                'project_id' => $project->id,
                'assigned_to_id' => $data['assigned_to_id'] ?? null,
                'assigned_by_id' => $createdBy->id,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'status' => ProgressStatus::NOT_STARTED->value,
                'priority' => $data['priority'] ?? 'normal',
                'due_date' => $data['due_date'] ?? null,
            ]);

            return $task;
        });
    }

    public function updateTask(Task $task, array $data)
    {
        $task->fill([
            'title' => $data['title'] ?? $task->title,
            'description' => $data['description'] ?? $task->description,
            'priority' => $data['priority'] ?? $task->priority,
            'due_date' => $data['due_date'] ?? $task->due_date,
            'assigned_to_id' => $data['assigned_to_id'] ?? $task->assigned_to_id,
        ]);
        $task->save();

        return $task;
    }

    public function deleteTask(Task $task, bool $force = false)
    {
        if ($force) {
            return $task->forceDelete();
        }

        return $task->delete();
    }

    public function restoreTask(int $taskId)
    {
        $task = Task::withTrashed()->findOrFail($taskId);

        return $task->restore();
    }

    public function assignTask(Task $task, User $assignee, User $assignedBy)
    {
        if (! $assignee->isInProjectTeams($task->project)) {
            throw new \InvalidArgumentException('User must be part of a team associated with the project to be assigned this task.');
        }

        if (! $assignedBy->isInProjectTeams($task->project)) {
            throw new \InvalidArgumentException('Assigning user must be part of a team associated with the project.');
        }

        $task->assigned_to_id = $assignee->id;
        $task->assigned_by_id = $assignedBy->id;
        $task->status = ProgressStatus::ASSIGNED->value;
        $task->save();

        return $task;
    }

    public function updateTaskStatus(Task $task, string $status, User $updatedBy)
    {
        if (! ProgressStatus::isValidStatus($status)) {
            throw new \InvalidArgumentException("Invalid status: $status");
        }

        $task->status = $status;
        $task->save();

        ProjectGraphCache::invalidate($task->project_id);
    }

    public function startTask(Task $task, User $startedBy)
    {
        $this->updateTaskStatus($task, ProgressStatus::IN_PROGRESS->value, $startedBy);

        return $task;
    }

    public function submitTaskForReview(Task $task, ?string $notes, User $submittedBy)
    {
        return DB::transaction(function () use ($task, $submittedBy, $notes) {
            $reviewInProgressStatuses = [ProgressStatus::AWAITING_REVIEW->value, ProgressStatus::UNDER_REVIEW->value];

            // Check if there's an existing review in progress
            $lastReview = $task->reviews()->whereIn('status', $reviewInProgressStatuses)->first();

            if ($lastReview) {
                throw new \LogicException('A review is already in progress for this task.');
            }

            $this->updateTaskStatus($task, ProgressStatus::AWAITING_REVIEW->value, $submittedBy);

            $task->reviews()->create([
                'reviewed_by_id' => $task->assigned_by_id,
                'submitted_by_id' => $submittedBy->id,
                'submission_notes' => $notes,
                'status' => ProgressStatus::AWAITING_REVIEW->value,
            ]);

            return $task;
        });
    }

    public function startReview(Task $task, int $reviewId, User $reviewer)
    {
        return DB::transaction(function () use ($task, $reviewId, $reviewer) {
            $this->updateTaskStatus($task, ProgressStatus::UNDER_REVIEW->value, $reviewer);

            $task->reviews()->findOrFail($reviewId)->update([
                'status' => ProgressStatus::UNDER_REVIEW->value,
            ]);

            return $task;
        });
    }

    public function submitReview(Task $task, int $reviewId, string $feedback, string $status, User $reviewer)
    {
        if (! in_array($status, [ProgressStatus::APPROVED->value, ProgressStatus::REJECTED->value])) {
            throw new \InvalidArgumentException("Invalid review status: $status");
        }

        $taskReview = $task->reviews()->findOrFail($reviewId);

        if ($taskReview->status !== $task->status) {
            throw new \LogicException('The task review status does not match the current task status.');
        }

        if ($task->assigned_by_id !== $reviewer->id) {
            throw new \InvalidArgumentException('Only the user who requested the review can submit it.');
        }

        if ($taskReview->reviewed_by_id !== $reviewer->id) {
            throw new \InvalidArgumentException('The reviewer does not match the user submitting the review.');
        }

        $finishedProgressStatuses = [ProgressStatus::APPROVED->value, ProgressStatus::REJECTED->value];
        if (! in_array($status, $finishedProgressStatuses)) {
            throw new \InvalidArgumentException("Review status must be either 'approved' or 'rejected'.");
        }

        return DB::transaction(function () use ($task, $taskReview, $feedback, $status, $reviewer) {
            $finalStatus = $status === ProgressStatus::APPROVED->value ? ProgressStatus::COMPLETED->value : ProgressStatus::IN_PROGRESS->value;
            $this->updateTaskStatus($task, $finalStatus, $reviewer);

            $taskReview->update([
                'feedback' => $feedback,
                'status' => $status,
                'reviewed_at' => now(),
            ]);

            return $task;
        });
    }

    public function buildQuery(array $filters)
    {
        $query = Task::query();

        $sortableFields = ['id', 'title', 'priority', 'status', 'due_date', 'created_at'];

        if (isset($filters['delete_status']) && SoftDeleteStatus::isValidStatus($filters['delete_status'])) {
            $status = $filters['delete_status'];
            if ($status == SoftDeleteStatus::ALL->value) {
                $query->withTrashed();
            }
            if ($status == SoftDeleteStatus::DELETED->value) {
                $query->onlyTrashed();
            }
        }

        $sort = isset($filters['sort']) && in_array($filters['sort'], $sortableFields) ? $filters['sort'] : 'id';

        $direction = isset($filters['direction']) && in_array($filters['direction'], ['asc', 'desc']) ? $filters['direction'] : 'asc';
        $query->orderBy($sort, $direction);

        if (isset($filters['status'])) {
            $statusArr = array_map('trim', explode(',', $filters['status']));
            $query->whereIn('status', $statusArr);
        }

        if (isset($filters['priority'])) {
            $priorityArr = array_map('trim', explode(',', $filters['priority']));
            $query->whereIn('priority', $priorityArr);
        }

        if (isset($filters['due_date'])) {
            $query->where('due_date', '<=', $filters['due_date']);
        }

        if (isset($filters['assigned_to_id'])) {
            $query->where('assigned_to_id', $filters['assigned_to_id']);
        }

        if (isset($filters['assigned_by_id'])) {
            $query->where('assigned_by_id', $filters['assigned_by_id']);
        }

        if (isset($filters['project_id'])) {
            $query->where('project_id', $filters['project_id']);
        }

        if (isset($filters['title'])) {
            $query->where('title', 'like', '%' . $filters['title'] . '%');
        }

        return $query;
    }
}
