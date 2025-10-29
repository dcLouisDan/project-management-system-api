<?php

namespace App\Services;

use App\Enums\ProjectRelationTypes;
use App\Enums\RelationDirection;
use App\Models\ProjectRelation;
use Illuminate\Database\Eloquent\Model;

class ProjectRelationService
{
    public function __construct(
        protected DependencyValidator $dependencyValidator
    ) {
        // Initialization code if needed
    }

    public function validateModelsHasProjectRelationsTrait(array $models): void
    {
        foreach ($models as $model) {
            if (! in_array(\App\Traits\HasProjectRelations::class, class_uses(get_class($model)))) {
                throw new \InvalidArgumentException('All models must use the HasProjectRelations trait.');
            }
        }
    }

    public function parentExists(Model $model): bool
    {
        return ProjectRelation::where([
            'target_type' => get_class($model),
            'target_id' => $model->id,
            'relation_type' => ProjectRelationTypes::PARENT_OF->value,
        ])->exists();
    }

    public function inverseRelationExists(string $relationType, Model $source, Model $target): bool
    {
        $inverseType = ProjectRelationTypes::fromString($relationType)?->inverse()->value;
        if (! $inverseType) {
            return false;
        }

        return ProjectRelation::where([
            'source_type' => get_class($target),
            'source_id' => $target->id,
            'target_type' => get_class($source),
            'target_id' => $source->id,
            'relation_type' => $inverseType,
        ])->exists();
    }

    public function checkForCircularDependency(string $relationType, Model $source, Model $target): bool
    {
        $graph = ProjectGraphCache::get($source->project_id);

        $direction = ProjectRelationTypes::fromString($relationType)->direction();

        if ($direction === RelationDirection::ASSOCIATIVE) {
            return false;
        }

        if ($direction === RelationDirection::DEPENDENCY_FORWARD) {
            return DependencyValidator::hasCircularDependency($graph, $source, $target);
        }

        return DependencyValidator::hasCircularDependency($graph, $target, $source);
    }

    public function createProjecRelation(string $relationType, Model $source, Model $target): ProjectRelation
    {
        // Validate the relation type
        if (! ProjectRelationTypes::isValidType($relationType)) {
            throw new \InvalidArgumentException("Invalid relation type: $relationType");
        }

        if ($source->project_id !== $target->project_id) {
            throw new \InvalidArgumentException('Source and target models must belong to the same project.');
        }

        $this->validateModelsHasProjectRelationsTrait([$source, $target]);

        if ($this->checkForCircularDependency($relationType, $source, $target)) {
            throw new \LogicException('Creating this relation would introduce a circular dependency.');
        }

        if ($this->inverseRelationExists($relationType, $source, $target)) {
            throw new \InvalidArgumentException('Inverse relation already exists.');
        }

        if ($relationType === ProjectRelationTypes::PARENT_OF->value && $this->parentExists($target)) {
            throw new \InvalidArgumentException('The target item already has a parent.');
        }

        $projectRelation = ProjectRelation::create([
            'source_type' => get_class($source),
            'source_id' => $source->id,
            'target_type' => get_class($target),
            'target_id' => $target->id,
            'relation_type' => $relationType,
        ]);

        ProjectGraphCache::invalidate($source->project_id);

        return $projectRelation;
    }

    public function removeRelation(string $relationType, Model $source, Model $target)
    {
        ProjectRelation::where([
            'source_type' => get_class($source),
            'source_id' => $source->id,
            'target_type' => get_class($target),
            'target_id' => $target->id,
            'relation_type' => $relationType,
        ])->delete();

        ProjectGraphCache::invalidate($source->project_id);

        return $this;
    }
}
