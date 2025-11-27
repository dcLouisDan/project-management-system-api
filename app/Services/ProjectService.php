<?php

namespace App\Services;

use App\Enums\SoftDeleteStatus;
use App\Events\ProjectTeamsSynced;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    public function buildFilteredQuery(array $filters)
    {
        $query = Project::query();

        $sortableFields = ['id', 'name', 'start_date', 'due_date', 'created_at'];

        if (isset($filters['delete_status']) && SoftDeleteStatus::isValidStatus($filters['delete_status'])) {
            $status = $filters['delete_status'];
            if ($status == SoftDeleteStatus::ALL->value) {
                $query->withTrashed();
            }
            if ($status == SoftDeleteStatus::DELETED->value) {
                $query->onlyTrashed();
            }
        }

        $sort = isset($filters['sort']) && in_array($filters['sort'], $sortableFields) ? $filters['sort'] : 'id';

        $direction = isset($filters['direction']) && in_array($filters['direction'], ['asc', 'desc']) ? $filters['direction'] : 'asc';
        $query->orderBy($sort, $direction);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['manager_id'])) {
            $query->where('manager_id', $filters['manager_id']);
        }

        if (isset($filters['start_date_from']) && isset($filters['start_date_to'])) {
            $query->whereBetween('start_date', [
                $filters['start_date_from'],
                $filters['start_date_to'],
            ]);
        }

        if (isset($filters['due_date_from']) && isset($filters['due_date_to'])) {
            $query->whereBetween('due_date', [
                $filters['due_date_from'],
                $filters['due_date_to'],
            ]);
        }

        if (isset($filters['name'])) {
            $query->where('name', 'ilike', '%' . $filters['name'] . '%');
        }

        if (isset($filters['member_id'])) {
            $memberId = $filters['member_id'];
            $query->whereHas('teams.users', function ($query) use ($memberId) {
                $query->where('users.id', $memberId);
            });
        }

        if (isset($filters['team_id'])) {
            $teamId = $filters['team_id'];
            $query->whereHas('teams', function ($query) use ($teamId) {
                $query->where('teams.id', $teamId);
            });
        }


        return $query;
    }

    public function createProject(array $data)
    {
        $manager = User::find($data['manager_id'] ?? null);
        if ($manager && ! $manager->isQualifiedAsProjectManager()) {
            throw new \InvalidArgumentException('The specified manager is not qualified to manage projects.', 400);
        }

        return Project::create($data);
    }

    public function assignTeams(Project $project, array $teamIds, User $assignedBy)
    {
        $invalidTeams = [];
        $validTeams = [];
        foreach ($teamIds as $teamId => $data) {
            if ($project->hasTeam($teamId)) {
                $invalidTeams[$teamId] = [
                    'notes' => $data['notes'] ?? null,
                    'reason' => 'team already associated with project',
                ];

                continue;
            }
            $validTeams[$teamId] = [
                'notes' => $data['notes'] ?? null,
            ];
        }
        $project->teams()->attach($validTeams);

        return $invalidTeams;
    }

    public function removeTeams(Project $project, array $teamIds, User $removedBy)
    {
        $invalidTeams = [];
        $validTeams = [];
        foreach ($teamIds as $teamId) {
            if (! $project->hasTeam($teamId)) {
                $invalidTeams[] = $teamId;

                continue;
            }
            $validTeams[] = $teamId;
        }
        $project->teams()->detach($validTeams);

        return $invalidTeams;
    }

    public function syncTeams(Project $project, array $teamIds, User $assignedBy): Project
    {
        return DB::transaction(function () use ($project, $teamIds, $assignedBy) {

            $currentTeams = $project->teams()->pluck('teams.id')->toArray();
            $toAdd = array_diff($teamIds, $currentTeams);
            $toRemove = array_diff($currentTeams, $teamIds);

            if (is_array($toAdd) && !empty($toAdd)) {
                $project->teams()->attach($toAdd);
            }

            if (is_array($toRemove) && !empty($toRemove)) {
                $project->teams()->detach($toRemove);
            }

            ProjectTeamsSynced::dispatch(
                $project,
                $toAdd,
                $toRemove,
                $teamIds,
                $assignedBy
            );

            return $project;
        });
    }

    public function assignManager(Project $project, int $userId, User $assignedBy): Project
    {
        $manager = User::find($userId);
        if (! $manager->isQualifiedAsProjectManager()) {
            throw new \InvalidArgumentException('User must have the Project Manager role or Admin role to be assigned as manager.');
        }

        $project->manager_id = $manager->id;
        $project->save();

        return $project;
    }
}
