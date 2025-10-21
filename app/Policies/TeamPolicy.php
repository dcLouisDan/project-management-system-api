<?php

namespace App\Policies;

use App\Enums\UserRoles;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TeamPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('list teams')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Team $team): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('view team')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('create team')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Team $team): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('update team')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Team $team): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('delete team')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Team $team): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('restore team')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Team $team): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('force delete team')) {
            return true;
        }
        return false;
    }

    public function addMember(User $user, Team $team): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value)) {
            return true;
        }
        if ($team->lead()->id == $user->id && $user->can('add member team')) {
            return  true;
        }
        return false;
    }

    public function removeMember(User $user, Team $team): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value)) {
            return true;
        }
        if ($team->lead()->id == $user->id && $user->can('remove member team')) {
            return  true;
        }
        return false;
    }

    public function assignProject(User $user, Team $team): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('assign project team')) {
            return true;
        }
        return false;
    }

    public function removeProject(User $user, Team $team): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('remove project team')) {
            return true;
        }
        return false;
    }

    public function assignRoles(User $user, Team $team): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('assign roles team')) {
            return true;
        }
        return false;
    }
}
