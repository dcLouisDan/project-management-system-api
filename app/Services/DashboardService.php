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
  public function getDashboardStats(User $user)
  {
    return [
      'users' => $this->getUsersStats(),
      'teams' => $this->getTeamsStats(),
      'projects' => $this->getProjectsStats(),
      'tasks' => $this->getTasksStats(),
    ];
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

  public function getProjectsStats()
  {
    return [
      'total' => Project::count(),
      'active' => Project::whereIn('status', [ProgressStatus::NOT_STARTED->value, ProgressStatus::IN_PROGRESS->value])->count(),
      'completed' => Project::where('status', ProgressStatus::COMPLETED->value)->count(),
      'cancelled' => Project::where('status', ProgressStatus::CANCELLED->value)->count(),
      'overdue' => Project::where('due_date', '<', now())->where('status', '!=', ProgressStatus::COMPLETED->value)->count(),
      'by_status' => [
        'not_started' => Project::where('status', ProgressStatus::NOT_STARTED->value)->count(),
        'in_progress' => Project::where('status', ProgressStatus::IN_PROGRESS->value)->count(),
        'awaiting_review' => Project::where('status', ProgressStatus::AWAITING_REVIEW->value)->count(),
        'under_review' => Project::where('status', ProgressStatus::UNDER_REVIEW->value)->count(),
        'completed' => Project::where('status', ProgressStatus::COMPLETED->value)->count(),
        'approved' => Project::where('status', ProgressStatus::APPROVED->value)->count(),
        'rejected' => Project::where('status', ProgressStatus::REJECTED->value)->count(),
        'cancelled' => Project::where('status', ProgressStatus::CANCELLED->value)->count(),
      ],
    ];
  }

  public function getTasksStats()
  {
    return [
      'total' => Task::count(),
      'pending' => Task::where('status', ProgressStatus::NOT_STARTED->value)->count(),
      'in_progress' => Task::where('status', ProgressStatus::IN_PROGRESS->value)->count(),
      'awaiting_review' => Task::where('status', ProgressStatus::AWAITING_REVIEW->value)->count(),
      'completed' => Task::where('status', ProgressStatus::COMPLETED->value)->count(),
      'overdue' => Task::where('due_date', '<', now())->where('status', '!=', ProgressStatus::COMPLETED->value)->count(),
      'by_priority' => [
        'low' => Task::where('priority', PriorityLevel::LOW->value)->count(),
        'medium' => Task::where('priority', PriorityLevel::MEDIUM->value)->count(),
        'high' => Task::where('priority', PriorityLevel::HIGH->value)->count(),
        'urgent' => Task::where('priority', PriorityLevel::URGENT->value)->count(),
      ],
      'by_status' => [
        'not_started' => Task::where('status', ProgressStatus::NOT_STARTED->value)->count(),
        'in_progress' => Task::where('status', ProgressStatus::IN_PROGRESS->value)->count(),
        'awaiting_review' => Task::where('status', ProgressStatus::AWAITING_REVIEW->value)->count(),
        'under_review' => Task::where('status', ProgressStatus::UNDER_REVIEW->value)->count(),
        'completed' => Task::where('status', ProgressStatus::COMPLETED->value)->count(),
      ],
    ];
  }

  public function getRecentProjects(User $user, int $limit = 5)
  {
    $query = Project::orderBy('updated_at', 'desc');
    if ($user->isAdmin()) {
      $query->limit($limit)->get();
    } else {
      $query->where('manager_id', $user->id)->limit($limit)->get();
    }

    return ProjectResource::collection($query->get());
  }

  public function getRecentTasks(User $user, int $limit = 5)
  {
    $query = Task::orderBy('updated_at', 'desc')->with('project');
    if ($user->isAdmin()) {
      $query->limit($limit)->get();
    } else {
      $query->where('assigned_to_id', $user->id)->limit($limit)->get();
    }

    return TaskResource::collection($query->get());
  }
}
