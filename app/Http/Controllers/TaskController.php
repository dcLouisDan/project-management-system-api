<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Http\Responses\ApiResponse;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;

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

        return new TaskResource($task);
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

        return TaskResource::collection($tasks);
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

        return TaskResource::collection($tasks);
    }
}
