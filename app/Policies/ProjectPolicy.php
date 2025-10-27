<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Enums\UserRoles;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('list projects')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value)) {
            return true;
        }
        if ($user->isInProjectTeams($project) || $user->can('view project')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('create project')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('edit project')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('delete project')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('restore project')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value)) {
            return true;
        }
        return false;
    }

    public function assignTeam(User $user, Project $project): bool
    {
        if (
            $user->hasRole(UserRoles::ADMIN->value) ||
            ($user->can('assign team project') && $project->manager_id === $user->id)
        ) {
            return true;
        }
        return false;
    }

    public function removeTeam(User $user, Project $project): bool
    {
        if (
            $user->hasRole(UserRoles::ADMIN->value) ||
            ($user->can('remove team project') && $project->manager_id === $user->id)
        ) {
            return true;
        }
        return false;
    }

    public function markComplete(User $user, Project $project): bool
    {
        if (
            $user->hasRole(UserRoles::ADMIN->value) ||
            ($user->can('mark project complete') && $project->manager_id === $user->id)
        ) {
            return true;
        }
        return false;
    }

    public function viewReport(User $user, Project $project): bool
    {
        if (
            $user->hasRole(UserRoles::ADMIN->value) ||
            ($user->can('view project report') && $project->manager_id === $user->id)
        ) {
            return true;
        }
        return false;
    }
}
