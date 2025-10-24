<?php

namespace App\Services;

use App\Enums\ProjectRelationTypes;
use App\Enums\RelationDirection;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;
use App\Models\ProjectRelation;
use Illuminate\Support\Facades\Log;

class ProjectGraphCache
{

  protected static function key(int $projectId): string
  {
    if ($projectId <= 0) {
      throw new \InvalidArgumentException('Invalid project ID for cache key generation.');
    }

    if (Project::where('id', $projectId)->doesntExist()) {
      throw new \InvalidArgumentException('Project ID does not exist for cache key generation.');
    }

    return 'project_graph_' . $projectId;
  }

  public static function get(int $projectId): ?array
  {
    // Validate project exists before attempting to cache
    if (!Project::where('id', $projectId)->exists()) {
      Log::warning("Attempted to get graph for non-existent project", ['project_id' => $projectId]);
      return null;
    }

    $key = self::key($projectId);
    return Cache::rememberForever($key, function () use ($projectId) {
      return self::build($projectId);
    });
  }

  public static function build(int $projectId): array
  {
    $relations = ProjectRelation::where('project_id', $projectId)->get();

    $graph = [];
    foreach ($relations as $relation) {
      $sourceKey = $relation->source_type . '_' . $relation->source_id;
      $targetKey = $relation->target_type . '_' . $relation->target_id;
      $typeString = $relation->relation_type;
      $direction = ProjectRelationTypes::fromString($typeString)->direction();
      if (!$direction) {
        continue; // Skip invalid relation types
      }

      switch ($direction) {
        case RelationDirection::DEPENDENCY_FORWARD:
          $graph[$sourceKey][] = [
            'target' => $targetKey,
            'target_status' => $relation->target->status ?? null,
            'type' => $typeString,
          ];
          break;

        case RelationDirection::DEPENDENCY_REVERSE:
          $graph[$targetKey][] = [
            'target' => $sourceKey,
            'target_status' => $relation->source->status ?? null,
            'type' => $typeString,
          ];
          break;

        // ignore associative relations for graph traversal
        case RelationDirection::ASSOCIATIVE:
        default:
          break;
      }

      // Ensure all nodes are represented in the graph
      if (!isset($graph[$sourceKey])) {
        $graph[$sourceKey] = [];
      }
      if (!isset($graph[$targetKey])) {
        $graph[$targetKey] = [];
      }
    }

    return $graph;
  }

  public static function refresh(int $projectId): void
  {
    $key = self::key($projectId);
    Cache::put($key, self::build($projectId));
  }

  public static function invalidate(int $projectId): void
  {
    $key = self::key($projectId);
    Cache::forget($key);
  }
}
