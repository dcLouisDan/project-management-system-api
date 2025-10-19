<?php

use App\Http\Controllers\TeamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


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
    Route::middleware(['permission:restore user'])->post('users/{user}/restore', [UserController::class, 'restore']);
    Route::middleware(['permission:assign role user'])->post('users/{user}/roles', [UserController::class, 'assignRoles']);


    // Team Management Routes
    Route::middleware(['permission:list teams'])->get('teams', [TeamController::class, 'index']);
    Route::middleware(['permission:create team'])->post('teams', [TeamController::class, 'store']);
    Route::middleware(['permission:view team'])->get('teams/{team}', [TeamController::class, 'show']);
    Route::middleware(['permission:update team'])->put('teams/{team}', [TeamController::class, 'update']);
    Route::middleware(['permission:delete team'])->delete('teams/{team}', [TeamController::class, 'destroy']);
})->middleware('auth:sanctum');
