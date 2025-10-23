<?php

namespace App\Models;

use App\Enums\ProgressStatus;
use App\Traits\HasActivityLogs;
use App\Traits\HasProjectRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasProjectRelations, SoftDeletes, HasFactory, HasActivityLogs;

    protected $fillable = [
        'project_id',
        'assigned_to_id',
        'assigned_by_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by_id');
    }

    public function reviews()
    {
        return $this->hasMany(TaskReview::class);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function isCompleted(): bool
    {
        return $this->status === ProgressStatus::COMPLETED;
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && !$this->isCompleted();
    }

    public function markAsCompleted()
    {
        $this->status = ProgressStatus::COMPLETED;
        $this->save();
    }

    public function updateStatus(string $newStatus, bool $overrideCompleted = false)
    {
        if (!ProgressStatus::isValidStatus($newStatus)) {
            throw new \InvalidArgumentException("Invalid status: $newStatus");
        }

        if ($this->isCompleted() && !$overrideCompleted) {
            throw new \LogicException("Cannot change status of a completed task without override.");
        }

        $this->status = $newStatus;
        $this->save();
    }
}
