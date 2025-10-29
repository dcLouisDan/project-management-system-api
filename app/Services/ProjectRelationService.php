<?php

namespace App\Services;

use App\Enums\ProjectRelationTypes;
use App\Enums\RelationDirection;
use App\Models\Milestone;
use App\Models\ProjectRelation;
use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProjectRelationService
{
    public function __construct(
        protected DependencyValidator $dependencyValidator
    ) {
        // Initialization code if needed
    }

    protected function validateStringIsModelClass(string $modelClass): void
    {
        if (! class_exists($modelClass) || ! is_subclass_of($modelClass, Model::class)) {
            throw new \InvalidArgumentException("Invalid model class: $modelClass");
        }
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

    public function createProjectRelation(string $relationType, Model $source, string $targetModelClass, int $targetId): ProjectRelation
    {
        // Validate the relation type
        if (! ProjectRelationTypes::isValidType($relationType)) {
            throw new \InvalidArgumentException("Invalid relation type: $relationType");
        }

        $this->validateStringIsModelClass($targetModelClass);

        $target = $targetModelClass::find($targetId);

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

        return $this;
    }

    /**
     * Sync outgoing relations for a model
     *
     * @param  Model  $model  The source model
     * @param  array  $targetIdsWithTypes  Array of ['id' => int, 'relation' => string]
     * @param  string  $targetModelClass  The target model class
     * @return array Sync statistics
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function syncOutgoingRelations(
        Model $model,
        array $targetIdsWithTypes,
        string $targetModelClass
    ): array {
        // Validate model has trait
        $this->validateModelsHasProjectRelationsTrait([$model]);

        // Validate target model class
        $this->validateStringIsModelClass($targetModelClass);

        if (! in_array($targetModelClass, [Task::class, Milestone::class])) {
            throw new \InvalidArgumentException(
                "Target model must be Task or Milestone, got: $targetModelClass"
            );
        }

        // Validate input structure and relation types
        $this->validateSyncInput($targetIdsWithTypes);

        return DB::transaction(function () use ($model, $targetIdsWithTypes, $targetModelClass) {
            $stats = [
                'created' => 0,
                'updated' => 0,
                'kept' => 0,
                'deleted' => 0,
                'errors' => [],
            ];

            // Get current relations: [target_id => relation_type]
            $currentRelations = $this->getOutgoingRelationsMap($model, $targetModelClass);

            // Extract target IDs for bulk loading
            $requestedTargetIds = array_column($targetIdsWithTypes, 'id');

            // Bulk load all target models (prevents N+1)
            $targetModels = $targetModelClass::whereIn('id', $requestedTargetIds)
                ->where('project_id', $model->project_id)
                ->get()
                ->keyBy('id');

            // Track which relations should be kept
            $processedTargetIds = [];

            // Process each requested relation
            foreach ($targetIdsWithTypes as $targetData) {
                $targetId = $targetData['id'];
                $desiredRelationType = $targetData['relation'];

                // Verify target exists in same project
                if (! $targetModels->has($targetId)) {
                    $stats['errors'][] = [
                        'target_id' => $targetId,
                        'relation' => $desiredRelationType,
                        'error' => 'Target not found or not in same project',
                    ];

                    continue;
                }

                $targetModel = $targetModels->get($targetId);

                try {
                    // Case 1: Relation exists with same type → Keep it
                    if (isset($currentRelations[$targetId])
                        && $currentRelations[$targetId] === $desiredRelationType
                    ) {
                        $processedTargetIds[] = $targetId;
                        $stats['kept']++;

                        continue;
                    }

                    // Case 2: Relation exists with different type → Update it
                    if (isset($currentRelations[$targetId])
                        && $currentRelations[$targetId] !== $desiredRelationType
                    ) {
                        // Remove old relation
                        $this->removeRelation(
                            $currentRelations[$targetId],
                            $model,
                            $targetModel
                        );

                        // Create new relation with new type
                        $this->createProjectRelation(
                            $desiredRelationType,
                            $model,
                            $targetModelClass,
                            $targetId
                        );

                        $processedTargetIds[] = $targetId;
                        $stats['updated']++;

                        continue;
                    }

                    // Case 3: Relation doesn't exist → Create it
                    $this->createProjectRelation(
                        $desiredRelationType,
                        $model,
                        $targetModelClass,
                        $targetId
                    );

                    $processedTargetIds[] = $targetId;
                    $stats['created']++;

                } catch (\InvalidArgumentException|\LogicException $e) {
                    // Business rule violation (circular dependency, inverse exists, etc.)
                    $stats['errors'][] = [
                        'target_id' => $targetId,
                        'relation' => $desiredRelationType,
                        'error' => $e->getMessage(),
                    ];
                } catch (\Exception $e) {
                    // Unexpected error
                    Log::error('Unexpected error syncing relation', [
                        'source' => get_class($model).':'.$model->id,
                        'target_id' => $targetId,
                        'relation' => $desiredRelationType,
                        'error' => $e->getMessage(),
                    ]);

                    $stats['errors'][] = [
                        'target_id' => $targetId,
                        'relation' => $desiredRelationType,
                        'error' => 'Internal error occurred',
                    ];
                }
            }

            // Delete relations that weren't in the request
            $relationsToDelete = array_diff(
                array_keys($currentRelations),
                $processedTargetIds
            );

            foreach ($relationsToDelete as $targetId) {
                $relationType = $currentRelations[$targetId];

                try {
                    // Delete directly via query (more efficient)
                    ProjectRelation::where([
                        'source_type' => get_class($model),
                        'source_id' => $model->id,
                        'target_type' => $targetModelClass,
                        'target_id' => $targetId,
                        'relation_type' => $relationType,
                    ])->delete();

                    $stats['deleted']++;

                } catch (\Exception $e) {
                    Log::error('Failed to delete relation during sync', [
                        'source' => get_class($model).':'.$model->id,
                        'target_id' => $targetId,
                        'relation' => $relationType,
                        'error' => $e->getMessage(),
                    ]);

                    $stats['errors'][] = [
                        'target_id' => $targetId,
                        'relation' => $relationType,
                        'error' => 'Failed to delete relation',
                    ];
                }
            }

            // Invalidate project graph cache
            ProjectGraphCache::invalidate($model->project_id);

            return $stats;
        });
    }

    /**
     * Validate sync input structure
     *
     * @throws \InvalidArgumentException
     */
    protected function validateSyncInput(array $targetIdsWithTypes): void
    {
        if (empty($targetIdsWithTypes)) {
            return; // Empty array is valid (means remove all relations)
        }

        foreach ($targetIdsWithTypes as $index => $targetData) {
            if (! is_array($targetData)) {
                throw new \InvalidArgumentException(
                    "Invalid input at index $index: expected array"
                );
            }

            if (! isset($targetData['id'])) {
                throw new \InvalidArgumentException(
                    "Missing 'id' key at index $index"
                );
            }

            if (! isset($targetData['relation'])) {
                throw new \InvalidArgumentException(
                    "Missing 'relation' key at index $index"
                );
            }

            if (! is_int($targetData['id']) && ! is_numeric($targetData['id'])) {
                throw new \InvalidArgumentException(
                    "Invalid 'id' at index $index: must be integer"
                );
            }

            if (! ProjectRelationTypes::isValidType($targetData['relation'])) {
                throw new \InvalidArgumentException(
                    "Invalid relation type '{$targetData['relation']}' at index $index"
                );
            }
        }
    }

    protected function getOutgoingRelationsMap(Model $model, string $targetModelClass): array
    {
        return $model->outgoingRelations()
            ->where('target_type', $targetModelClass)
            ->get()
            ->mapWithKeys(function ($relation) {
                return [$relation->target_id => $relation->relation_type];
            })
            ->toArray();
    }
}
