<?php

namespace App\Models;

use App\Enums\ProgressStatus;
use App\Enums\UserRoles;
use App\Traits\HasActivityLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use HasActivityLogs, HasFactory, SoftDeletes;

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
    public function lead(): BelongsToMany
    {
        return $this->users()
            ->wherePivot('role', UserRoles::TEAM_LEAD->value);
    }

    /**
     * Get all team members (excluding lead)
     */
    public function members(): BelongsToMany
    {
        return $this->users()
            ->wherePivot('role', UserRoles::TEAM_MEMBER->value);
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)->withPivot('notes')->withTimestamps();
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
    public function hasMember(int|User $user): bool
    {
        $id = $user instanceof User ? $user->id : $user;

        return $this->users()->where('user_id', $id)->exists();
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

    public function isActive(): bool
    {
        return $this->projects()->exists();
    }

    public function worksOnProject(int|Project $project): bool
    {
        $id = $project instanceof Project ? $project->id : $project;

        return $this->projects()->where('project_id', $id)->exists();
    }

    public function getOngoingProjectsCount(): int
    {
        return $this->projects()
            ->whereIn('status', [
                ProgressStatus::NOT_STARTED->value,
                ProgressStatus::IN_PROGRESS->value,
                ProgressStatus::ON_HOLD->value,
            ])
            ->count();
    }

    public function getCompletedProjectsCount(): int
    {
        return $this->projects()
            ->where('status', ProgressStatus::COMPLETED->value)
            ->count();
    }
}
