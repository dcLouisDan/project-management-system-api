<?php

namespace App\Enums;

enum UserRoles: string
{
  case ADMIN = 'admin';
  case PROJECT_MANAGER = 'project manager';
  case TEAM_LEAD = 'team lead';
  case TEAM_MEMBER = 'team member';

  /**
   * Get all role values as an array
   */
  public static function allRoles(): array
  {
    return array_map(fn($role) => $role->value, self::cases());
  }
}
