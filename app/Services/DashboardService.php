<?php

namespace App\Services;

use App\Enums\PriorityLevel;
use App\Enums\ProgressStatus;
use App\Enums\UserRoles;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\User;
use App\Models\Team;
use App\Models\Task;

class DashboardService
{
  public function getDashboardStats(User $user, ?string $forceRole = null)
  {
    $stats = [];

    if ($forceRole && !UserRoles::isValidRole($forceRole)) {
      $forceRole = null;
    }

    $role = $forceRole ?? $user->getRoleNames()->first();

    $stats = [
      'projects' => $this->getProjectsStats($user, $forceRole),
      'tasks' => $this->getTasksStats($user, $forceRole),
    ];

    if ($role === UserRoles::ADMIN->value) {
      $stats['users'] = $this->getUsersStats();
      $stats['teams'] = $this->getTeamsStats();
    }

    return $stats;
  }

  public function getUsersStats()
  {
    return [
      'total' => User::withTrashed()->count(),
      'active' => User::count(),
      'deleted' => User::onlyTrashed()->count(),
      'by_role' => [
        'admin' => User::role(UserRoles::ADMIN->value)->count(),
        'project_manager' => User::role(UserRoles::PROJECT_MANAGER->value)->count(),
        'team_lead' => User::role(UserRoles::TEAM_LEAD->value)->count(),
        'team_member' => User::role(UserRoles::TEAM_MEMBER->value)->count(),
      ],
    ];
  }

  public function getTeamsStats()
  {
    return [
      'total' => Team::withTrashed()->count(),
      'active' => Team::count(),
      'deleted' => Team::onlyTrashed()->count(),
      'with_lead' => Team::whereHas('users', function ($query) {
        $query->where('role', UserRoles::TEAM_LEAD->value);
      })->count(),
      'without_lead' => Team::whereDoesntHave('users', function ($query) {
        $query->where('role', UserRoles::TEAM_LEAD->value);
      })->count(),
      'active' => Team::has('projects')->count(),
    ];
  }

  public function getProjectsStats(User $user, ?string $forceRole = null)
  {
    $query = Project::query();
    if ($forceRole && !UserRoles::isValidRole($forceRole)) {
      $forceRole = null;
    }
    $role = $forceRole ?? $user->getRoleNames()->first();
    if ($role === UserRoles::ADMIN->value) {
      $query->withTrashed();
    } else if ($role === UserRoles::PROJECT_MANAGER->value) {
      $query->where('manager_id', $user->id);
    }

    return [
      'total' => $query->count(),
      'active' => $query->whereIn('status', [ProgressStatus::NOT_STARTED->value, ProgressStatus::IN_PROGRESS->value])->count(),
      'completed' => $query->where('status', ProgressStatus::COMPLETED->value)->count(),
      'cancelled' => $query->where('status', ProgressStatus::CANCELLED->value)->count(),
      'overdue' => $query->where('due_date', '<', now())->where('status', '!=', ProgressStatus::COMPLETED->value)->count(),
      'by_status' => [
        'not_started' => $query->where('status', ProgressStatus::NOT_STARTED->value)->count(),
        'in_progress' => $query->where('status', ProgressStatus::IN_PROGRESS->value)->count(),
        'awaiting_review' => $query->where('status', ProgressStatus::AWAITING_REVIEW->value)->count(),
        'under_review' => $query->where('status', ProgressStatus::UNDER_REVIEW->value)->count(),
        'completed' => $query->where('status', ProgressStatus::COMPLETED->value)->count(),
      ],
    ];
  }

  public function getTasksStats(User $user, ?string $forceRole = null)
  {
    $query = Task::query();
    if ($forceRole && !UserRoles::isValidRole($forceRole)) {
      $forceRole = null;
    }
    $role = $forceRole ?? $user->getRoleNames()->first();
    if ($role === UserRoles::ADMIN->value) {
      $query->withTrashed();
    } else if ($role === UserRoles::PROJECT_MANAGER->value) {
      $query->whereHas('project', function ($q) use ($user) {
        $q->where('manager_id', $user->id);
      });
    } else if ($role == UserRoles::TEAM_LEAD->value) {
      $query->whereHas('project.teams', function ($q) use ($user) {
        $q->whereHas('users', function ($q) use ($user) {
          $q->where('users.id', $user->id)->where('role', UserRoles::TEAM_LEAD->value);
        });
      });
    } else {
      $query->where('assigned_to_id', $user->id);
    }

    return [
      'total' => $query->count(),
      'pending' => $query->where('status', ProgressStatus::NOT_STARTED->value)->count(),
      'in_progress' => $query->where('status', ProgressStatus::IN_PROGRESS->value)->count(),
      'awaiting_review' => $query->where('status', ProgressStatus::AWAITING_REVIEW->value)->count(),
      'completed' => $query->where('status', ProgressStatus::COMPLETED->value)->count(),
      'overdue' => $query->where('due_date', '<', now())->where('status', '!=', ProgressStatus::COMPLETED->value)->count(),
      'by_priority' => [
        'low' => $query->where('priority', PriorityLevel::LOW->value)->count(),
        'medium' => $query->where('priority', PriorityLevel::MEDIUM->value)->count(),
        'high' => $query->where('priority', PriorityLevel::HIGH->value)->count(),
        'urgent' => $query->where('priority', PriorityLevel::URGENT->value)->count(),
      ],
      'by_status' => [
        'not_started' => $query->where('status', ProgressStatus::NOT_STARTED->value)->count(),
        'in_progress' => $query->where('status', ProgressStatus::IN_PROGRESS->value)->count(),
        'awaiting_review' => $query->where('status', ProgressStatus::AWAITING_REVIEW->value)->count(),
        'under_review' => $query->where('status', ProgressStatus::UNDER_REVIEW->value)->count(),
        'completed' => $query->where('status', ProgressStatus::COMPLETED->value)->count(),
      ],
    ];
  }

  public function getRecentProjects(User $user, int $limit = 5, ?string $forceRole = null)
  {
    $query = Project::orderBy('updated_at', 'desc');
    if ($forceRole && !UserRoles::isValidRole($forceRole)) {
      $forceRole = null;
    }
    $role = $forceRole ?? $user->getRoleNames()->first();
    if ($role === UserRoles::ADMIN->value) {
      $query->limit($limit)->get();
    } else if ($role === UserRoles::PROJECT_MANAGER->value) {
      $query->where('manager_id', $user->id)->limit($limit)->get();
    } else if ($role == UserRoles::TEAM_LEAD->value) {
      $query->whereHas('teams', function ($q) use ($user) {
        $q->whereHas('users', function ($q) use ($user) {
          $q->where('users.id', $user->id)->where('role', UserRoles::TEAM_LEAD->value);
        });
      })->limit($limit)->get();
    } else {
      $query->whereHas('teams', function ($q) use ($user) {
        $q->whereHas('users', function ($q) use ($user) {
          $q->where('users.id', $user->id)->where('role', UserRoles::TEAM_MEMBER->value);
        });
      })->limit($limit)->get();
    }

    return ProjectResource::collection($query->get());
  }

  public function getRecentTasks(User $user, int $limit = 5, ?string $forceRole = null)
  {
    $query = Task::orderBy('updated_at', 'desc')->with('project');
    if ($forceRole && !UserRoles::isValidRole($forceRole)) {
      $forceRole = null;
    }
    $role = $forceRole ?? $user->getRoleNames()->first();

    if ($role === UserRoles::ADMIN->value) {
      $query->limit($limit)->get();
    } else if ($role === UserRoles::PROJECT_MANAGER->value) {
      $query->whereHas('project', function ($q) use ($user) {
        $q->where('manager_id', $user->id);
      })->limit($limit)->get();
    } else if ($role == UserRoles::TEAM_LEAD->value) {
      $query->whereHas('project.teams', function ($q) use ($user) {
        $q->whereHas('users', function ($q) use ($user) {
          $q->where('users.id', $user->id)->where('role', UserRoles::TEAM_LEAD->value);
        });
      })->limit($limit)->get();
    } else {
      $query->where('assigned_to_id', $user->id)->limit($limit)->get();
    }

    return TaskResource::collection($query->get());
  }
}
