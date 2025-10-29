<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1')->group(function () {
    // Protected route example
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User Management Routes
    Route::middleware(['permission:list users'])->get('users', [UserController::class, 'index']);
    Route::middleware(['permission:view user'])->get('users/{user}', [UserController::class, 'show']);
    Route::middleware(['permission:create user'])->post('users', [UserController::class, 'store']);
    Route::middleware(['permission:update user'])->put('users/{user}', [UserController::class, 'update']);
    Route::middleware(['permission:delete user'])->delete('users/{user}', [UserController::class, 'destroy']);
    Route::middleware(['permission:restore user'])->post('users/{userId}/restore', [UserController::class, 'restore']);
    Route::middleware(['permission:assign role user'])->post('users/{user}/roles', [UserController::class, 'assignRoles']);

    // Team Management Routes
    Route::middleware(['permission:list teams'])->get('teams', [TeamController::class, 'index']);
    Route::middleware(['permission:create team'])->post('teams', [TeamController::class, 'store']);
    Route::middleware(['permission:view team'])->get('teams/{team}', [TeamController::class, 'show']);
    Route::middleware(['permission:update team'])->put('teams/{team}', [TeamController::class, 'update']);
    Route::middleware(['permission:delete team'])->delete('teams/{team}', [TeamController::class, 'destroy']);
    Route::middleware(['permission:add member team'])->post('teams/{team}/members', [TeamController::class, 'addMember']);
    Route::middleware(['permission:add member team'])->post('teams/{team}/members/bulk', [TeamController::class, 'addMembers']);
    Route::middleware(['permission:remove member team'])->delete('teams/{team}/members/{user}', [TeamController::class, 'removeMember']);
    Route::middleware(['permission:remove member team'])->delete('teams/{team}/members', [TeamController::class, 'removeMembers']);
    Route::middleware(['permission:assign roles team'])->post('teams/{team}/lead', [TeamController::class, 'setLeader']);
    Route::middleware(['permission:assign project team'])->post('teams/{team}/projects', [TeamController::class, 'assignProject']);
    Route::middleware(['permission:remove project team'])->delete('teams/{team}/projects/{project}', [TeamController::class, 'removeProject']);

    // Project Management Routes
    Route::middleware(['permission:list projects'])->get('projects', [ProjectController::class, 'index']);
    Route::middleware(['permission:view project'])->get('projects/{project}', [ProjectController::class, 'show']);
    Route::middleware(['permission:create project'])->post('projects', [ProjectController::class, 'store']);
    Route::middleware(['permission:update project'])->put('projects/{project}', [ProjectController::class, 'update']);
    Route::middleware(['permission:delete project'])->delete('projects/{project}', [ProjectController::class, 'destroy']);
    Route::middleware(['permission:restore project'])->post('projects/{projectId}/restore', [ProjectController::class, 'restore']);
    Route::middleware(['permission:update project'])->post('projects/{project}/manager', [ProjectController::class, 'setManager']);
    Route::middleware(['permission:assign team project'])->post('projects/{project}/teams', [ProjectController::class, 'assignTeams']);

    // Task Management Routes
    Route::middleware(['permission:list tasks'])->get('tasks', [TaskController::class, 'index']);
    Route::middleware(['permission:view task'])->get('tasks/{task}', [TaskController::class, 'show']);
    Route::middleware(['permission:list tasks'])->get('users/{userId}/tasks', [TaskController::class, 'indexByUser']);
    Route::middleware(['permission:list tasks'])->get('projects/{projectId}/tasks', [TaskController::class, 'indexByProject']);
    Route::middleware(['permission:update task'])->post('tasks/{task}/sync-relations', [TaskController::class, 'syncRelations']);
    Route::middleware(['permission:create task'])->post('tasks', [TaskController::class, 'store']);
    Route::middleware(['permission:update task'])->put('tasks/{task}', [TaskController::class, 'update']);
    Route::middleware(['permission:delete task'])->delete('tasks/{task}', [TaskController::class, 'destroy']);
    Route::middleware(['permission:restore task'])->post('tasks/{taskId}/restore', [TaskController::class, 'restore']);
})->middleware('auth:sanctum');
