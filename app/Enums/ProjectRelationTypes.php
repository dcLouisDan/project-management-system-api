<?php

namespace App\Enums;


enum ProjectRelationTypes: string
{
  case BLOCKS = 'blocks';
  case REQUIRES = 'requires';
  case FOLLOWS = 'follows';
  case RELATED_TO = 'related_to';
  case DUPLICATE_OF = 'duplicate_of';
  case PARENT = 'parent';
  case CHILD = 'child';

  /**
   * Get all relation type values as an array
   */
  public static function allTypes(): array
  {
    return array_map(fn($type) => $type->value, self::cases());
  }

  public static function isValidType(string $type): bool
  {
    return in_array($type, self::allTypes());
  }

  public function label(): string
  {
    return match ($this) {
      self::BLOCKS => 'Blocks',
      self::REQUIRES => 'Requires',
      self::FOLLOWS => 'Follows',
      self::RELATED_TO => 'Related To',
      self::DUPLICATE_OF => 'Duplicate Of',
      self::PARENT => 'Parent',
      self::CHILD => 'Child',
    };
  }
}
