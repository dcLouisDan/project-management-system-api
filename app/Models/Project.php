<?php

namespace App\Models;

use App\Enums\ProgressStatus;
use App\Traits\HasActivityLogs;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasActivityLogs, SoftDeletes, HasFactory;

    protected $fillable = [
        'name',
        'description',
        'manager_id',
        'status',
        'start_date',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'due_date' => 'date',
        ];
    }

    /**
     * Get the manager of the project
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)->withPivot('notes')->withTimestamps();
    }

    public function setManager(User $user)
    {
        if (!$user->isQualifiedAsProjectManager()) {
            throw new \InvalidArgumentException("User must have the Project Manager role or Admin role to be assigned as manager.");
        }

        $this->manager_id = $user->id;
        $this->save();
    }

    public function setStatus($status)
    {
        if (!ProgressStatus::isValidStatus($status)) {
            throw new \InvalidArgumentException("Invalid status: $status");
        }

        $this->status = $status;
        $this->save();
    }

    public function hasTeam(int|Team $team): bool
    {
        $teamId = $team instanceof Team ? $team->id : $team;
        return $this->teams()->where('teams.id', $teamId)->exists();
    }

    public function assignTeams(array $teamIds)
    {
        $invalidTeams = [];
        $validTeams = [];
        foreach ($teamIds as $teamId => $data) {
            if ($this->hasTeam($teamId)) {
                $invalidTeams[$teamId] = [
                    'notes' => $data['notes'] ?? null,
                    'reason' => 'team already associated with project'
                ];
                continue;
            }
            $validTeams[$teamId] = [
                'notes' => $data['notes'] ?? null
            ];
        }
        $this->teams()->attach($validTeams);
        return $invalidTeams;
    }

    public function removeTeams(array $teamIds)
    {
        $invalidTeams = [];
        $validTeams = [];
        foreach ($teamIds as $teamId) {
            if (!$this->hasTeam($teamId)) {
                $invalidTeams[] = $teamId;
                continue;
            }
            $validTeams[] = $teamId;
        }
        $this->teams()->detach($validTeams);
        return $invalidTeams;
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
