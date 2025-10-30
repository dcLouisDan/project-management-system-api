<?php

namespace App\Http\Controllers;

use App\Enums\ProjectRelationTypes;
use App\Http\Resources\TaskResource;
use App\Http\Responses\ApiResponse;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use App\Services\ProjectRelationService;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

/**
 * @group Task Management
 *
 * APIs for managing tasks within projects
 */
class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService,
        protected ProjectRelationService $projectRelationService
    ) {}

    /**
     * List All Tasks
     *
     * Get a paginated list of all tasks with optional filtering.
     *
     * @queryParam per_page integer Number of tasks per page. Defaults to 15. Example: 20
     * @queryParam project_id integer Filter tasks by project ID. Example: 1
     * @queryParam assigned_to_id integer Filter tasks by assigned user ID. Example: 5
     * @queryParam status string Filter tasks by status. Example: in_progress
     * @queryParam priority string Filter tasks by priority. Example: high
     *
     * @apiResourceCollection App\Http\Resources\TaskResource
     *
     * @apiResourceModel App\Models\Task paginate=15
     *
     * @response status=200 scenario="success" {"data": [{"id": 1, "title": "Implement feature X", "status": "in_progress"}], "links": {}, "meta": {}}
     * @response status=403 scenario="forbidden" {"message": "This action is unauthorized."}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to retrieve tasks", "errors": [], "meta": []}
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Task::class)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        $query = $this->taskService->buildQuery($request->all());
        $tasks = $query->paginate($request->get('per_page', 15));

        return TaskResource::collection($tasks);
    }

    /**
     * Show Task Details
     *
     * Get detailed information about a specific task.
     *
     * @urlParam task integer required The ID of the task. Example: 1
     *
     * @apiResource App\Http\Resources\TaskResource
     *
     * @apiResourceModel App\Models\Task
     *
     * @response status=200 scenario="success" {"data": {"id": 1, "title": "Implement feature X", "description": "Task description", "status": "in_progress", "priority": "high"}}
     * @response status=403 scenario="forbidden" {"message": "This action is unauthorized."}
     * @response status=404 scenario="not found" {"message": "Task not found"}
     */
    public function show(Task $task)
    {
        if (request()->user()->cannot('view', $task)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        return new TaskResource($task->load(['assignedTo', 'assignedBy']));
    }

    /**
     * List Tasks by User
     *
     * Get a paginated list of tasks assigned to a specific user.
     *
     * @urlParam userId integer required The ID of the user. Example: 5
     *
     * @queryParam per_page integer Number of tasks per page. Defaults to 15. Example: 20
     * @queryParam project_id integer Filter by project ID. Example: 1
     * @queryParam status string Filter by task status. Example: in_progress
     * @queryParam priority string Filter by task priority. Example: high
     *
     * @apiResourceCollection App\Http\Resources\TaskResource
     *
     * @apiResourceModel App\Models\Task paginate=15
     *
     * @response status=200 scenario="success" {"data": [{"id": 1, "title": "Implement feature X", "assigned_to": {"id": 5, "name": "John Doe"}}], "links": {}, "meta": {}}
     * @response status=403 scenario="forbidden" {"message": "This action is unauthorized."}
     */
    public function indexByUser(Request $request, int $userId)
    {
        if ($request->user()->cannot('viewAnyByUser', $userId)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        $filters = $request->all();
        $filters['assigned_to_id'] = $userId;

        $query = $this->taskService->buildQuery($filters);
        $tasks = $query->paginate($request->get('per_page', 15));

        return TaskResource::collection($tasks->load(['assignedBy', 'project']));
    }

    /**
     * List Tasks by Project
     *
     * Get a paginated list of tasks for a specific project.
     *
     * @urlParam projectId integer required The ID of the project. Example: 1
     *
     * @queryParam per_page integer Number of tasks per page. Defaults to 15. Example: 20
     * @queryParam assigned_to_id integer Filter by assigned user ID. Example: 5
     * @queryParam status string Filter by task status. Example: in_progress
     * @queryParam priority string Filter by task priority. Example: high
     *
     * @apiResourceCollection App\Http\Resources\TaskResource
     *
     * @apiResourceModel App\Models\Task paginate=15
     *
     * @response status=200 scenario="success" {"data": [{"id": 1, "title": "Implement feature X", "project": {"id": 1, "name": "Project Alpha"}}], "links": {}, "meta": {}}
     * @response status=403 scenario="forbidden" {"message": "This action is unauthorized."}
     */
    public function indexByProject(Request $request, int $projectId)
    {
        if ($request->user()->cannot('viewAnyInProject', $projectId)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        $filters = $request->all();
        $filters['project_id'] = $projectId;

        $query = $this->taskService->buildQuery($filters);
        $tasks = $query->paginate($request->get('per_page', 15));

        return TaskResource::collection($tasks->load(['assignedTo', 'assignedBy']));
    }

    /**
     * Create Task
     *
     * Create a new task within a project.
     *
     * @urlParam project integer required The ID of the project. Example: 1
     *
     * @bodyParam project_id integer required The ID of the project. Example: 1
     * @bodyParam title string required The title of the task. Must be unique within the project. Min: 3 chars, Max: 255 chars. Example: Implement user authentication
     * @bodyParam description string Optional description of the task. Example: Add JWT-based authentication to the API
     * @bodyParam priority string required Priority level of the task. Must be one of: low, medium, high, urgent. Example: high
     * @bodyParam due_date date Optional due date for the task. Must be a valid date. Example: 2025-11-15
     *
     * @apiResource App\Http\Resources\TaskResource
     *
     * @apiResourceModel App\Models\Task
     *
     * @response status=201 scenario="success" {"data": {"id": 1, "title": "Implement user authentication", "priority": "high", "status": "not_started"}, "message": "Task created successfully."}
     * @response status=403 scenario="forbidden" {"message": "This action is unauthorized."}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"title": ["The title has already been taken."]}}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to create task: Database error", "errors": [], "meta": []}
     */
    public function store(Request $request, Project $project)
    {
        if ($request->user()->cannot('create', Task::class)) {
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

    /**
     * Update Task
     *
     * Update an existing task's information.
     *
     * @urlParam task integer required The ID of the task to update. Example: 1
     *
     * @bodyParam project_id integer required The ID of the project. Example: 1
     * @bodyParam title string required The title of the task. Must be unique within the project. Min: 3 chars, Max: 255 chars. Example: Implement user authentication
     * @bodyParam description string Optional description of the task. Example: Add JWT-based authentication with refresh tokens
     * @bodyParam priority string required Priority level of the task. Must be one of: low, medium, high, urgent. Example: urgent
     * @bodyParam due_date date Optional due date for the task. Must be a valid date. Example: 2025-11-20
     *
     * @apiResource App\Http\Resources\TaskResource
     *
     * @apiResourceModel App\Models\Task
     *
     * @response status=200 scenario="success" {"data": {"id": 1, "title": "Implement user authentication", "priority": "urgent"}, "message": "Task updated successfully."}
     * @response status=403 scenario="forbidden" {"message": "This action is unauthorized."}
     * @response status=404 scenario="not found" {"message": "Task not found"}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"title": ["The title has already been taken."]}}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to update task: Database error", "errors": [], "meta": []}
     */
    public function update(Request $request, Task $task)
    {
        if ($request->user()->cannot('update', $task)) {
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

    /**
     * Delete Task
     *
     * Delete a task (soft delete by default). Use force parameter for permanent deletion.
     *
     * @urlParam task integer required The ID of the task to delete. Example: 1
     *
     * @queryParam force boolean Force permanent deletion. Defaults to false. Example: true
     *
     * @response status=200 scenario="success" {"data": null, "message": "Task deleted successfully."}
     * @response status=403 scenario="forbidden" {"message": "This action is unauthorized."}
     * @response status=404 scenario="not found" {"message": "Task not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to delete task: Task has dependencies", "errors": [], "meta": []}
     */
    public function destroy(Request $request, Task $task)
    {
        if ($request->user()->cannot('delete', $task)) {
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

    /**
     * Restore Deleted Task
     *
     * Restore a soft-deleted task.
     *
     * @urlParam taskId integer required The ID of the task to restore. Example: 1
     *
     * @response status=200 scenario="success" {"data": null, "message": "Task restored successfully."}
     * @response status=403 scenario="forbidden" {"message": "This action is unauthorized."}
     * @response status=404 scenario="not found" {"message": "Task not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to restore task: Database error", "errors": [], "meta": []}
     */
    public function restore(Request $request, int $taskId)
    {
        $task = Task::withTrashed()->find($taskId);

        if (! $task) {
            return ApiResponse::error('Task not found', 404);
        }

        if ($request->user()->cannot('restore', $task)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        try {
            $this->taskService->restoreTask($taskId);

            return ApiResponse::success(null, 'Task restored successfully.');
        } catch (\Exception $e) {
            return ApiResponse::error('Failed to restore task: '.$e->getMessage(), 500);
        }
    }

    /**
     * Sync Task Relations
     *
     * Synchronize task relationships with other tasks and milestones. This will create new relations,
     * update existing ones, and remove relations not in the provided arrays.
     *
     * @urlParam task integer required The ID of the task. Example: 1
     *
     * @bodyParam tasks array Optional array of related tasks to sync. Example: [{"id": 2, "relation_type": "blocks"}, {"id": 3, "relation_type": "depends_on"}]
     * @bodyParam tasks[].id integer required The ID of the related task. Example: 2
     * @bodyParam tasks[].relation_type string required Type of relation. Must be one of: blocks, blocked_by, depends_on, dependency_of, parent_of, child_of, relates_to, duplicates, duplicated_by. Example: blocks
     * @bodyParam milestones array Optional array of related milestones to sync. Example: [{"id": 5, "relation_type": "relates_to"}]
     * @bodyParam milestones[].id integer required The ID of the related milestone. Example: 5
     * @bodyParam milestones[].relation_type string required Type of relation. Must be one of: blocks, blocked_by, depends_on, dependency_of, parent_of, child_of, relates_to, duplicates, duplicated_by. Example: relates_to
     *
     * @response status=200 scenario="success" {"data": null, "message": "Task relations synchronized successfully."}
     * @response status=403 scenario="forbidden" {"message": "This action is unauthorized."}
     * @response status=404 scenario="not found" {"message": "Task not found"}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"tasks.0.relation_type": ["The selected relation type is invalid."]}}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to synchronize task relations: Circular dependency detected", "errors": [], "meta": []}
     */
    public function syncRelations(Request $request, Task $task)
    {
        if ($request->user()->cannot('update', $task)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        $validated = $request->validate([
            'tasks' => ['sometimes', 'array'],
            'tasks.*.id' => ['required', 'integer', 'exists:tasks,id'],
            'tasks.*.relation_type' => ['required', 'string', Rule::in(ProjectRelationTypes::allTypes())],
            'milestones' => ['sometimes', 'array'],
            'milestones.*.id' => ['required', 'integer', 'exists:milestones,id'],
            'milestones.*.relation_type' => ['required', 'string', Rule::in(ProjectRelationTypes::allTypes())],
        ]);

        $relatedTasks = $validated['tasks'] ?? [];
        $relatedMilestones = $validated['milestones'] ?? [];

        try {
            if (! empty($relatedTasks)) {
                $this->projectRelationService->syncOutgoingRelations($task, $relatedTasks, Task::class);
            }

            if (! empty($relatedMilestones)) {
                $this->projectRelationService->syncOutgoingRelations($task, $relatedMilestones, Milestone::class);
            }

            return ApiResponse::success(null, 'Task relations synchronized successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to synchronize task relations: '.$e->getMessage());

            return ApiResponse::error('Failed to synchronize task relations: '.$e->getMessage(), 500);
        }

    }
}
