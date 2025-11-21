<?php

namespace App\Services;

use App\Enums\SoftDeleteStatus;
use App\Events\UserRolesAssigned;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            if (isset($data['roles']) && is_array($data['roles'])) {
                $user->syncRoles($data['roles']);
            }

            return $user;
        });
    }

    public function updateUser(User $user, array $data): User
    {
        $user->fill([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
        ]);

        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        if (isset($data['roles']) && is_array($data['roles'])) {
            $user->syncRoles($data['roles']);
        }

        return $user;
    }

    public function assignRoles(User $user, array $roles, User $assignedBy): User
    {
        $this->validateTeamLeadRoleRemoval($user, $roles);

        return DB::transaction(function () use ($user, $roles, $assignedBy) {
            $currentRoles = $user->getRoleNames()->toArray();

            $user->syncRoles($roles);

            UserRolesAssigned::dispatch($user, $currentRoles, $roles, $assignedBy);

            return $user;
        });
    }

    public function deleteUser(User $user): bool
    {
        return $user->delete();
    }

    public function restoreUser(User $user): User
    {
        $user->restore();

        return $user;
    }

    public function buildFilteredUserQuery(array $filters)
    {
        $query = User::query();
        $sortableFields = ['id', 'name', 'created_at'];

        if (isset($filters['delete_status']) && SoftDeleteStatus::isValidStatus($filters['delete_status'])) {
            $status = $filters['delete_status'];
            if ($status == SoftDeleteStatus::ALL->value) {
                $query->withTrashed();
            }
            if ($status == SoftDeleteStatus::DELETED->value) {
                $query->onlyTrashed();
            }
        }

        if (isset($filters['name'])) {
            $query->where('name', 'ilike', '%' . $filters['name'] . '%');
        }

        if (isset($filters['email'])) {
            $query->where('email', 'ilike', '%' . $filters['email'] . '%');
        }

        if (isset($filters['role'])) {
            $query->role($filters['role']);
        }

        if (isset($filters['roles'])) {
            $roles = array_map('trim', explode(',', $filters['roles']));
            $query->whereHas('roles', function ($q) use ($roles) {
                $q->whereIn('name', $roles);
            });
        }

        if (isset($filters['team_id'])) {
            $teamId = $filters['team_id'];
            $query->whereHas('teams', function ($q) use ($teamId) {
                $q->where('id', $teamId);
            });
        }

        if (isset($filters['project_id'])) {
            $projectId = $filters['project_id'];
            $query->whereHas('teams.projects', function ($query) use ($projectId) {
                $query->where('projects.id', $projectId);
            })->get();
        }

        $sort = isset($filters['sort']) && in_array($filters['sort'], $sortableFields) ? $filters['sort'] : 'id';

        $direction = isset($filters['direction']) && in_array($filters['direction'], ['asc', 'desc']) ? $filters['direction'] : 'asc';
        $query->orderBy($sort, $direction);

        return $query;
    }

    protected function validateTeamLeadRoleRemoval(User $user, array $newRoles): void
    {
        $currentRoles = $user->getRoleNames()->toArray();

        if (in_array('team_lead', $currentRoles) && ! in_array('team_lead', $newRoles) && $user->canChangeFromTeamLeadRole()) {
            throw new \Exception('Cannot remove team_lead role while user leads teams.');
        }
    }
}
