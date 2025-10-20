<?php

namespace App\Models;

use App\Enums\UserRoles;
use App\Traits\HasActivityLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles, SoftDeletes, HasActivityLogs;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The teams that the user belongs to.
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Check if the user is actively leading any team.
     * 
     * This checks both:
     * 1. User has the 'team lead' system role (qualification)
     * 2. User is assigned as team lead in at least one team (active assignment)
     * 
     * Used to prevent role changes when user has active team leadership responsibilities.
     */
    public function isActivelyLeadingTeams(): bool
    {
        // Must have the team lead role
        if (!$this->hasRole(UserRoles::TEAM_LEAD->value)) {
            return false;
        }

        // Must be actively assigned as team lead in at least one team
        return $this->teams()
            ->wherePivot('role', UserRoles::TEAM_LEAD->value)
            ->exists();
    }

    /**
     * Check if user is qualified to be a team lead (has the system role).
     */
    public function isQualifiedAsTeamLead(): bool
    {
        return $this->hasRole(UserRoles::TEAM_LEAD->value);
    }

    /**
     * Check if user has the team lead role but is not actively leading any teams.
     * 
     * This indicates the user can safely have their role changed.
     */
    public function canChangeFromTeamLeadRole(): bool
    {
        return $this->isQualifiedAsTeamLead() && !$this->isActivelyLeadingTeams();
    }

    /**
     * Get all teams where this user is the team lead.
     */
    public function ledTeams(): BelongsToMany
    {
        return $this->teams()
            ->wherePivot('role', UserRoles::TEAM_LEAD->value);
    }

    /**
     * Get all teams where this user is a member (not lead).
     */
    public function memberTeams(): BelongsToMany
    {
        return $this->teams()
            ->wherePivot('role', UserRoles::TEAM_MEMBER->value);
    }

    /**
     * Get all activity logs for the user.
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get all projects managed by the user.
     */
    public function managedProjects()
    {
        return $this->hasMany(Project::class, 'manager_id');
    }

    public function isQualifiedAsProjectManager(): bool
    {
        return $this->hasRole(UserRoles::PROJECT_MANAGER->value);
    }

    public function isActivelyManagingProjects(): bool
    {
        return $this->managedProjects()->exists();
    }

    public function canChangeFromProjectManagerRole(): bool
    {
        return $this->isQualifiedAsProjectManager() && !$this->isActivelyManagingProjects();
    }
}
