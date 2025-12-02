<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use App\Models\TaskReview;
use App\Models\Team;
use App\Models\User;

class ActivityLogService
{
  private $modelMap = [
    'user' => User::class,
    'project' => Project::class,
    'task' => Task::class,
    'team' => Team::class,
    'milestone' => Milestone::class,
    'task_review' => TaskReview::class,
  ];

  public function buildFilteredQuery(array $filters)
  {
    $query = ActivityLog::query();

    $sortableFields = ['id', 'created_at'];

    $sort = isset($filters['sort']) && in_array($filters['sort'], $sortableFields) ? $filters['sort'] : 'id';

    $direction = isset($filters['direction']) && in_array($filters['direction'], ['asc', 'desc']) ? $filters['direction'] : 'asc';
    $query->orderBy($sort, $direction);

    if (isset($filters['user_id'])) {
      $query->where('user_id', $filters['user_id']);
    }

    if (isset($filters['action'])) {
      $query->where('action', $filters['action']);
    }

    if (isset($filters['model']) && isset($this->modelMap[$filters['model']])) {
      $model = $this->modelMap[$filters['model']];
      $query->where('auditable_type', $model);
    }

    if (isset($filters['model_id'])) {
      $query->where('auditable_id', $filters['model_id']);
    }

    if (isset($filters['description'])) {
      $query->where('description', 'ilike', '%' . $filters['description'] . '%');
    }

    return $query;
  }
}
