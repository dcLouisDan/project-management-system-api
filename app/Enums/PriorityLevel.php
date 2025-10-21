<?php

namespace App\Enums;

enum PriorityLevel: string
{
  case LOW = 'low';
  case MEDIUM = 'medium';
  case HIGH = 'high';
  case URGENT = 'urgent';

  /**
   * Get all priority levels as an array
   */
  public static function allLevels(): array
  {
    return array_map(fn($level) => $level->value, self::cases());
  }

  public static function isValidLevel(string $level): bool
  {
    return in_array($level, self::allLevels());
  }

  public function label(): string
  {
    return match ($this) {
      self::LOW => 'Low',
      self::MEDIUM => 'Medium',
      self::HIGH => 'High',
      self::URGENT => 'Urgent',
    };
  }

  public function getRandom(): PriorityLevel
  {
    $levels = self::cases();
    return $levels[array_rand($levels)];
  }
}
