<?php

namespace App\Models;

use App\Enums\ProgressStatus;
use App\Services\ProjectGraphCache;
use App\Traits\HasActivityLogs;
use App\Traits\HasProjectRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Milestone extends Model
{
    use SoftDeletes, HasProjectRelations, HasFactory, HasActivityLogs;

    protected $fillable = [
        'project_id',
        'name',
        'description',
        'due_date',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function isCompleted(): bool
    {
        return $this->status === ProgressStatus::COMPLETED->value;
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && !$this->isCompleted();
    }

    public function setStatus(string $newStatus, bool $overrideCompleted = false)
    {
        if (!ProgressStatus::isValidStatus($newStatus)) {
            throw new \InvalidArgumentException("Invalid status: $newStatus");
        }

        if ($this->isCompleted() && !$overrideCompleted) {
            throw new \LogicException("Cannot change status of a completed milestone without override.");
        }

        $this->status = $newStatus;
        $this->save();

        ProjectGraphCache::invalidate($this->project_id);
    }
}
