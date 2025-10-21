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

  public static function isValidRole(string $role): bool
  {
    return in_array($role, self::allRoles());
  }

  public function label(): string
  {
    return match ($this) {
      self::ADMIN => 'Administrator',
      self::PROJECT_MANAGER => 'Project Manager',
      self::TEAM_LEAD => 'Team Lead',
      self::TEAM_MEMBER => 'Team Member',
    };
  }

  public function getRandom(): UserRoles
  {
    $roles = self::cases();
    return $roles[array_rand($roles)];
  }
}
