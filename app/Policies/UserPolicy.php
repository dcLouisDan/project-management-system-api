<?php

namespace App\Policies;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('list users')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('view user')) {
            return true;
        }
        if ($user->id === $model->id) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('create user')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('update user')) {
            return true;
        }
        if ($user->id === $model->id) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('delete user')) {
            return true;
        }
        if ($user->id === $model->id) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('restore user')) {
            return true;
        }
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('delete user')) {
            return true;
        }
        return false;
    }

    public function assignRole(User $user, User $model): bool
    {
        if ($user->hasRole(UserRoles::ADMIN->value) || $user->can('assign role user')) {
            return true;
        }
        return false;
    }
}
