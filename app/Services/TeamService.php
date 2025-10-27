<?php

namespace App\Services;

use App\Enums\UserRoles;
use App\Events\TeamLeaderAssigned;
use App\Events\TeamLeaderDemoted;
use App\Events\TeamMemberAdded;
use App\Events\TeamMemberRemoved;
use App\Events\TeamMembersBulkAdded;
use App\Events\TeamMembersBulkRemoved;
use App\Events\TeamProjectAssigned;
use App\Events\TeamProjectRemoved;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TeamService
{
    public function buildFilteredQuery(array $filters)
    {
        $query = Team::query();

        // Apply filters based on request parameters
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        if (isset($filters['has_leader'])) {
            $hasLeader = filter_var($filters['has_leader'], FILTER_VALIDATE_BOOLEAN);
            if ($hasLeader) {
                $query->whereHas('users', function ($q) {
                    $q->where('role', 'team_lead');
                });
            } else {
                $query->whereDoesntHave('users', function ($q) {
                    $q->where('role', 'team_lead');
                });
            }
        }

        return $query;
    }

    public function createTeam(array $data): Team
    {
        $team = Team::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return $team;
    }

    public function updateTeam(Team $team, array $data): Team
    {
        $team->update([
            'name' => $data['name'] ?? $team->name,
            'description' => $data['description'] ?? $team->description,
        ]);

        return $team;
    }

    /**
     * Add a user to the team as a member
     *
     * @throws \InvalidArgumentException
     */
    public function addMember(Team $team, User $user, string $role, User $addedBy): void
    {
        if ($team->hasMember($user)) {
            throw new \InvalidArgumentException('User is already a member of this team.');
        }

        $team->users()->attach($user->id, [
            'role' => UserRoles::TEAM_MEMBER->value,
        ]);

        TeamMemberAdded::dispatch(
            $team,
            $user,
            UserRoles::TEAM_MEMBER->value,
            $addedBy
        );
    }

    /**
     * Add users to the team as members
     *
     * @param  array  $usersWithRoles  Array with user ids as key and roles as value [1 => 'team_member', 2 => 'team_lead']
     */
    public function addMembers(Team $team, array $usersWithRoles, User $addedBy): array
    {
        $invalidUsers = [];
        $validUsers = [];
        $hasTeamLead = $team->hasLeader();
        foreach ($usersWithRoles as $userId => $role) {
            if ($team->hasMember($userId)) {
                $invalidUsers[$userId] = [
                    'role' => $role,
                    'reason' => 'already a member',
                ];

                continue;
            }

            if ($hasTeamLead && $role === UserRoles::TEAM_LEAD->value) {
                $invalidUsers[$userId] = [
                    'role' => $role,
                    'reason' => 'team already has a lead',
                ];

                continue;
            }

            $validUsers[$userId] = ['role' => $role];
        }

        if (! empty($validUsers)) {
            $team->users()->attach($validUsers);
        }

        TeamMembersBulkAdded::dispatch(
            $team,
            $validUsers,
            $addedBy
        );

        return ['valid_users' => $validUsers, 'invalid_users' => $invalidUsers];
    }

    /**
     * Set a user as the team lead
     *
     * @throws \InvalidArgumentException
     */
    public function setLeader(Team $team, int $userId, User $updatedBy): Team
    {
        return DB::transaction(function () use ($team, $userId, $updatedBy) {
            $user = User::findOrFail($userId);

            // Verify user has the team lead system role
            if (! $user->isQualifiedAsTeamLead()) {
                throw new \InvalidArgumentException(
                    "User must have the 'team lead' role before being assigned as team leader."
                );
            }

            if ($team->hasLeader()) {
                // Demote current lead to member
                $demotedLead = $team->lead();
                $this->demoteLeader($team);

                TeamLeaderDemoted::dispatch(
                    $team,
                    $demotedLead,
                    $updatedBy
                );
            }

            if (! $team->hasMember($user)) {
                // Add user to team first
                $team->users()->attach($user->id, [
                    'role' => UserRoles::TEAM_LEAD->value,
                ]);
            } else {
                $team->users()->updateExistingPivot($user->id, [
                    'role' => UserRoles::TEAM_LEAD->value,
                ]);
            }

            TeamLeaderAssigned::dispatch(
                $team,
                $demotedLead ?? null,
                $user,
                $updatedBy
            );

            return $team;
        });
    }

    /**
     * Promote a member to team lead (demotes current lead to member)
     *
     * @throws \InvalidArgumentException
     */
    public function promoteToLead(Team $team, User $user): Team
    {
        // Verify user has the team lead system role
        if (! $user->isQualifiedAsTeamLead()) {
            throw new \InvalidArgumentException(
                "User must have the 'team lead' role before being promoted to team leader."
            );
        }

        if (! $team->hasMember($user)) {
            throw new \InvalidArgumentException('User is not a member of this team.');
        }

        // Demote current lead to member if exists
        if ($currentLead = $team->lead()) {
            $team->users()->updateExistingPivot($currentLead->id, [
                'role' => UserRoles::TEAM_MEMBER->value,
            ]);
        }

        // Promote user to lead
        $team->users()->updateExistingPivot($user->id, [
            'role' => UserRoles::TEAM_LEAD->value,
        ]);

        return $team;
    }

    /**
     * Demote the team lead to a regular member
     *
     * @throws \LogicException
     */
    public function demoteLeader(Team $team): Team
    {
        $lead = $team->lead();

        if (! $lead) {
            throw new \LogicException('Team has no leader to demote.');
        }

        $team->users()->updateExistingPivot($lead->id, [
            'role' => UserRoles::TEAM_MEMBER->value,
        ]);

        return $team;
    }

    /**
     * Remove a user from the team
     *
     * @throws \InvalidArgumentException
     */
    public function removeMember(Team $team, User $user, User $removedBy): Team
    {
        if (! $team->hasMember($user)) {
            throw new \InvalidArgumentException('User is not a member of this team.');
        }

        $team->users()->detach($user->id);

        TeamMemberRemoved::dispatch(
            $team,
            $user,
            $removedBy
        );

        return $team;
    }

    /**
     * Remove members in bulk
     */
    public function removeMembers(Team $team, array $users, User $removedBy): Team
    {
        $userIds = array_map(function ($user) {
            return $user instanceof User ? $user->id : $user;
        }, $users);

        TeamMembersBulkRemoved::dispatch(
            $team,
            $userIds,
            $removedBy
        );

        $team->users()->detach($userIds);

        return $team;
    }

    public function assignProject(Team $team, int|Project $project, ?string $notes, User $assignedBy): Team
    {
        $id = $project instanceof Project ? $project->id : $project;

        if (! Project::where('id', $id)->exists()) {
            throw new \InvalidArgumentException('Project does not exist.');
        }

        if ($team->worksOnProject($id)) {
            throw new \InvalidArgumentException('Project already assigned to team.');
        }

        $team->projects()->attach($id, [
            'notes' => $notes,
        ]);

        TeamProjectAssigned::dispatch($team, $project, $assignedBy);

        return $team;
    }

    public function removeProject(Team $team, int|Project $project, User $removedBy): Team
    {
        $id = $project instanceof Project ? $project->id : $project;
        if ($team->worksOnProject($id)) {
            $team->projects()->detach($id);
        } else {
            throw new \InvalidArgumentException('Project is not assigned to team.');
        }

        TeamProjectRemoved::dispatch($team, $project, $removedBy);

        return $team;
    }
}
