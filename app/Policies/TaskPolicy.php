<?php

namespace App\Policies;

use App\Enums\UserRoles;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value)) {
            return true;
        }

        if  ($user->can('list tasks')) {
            return true;
        }

        return false;
    }

    public function viewAnyByUser(User $user, int $userId): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value)) {
            return true;
        }

        if ($user->id === $userId) {
            return true;
        }

        return false;
    }

    public function viewAnyInProject(User $user, int $projectId): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value)) {
            return true;
        }

        if ($user->isInProjectTeams($projectId)) {
            return true;
        }

        if ($user->isManagerOfProject($projectId)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value)) {
            return true;
        }

        if ($user->id === $task->assigned_to_id) {
            return true;
        }

        if ($user->isInProjectTeams($task->project) && $user->can('view task')) {
            return true;
        }

        if ($user->isManagerOfProject($task->project) && $user->can('view task')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value)) {
            return true;
        }

        return false;
    }

    public function createInProject(User $user, int $projectId): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value)) {
            return true;
        }

        if ($user->isInProjectTeams($projectId) && $user->can('create task')) {
            return true;
        }

        if ($user->isManagerOfProject($projectId)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value)) {
            return true;
        }

        if ($user->id === $task->assigned_to_id) {
            return true;
        }

        if ($user->isInProjectTeams($task->project) && $user->can('update task')) {
            return true;
        }

        if ($user->isManagerOfProject($task->project) && $user->can('update task')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value)) {
            return true;
        }

        if ($user->isManagerOfProject($task->project) && $user->can('delete task')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value)) {
            return true;
        }

        if ($user->isManagerOfProject($task->project) && $user->can('restore task')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value)) {
            return true;
        }

        if ($user->isManagerOfProject($task->project) && $user->can('delete task')) {
            return true;
        }

        return false;
    }

    public function assignToUser(User $user, Task $task): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value)) {
            return true;
        }

        if ($user->isManagerOfProject($task->project) && $user->can('assign task')) {
            return true;
        }

        if ($user->isInProjectTeamLeads($task->project) && $user->can('assign task')) {
            return true;
        }

        return false;
    }

    public function startTask(User $user, Task $task)
    {
        if ($task->assigned_to_id == $user->id) {
            return true;
        }

        return false;
    }


    public function submitTask(User $user, Task $task)
    {
        if ($task->assigned_to_id == $user->id) {
            return true;
        }

        return false;
    }

    public function reviewTask(User $user, Task $task)
    {
        if ($task->assigned_by_id == $user->id) {
            return true;
        }

        return false;
    }
}
