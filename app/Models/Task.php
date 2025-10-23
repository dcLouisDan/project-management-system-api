<?php

namespace App\Models;

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

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
