<?php

namespace App\Traits;

use App\Enums\ProjectRelationTypes;
use App\Enums\RelationDirection;
use App\Models\ProjectRelation;
use App\Services\DependencyValidator;
use App\Services\ProjectGraphCache;
use Illuminate\Database\Eloquent\Model;

trait HasProjectRelations
{
    protected static function bootHasProjectRelations()
    {
        static::created(function ($model) {
            ProjectGraphCache::invalidate($model->project_id);
        });

        static::updated(function ($model) {
            ProjectGraphCache::invalidate($model->project_id);
        });

        static::deleting(function ($model) {
            $model->outgoingRelations()->delete();
            $model->incomingRelations()->delete();
        });

        static::deleted(function ($model) {
            ProjectGraphCache::invalidate($model->project_id);
        });
    }

    public function parentExists(): bool
    {
        return ProjectRelation::where([
            'target_type' => self::class,
            'target_id' => $this->id,
            'relation_type' => ProjectRelationTypes::PARENT_OF->value,
        ])->exists();
    }

    public function inverseRelationExists(string $relationType, Model $target): bool
    {
        $inverseType = ProjectRelationTypes::fromString($relationType)?->inverse()->value;
        if (! $inverseType) {
            return false;
        }

        return ProjectRelation::where([
            'source_type' => get_class($target),
            'source_id' => $target->id,
            'target_type' => self::class,
            'target_id' => $this->id,
            'relation_type' => $inverseType,
        ])->exists();
    }

    public function hasCircularDependency(string $relationType, Model $target): bool
    {
        $graph = ProjectGraphCache::get($this->project_id);

        $direction = ProjectRelationTypes::fromString($relationType)->direction();

        if ($direction === RelationDirection::ASSOCIATIVE) {
            return false;
        }

        if ($direction === RelationDirection::DEPENDENCY_FORWARD) {
            return DependencyValidator::hasCircularDependency($graph, $this, $target);
        }

        return DependencyValidator::hasCircularDependency($graph, $target, $this);
    }

    public function addRelation(string $relationType, Model $target, ?int $createdById = null)
    {
        // Validate the relation type
        if (! ProjectRelationTypes::isValidType($relationType)) {
            throw new \InvalidArgumentException("Invalid relation type: $relationType");
        }

        if ($this->project_id !== $target->project_id) {
            throw new \InvalidArgumentException('Cannot create relation between items from different projects.');
        }

        if ($this->inverseRelationExists($relationType, $target)) {
            throw new \InvalidArgumentException('Inverse relation already exists.');
        }

        if ($this->hasCircularDependency($relationType, $target)) {
            throw new \InvalidArgumentException('Adding this relation would create a circular dependency.');
        }

        if ($relationType === ProjectRelationTypes::PARENT_OF->value && $target->parentExists()) {
            throw new \InvalidArgumentException('The target item already has a parent.');
        }

        ProjectRelation::create([
            'project_id' => $this->project_id,
            'created_by_id' => $createdById,
            'source_type' => self::class,
            'source_id' => $this->id,
            'target_type' => get_class($target),
            'target_id' => $target->id,
            'relation_type' => $relationType,
        ]);

        ProjectGraphCache::invalidate($this->project_id);

        return $this;
    }

    public function removeRelation(string $relationType, Model $target)
    {
        ProjectRelation::where([
            'source_type' => self::class,
            'source_id' => $this->id,
            'target_type' => get_class($target),
            'target_id' => $target->id,
            'relation_type' => $relationType,
        ])->delete();

        ProjectGraphCache::invalidate($this->project_id);

        return $this;
    }

    public function outgoingRelations()
    {
        return $this->morphMany(ProjectRelation::class, 'source');
    }

    public function incomingRelations()
    {
        return $this->morphMany(ProjectRelation::class, 'target');
    }

    public function blocks()
    {
        return $this->outgoingRelations()->where('relation_type', ProjectRelationTypes::BLOCKS->value)->with('target');
    }

    public function blockedBy()
    {
        return $this->incomingRelations()->where('relation_type', ProjectRelationTypes::BLOCKS->value)->with('source');
    }

    public function requires()
    {
        return $this->outgoingRelations()->where('relation_type', ProjectRelationTypes::REQUIRES->value)->with('target');
    }

    public function requiredBy()
    {
        return $this->incomingRelations()->where('relation_type', ProjectRelationTypes::REQUIRES->value)->with('source');
    }

    public function relatesTo()
    {
        return $this->outgoingRelations()->where('relation_type', ProjectRelationTypes::RELATES_TO->value)->with('target');
    }

    public function children()
    {
        return $this->outgoingRelations()->where('relation_type', ProjectRelationTypes::PARENT_OF->value)->with('target');
    }
}
