<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait HasActivityLogsNoSoftDelete
{
  /**
   * Relationship to activity logs
   */
  public function activityLogs()
  {
    return $this->morphMany(ActivityLog::class, 'auditable');
  }

  /**
   * Log an activity for the model
   */
  protected static function bootHasActivityLogs(): void
  {
    static::created(function ($model) {
      ActivityLog::logActivity(
        Auth::id() ?? null,
        'created',
        $model,
        'created ' . class_basename($model),
        []
      );
    });

    static::updated(function ($model) {
      $changes = $model->getChanges();
      $original = $model->getOriginal();

      ActivityLog::logActivity(
        Auth::id() ?? null,
        'updated',
        $model,
        'updated ' . class_basename($model),
        [],
        array_intersect_key($original, $changes),
        $changes
      );
    });

    static::deleted(function ($model) {
      ActivityLog::logActivity(
        Auth::id() ?? null,
        'deleted',
        $model,
        'deleted ' . class_basename($model),
        []
      );
    });
  }
}
