<?php

namespace App\Enums;

use Illuminate\Database\Eloquent\Relations\Relation;

enum ProjectRelationTypes: string
{
  case BLOCKS = 'blocks';
  case REQUIRES = 'requires';
  case FOLLOWS = 'follows';
  case RELATED_TO = 'related_to';
  case DUPLICATE_OF = 'duplicate_of';
  case PARENT_OF = 'parent_of';

  /**
   * Get all relation type values as an array
   */
  public static function allTypes(): array
  {
    return array_map(fn($type) => $type->value, self::cases());
  }

  public static function fromString(string $type): ?self
  {
    return match ($type) {
      'blocks' => self::BLOCKS,
      'requires' => self::REQUIRES,
      'follows' => self::FOLLOWS,
      'related_to' => self::RELATED_TO,
      'duplicate_of' => self::DUPLICATE_OF,
      'parent_of' => self::PARENT_OF,
      default => null,
    };
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
      self::PARENT_OF => 'Parent Of',
    };
  }

  public function inverse(): self
  {
    return match ($this) {
      self::BLOCKS => self::REQUIRES,
      self::REQUIRES => self::BLOCKS,
      self::FOLLOWS => self::FOLLOWS,
      self::RELATED_TO => self::RELATED_TO,
      self::DUPLICATE_OF => self::DUPLICATE_OF,
      self::PARENT_OF => self::PARENT_OF,
    };
  }

  public function direction(): RelationDirection
  {
    return match ($this) {
      self::BLOCKS,
      self::PARENT_OF => RelationDirection::DEPENDENCY_FORWARD,

      self::REQUIRES,
      self::FOLLOWS => RelationDirection::DEPENDENCY_REVERSE,

      self::RELATED_TO,
      self::DUPLICATE_OF => RelationDirection::ASSOCIATIVE,
    };
  }
}
