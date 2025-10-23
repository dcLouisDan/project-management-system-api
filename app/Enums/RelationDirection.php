<?php

namespace App\Enums;

enum RelationDirection: string
{
  case DEPENDENCY_FORWARD = 'dependency_forward';
  case DEPENDENCY_REVERSE = 'dependency_reverse';
  case ASSOCIATIVE = 'associative';

  public static function fromString(string $direction): ?self
  {
    return match ($direction) {
      'dependency_forward' => self::DEPENDENCY_FORWARD,
      'dependency_reverse' => self::DEPENDENCY_REVERSE,
      'associative' => self::ASSOCIATIVE,
      default => null,
    };
  }

  public function label(): string
  {
    return match ($this) {
      self::DEPENDENCY_FORWARD => 'Dependency Forward',
      self::DEPENDENCY_REVERSE => 'Dependency Reverse',
      self::ASSOCIATIVE => 'Associative',
    };
  }

  public static function allDirections(): array
  {
    return array_map(fn($direction) => $direction->value, self::cases());
  }
}
