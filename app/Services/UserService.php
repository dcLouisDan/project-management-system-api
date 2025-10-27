<?php

namespace App\Services;

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

      if (isset($filters['name'])) {
          $query->where('name', 'like', '%' . $filters['name'] . '%');
      }

      if (isset($filters['email'])) {
          $query->where('email', 'like', '%' . $filters['email'] . '%');
      }

      if (isset($filters['role'])) {
          $query->role($filters['role']);
      }

      return $query;
  }



  protected function validateTeamLeadRoleRemoval(User $user, array $newRoles): void
  {
    $currentRoles = $user->getRoleNames()->toArray();

    if (in_array('team_lead', $currentRoles) && !in_array('team_lead', $newRoles) && $user->canChangeFromTeamLeadRole()) {
      throw new \Exception('Cannot remove team_lead role while user leads teams.');
    }
  }
}
