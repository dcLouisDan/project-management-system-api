<?php

namespace App\Http\Controllers;

use App\Enums\ProjectRelationTypes;
use App\Http\Resources\TaskResource;
use App\Http\Responses\ApiResponse;
use App\Models\Project;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @group Tasks Management
 */
class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {
        //
    }

    public function index(Request $request)
    {
        if ($request->user->cannot('viewAny', Task::class)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        $query = $this->taskService->buildQuery($request->all());
        $tasks = $query->paginate($request->get('per_page', 15));

        return TaskResource::collection($tasks);
    }

    public function show(Task $task)
    {
        if (request()->user->cannot('view', $task)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        return new TaskResource($task->load(['assignedTo', 'assignedBy']));
    }

    public function indexByUser(Request $request, int $userId)
    {
        if ($request->user->cannot('viewAnyByUser', $userId)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        $filters = $request->all();
        $filters['assigned_to_id'] = $userId;

        $query = $this->taskService->buildQuery($filters);
        $tasks = $query->paginate($request->get('per_page', 15));

        return TaskResource::collection($tasks->load(['assignedBy', 'project']));
    }

    public function indexByProject(Request $request, int $projectId)
    {
        if ($request->user->cannot('viewAnyInProject', $projectId)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        $filters = $request->all();
        $filters['project_id'] = $projectId;

        $query = $this->taskService->buildQuery($filters);
        $tasks = $query->paginate($request->get('per_page', 15));

        return TaskResource::collection($tasks->load(['assignedTo', 'assignedBy']));
    }

    public function store(Request $request, Project $project)
    {
        if ($request->user->cannot('create', Task::class)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        $validated = $request->validate([
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'title' => [
                'required',
                'string',
                'max:255',
                'min:3',
                Rule::unique('tasks')->where(function ($query) use ($project) {
                    return $query->where('project_id', $project->id);
                }),
            ],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'string', 'in:low,medium,high,urgent'],
            'due_date' => ['nullable', 'date'],
        ]);

        try {
            $task = $this->taskService->createTask($validated, $project, $request->user);

            return ApiResponse::success(new TaskResource($task), 'Task created successfully.', 201);
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to create task: '.$e->getMessage(), 500);
        }
    }

    public function update(Request $request, Task $task)
    {
        if ($request->user->cannot('update', $task)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        $validated = $request->validate([
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'title' => [
                'required',
                'string',
                'max:255',
                'min:3',
                Rule::unique('tasks')->where(function ($query) use ($task) {
                    return $query->where('project_id', $task->project_id);
                })->ignore($task->id),
            ],
            'description' => ['nullable', 'string'],
            'priority' => ['required', 'string', 'in:low,medium,high,urgent'],
            'due_date' => ['nullable', 'date'],
        ]);

        try {
            $updatedTask = $this->taskService->updateTask($task, $validated);

            return ApiResponse::success(new TaskResource($updatedTask), 'Task updated successfully.');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to update task: '.$e->getMessage(), 500);
        }
    }

    public function destroy(Request $request, Task $task)
    {
        if ($request->user->cannot('delete', $task)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        $force = $request->query('force', false);

        try {
            $this->taskService->deleteTask($task, $force);

            return ApiResponse::success(null, 'Task deleted successfully.');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to delete task: '.$e->getMessage(), 500);
        }
    }

    public function restore(Request $request, int $taskId)
    {
        if ($request->user->cannot('restore', $taskId)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        try {
            $this->taskService->restoreTask($taskId);

            return ApiResponse::success(null, 'Task restored successfully.');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to restore task: '.$e->getMessage(), 500);
        }
    }

    public function syncTaskRelations(Request $request, Task $task)
    {
        if ($request->user->cannot('update', $task)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        $validated = $request->validate([
            // Example [1: 'depends_on', 2: 'related_to']
            'related_task_ids' => ['required', 'array'],
            'related_task_ids.*.id' => ['integer', 'exists:tasks,id'],
            'related_task_ids.*.relation' => ['string', Rule::in(ProjectRelationTypes::allTypes())],
        ]);
    }
}
