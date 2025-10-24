<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;

class DependencyValidator
{
  protected function getNodeKey(Model $model): string
  {
    return $model->getMorphClass() . '_' . $model->id;
  }

  public static function hasCircularDependency(array $graph, Model $startNode, Model $targetNode): bool
  {
    $startNodeKey = (new self())->getNodeKey($startNode);
    $targetNodeKey = (new self())->getNodeKey($targetNode);

    $visited = [];
    $stack = [$startNodeKey];

    while (!empty($stack)) {
      $currentNode = array_pop($stack);

      if ($currentNode === $targetNodeKey) {
        return true; // Circular dependency detected
      }

      if (isset($visited[$currentNode])) {
        continue;
      }

      $visited[$currentNode] = true;

      if (isset($graph[$currentNode])) {
        foreach ($graph[$currentNode] as $edge) {
          $stack[] = $edge['target'];
        }
      }
    }

    return false; // No circular dependency
  }

  public static function hasIncompleteDependencies(array $graph, Model $node, string $requiredStatus): bool
  {
    $nodeKey = (new self())->getNodeKey($node);

    if (!isset($graph[$nodeKey])) {
      return false; // No dependencies
    }

    foreach ($graph[$nodeKey] as $edge) {
      if (($edge['target_status'] ?? null) !== $requiredStatus) {
        return true; // Found a dependency that does not meet the required status
      }
    }

    return false; // All dependencies meet the required status
  }
}
