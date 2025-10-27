<?php

namespace App\Services;

use App\Enums\ProgressStatus;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TaskService
{
    public function __construct(
        protected DependencyValidator $dependencyValidator
    ) {
        // Initialization code if needed
    }

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

    public function updateTask(Task $task, array $data, User $updatedBy)
    {
        // Logic to update a task
    }

    public function deleteTask(Task $task, User $deletedBy)
    {
        // Logic to delete a task
    }

    public function restoreTask(Task $task, User $restoredBy)
    {
        // Logic to restore a deleted task
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

    public function buildQuery(array $filters, User $user)
    {
        $query = Task::query();

        if (isset($filters['status'])) {
            $query->status($filters['status']);
        }

        if (isset($filters['priority'])) {
            $query->priority($filters['priority']);
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
            $query->where('title', 'like', '%'.$filters['title'].'%');
        }

        return $query;
    }
}
