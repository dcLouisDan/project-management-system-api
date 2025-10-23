<?php

namespace App\Enums;

enum ProgressStatus: string
{
  case NOT_STARTED = 'not_started';
  case ASSIGNED = 'assigned';
  case IN_PROGRESS = 'in_progress';
  case AWAITING_REVIEW = 'awaiting_review';
  case UNDER_REVIEW = 'under_review';
  case APPROVED = 'approved';
  case REJECTED = 'rejected';
  case COMPLETED = 'completed';
  case ON_HOLD = 'on_hold';

  /**
   * Get all status values as an array
   */
  public static function allStatuses(): array
  {
    return array_map(fn($status) => $status->value, self::cases());
  }

  public static function reviewStatuses(): array
  {
    return [
      self::AWAITING_REVIEW->value,
      self::UNDER_REVIEW->value,
      self::APPROVED->value,
      self::REJECTED->value,
    ];
  }

  public static function isValidReviewStatus(string $status): bool
  {
    return in_array($status, self::reviewStatuses());
  }

  public static function isValidStatus(string $status): bool
  {
    return in_array($status, self::allStatuses());
  }

  public function label(): string
  {
    return match ($this) {
      self::NOT_STARTED => 'Not Started',
      self::IN_PROGRESS => 'In Progress',
      self::COMPLETED => 'Completed',
      self::ON_HOLD => 'On Hold',
    };
  }

  public function getRandom(): ProgressStatus
  {
    $statuses = self::cases();
    return $statuses[array_rand($statuses)];
  }
}
