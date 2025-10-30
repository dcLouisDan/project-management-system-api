<?php

namespace App\Models;

use App\Traits\HasActivityLogsNoSoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectRelation extends Model
{
    use HasActivityLogsNoSoftDelete, HasFactory;

    protected $fillable = [
        'project_id',
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
}
