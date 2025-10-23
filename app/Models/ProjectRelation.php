<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRelation extends Model
{
    protected $fillable = [
        'project_id',
        'created_by_id',
        'source_type',
        'source_id',
        'target_type',
        'target_id',
        'relation_type',
    ];

    public function source()
    {
        return $this->morphTo();
    }

    public function target()
    {
        return $this->morphTo();
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
