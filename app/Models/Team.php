<?php

namespace App\Models;

use App\Enums\UserRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Team extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get all users in the team with their roles
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the team lead
     */
    public function lead(): ?User
    {
        return $this->users()
            ->wherePivot('role', UserRoles::TEAM_LEAD->value)
            ->first();
    }

    /**
     * Get all team members (excluding lead)
     */
    public function members(): BelongsToMany
    {
        return $this->users()
            ->wherePivot('role', UserRoles::TEAM_MEMBER->value);
    }

    /**
     * Check if team has a leader
     */
    public function hasLeader(): bool
    {
        return $this->users()->wherePivot('role', UserRoles::TEAM_LEAD->value)->exists();
    }

    /**
     * Check if user is a member of the team (any role)
     */
    public function hasMember(User $user): bool
    {
        return $this->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if user is specifically a team member (not lead)
     */
    public function isMember(User $user): bool
    {
        return $this->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', UserRoles::TEAM_MEMBER->value)
            ->exists();
    }

    /**
     * Check if user is the team lead
     */
    public function isLead(User $user): bool
    {
        return $this->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', UserRoles::TEAM_LEAD->value)
            ->exists();
    }


    /**
     * Add a user to the team as a member
     * 
     * @throws \InvalidArgumentException
     */
    public function addMember(User $user): void
    {
        if ($this->hasMember($user)) {
            throw new \InvalidArgumentException("User is already a member of this team.");
        }

        $this->users()->attach($user->id, [
            'role' => UserRoles::TEAM_MEMBER->value
        ]);
    }

    /**
     * Set a user as the team lead
     * 
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function setLeader(User $user): void
    {
        // Verify user has the team lead system role
        if (!$user->isQualifiedAsTeamLead()) {
            throw new \InvalidArgumentException(
                "User must have the 'team lead' role before being assigned as team leader."
            );
        }

        if ($this->hasLeader()) {
            throw new \LogicException(
                "Team already has a leader. Remove the current leader first or use promoteToLead()."
            );
        }

        if (!$this->hasMember($user)) {
            // Add user to team first
            $this->users()->attach($user->id, [
                'role' => UserRoles::TEAM_LEAD->value
            ]);
        } else {
            $this->users()->updateExistingPivot($user->id, [
                'role' => UserRoles::TEAM_LEAD->value
            ]);
        }
    }

    /**
     * Promote a member to team lead (demotes current lead to member)
     * 
     * @throws \InvalidArgumentException
     */
    public function promoteToLead(User $user): void
    {
        // Verify user has the team lead system role
        if (!$user->isQualifiedAsTeamLead()) {
            throw new \InvalidArgumentException(
                "User must have the 'team lead' role before being promoted to team leader."
            );
        }

        if (!$this->hasMember($user)) {
            throw new \InvalidArgumentException("User is not a member of this team.");
        }

        // Demote current lead to member if exists
        if ($currentLead = $this->lead()) {
            $this->users()->updateExistingPivot($currentLead->id, [
                'role' => UserRoles::TEAM_MEMBER->value
            ]);
        }

        // Promote user to lead
        $this->users()->updateExistingPivot($user->id, [
            'role' => UserRoles::TEAM_LEAD->value
        ]);
    }

    /**
     * Demote the team lead to a regular member
     * 
     * @throws \LogicException
     */
    public function demoteLeader(): void
    {
        $lead = $this->lead();

        if (!$lead) {
            throw new \LogicException("Team has no leader to demote.");
        }

        $this->users()->updateExistingPivot($lead->id, [
            'role' => UserRoles::TEAM_MEMBER->value
        ]);
    }

    /**
     * Remove a user from the team
     * 
     * @throws \InvalidArgumentException
     */
    public function removeMember(User $user): void
    {
        if (!$this->hasMember($user)) {
            throw new \InvalidArgumentException("User is not a member of this team.");
        }

        $this->users()->detach($user->id);
    }

    /**
     * Get the count of team members (excluding lead)
     */
    public function memberCount(): int
    {
        return $this->members()->count();
    }

    /**
     * Get the total count of all users in team (including lead)
     */
    public function totalUserCount(): int
    {
        return $this->users()->count();
    }
}
