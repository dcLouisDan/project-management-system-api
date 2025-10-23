<?php

namespace App\Models;

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
}
