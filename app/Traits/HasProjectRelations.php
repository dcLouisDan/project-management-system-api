<?php

namespace App\Traits;

use App\Enums\ProjectRelationTypes;
use App\Models\ProjectRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait HasProjectRelations
{
  public function addRelation(string $relationType, Model $target)
  {
    // Validate the relation type
    if (!ProjectRelationTypes::isValidType($relationType)) {
      throw new \InvalidArgumentException("Invalid relation type: $relationType");
    }

    return ProjectRelation::create([
      'project_id' => $this->project_id,
      'created_by_id' => Auth::id() ?? null,
      'source_type' => self::class,
      'source_id' => $this->id,
      'target_type' => get_class($target),
      'target_id' => $target->id,
      'relation_type' => $relationType,
    ]);
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
    return $this->outgoingRelations()->where('relation_type', 'blocks')->with('target');
  }

  public function blockedBy()
  {
    return $this->incomingRelations()->where('relation_type', 'blocks')->with('source');
  }

  public function requires()
  {
    return $this->outgoingRelations()->where('relation_type', 'requires')->with('target');
  }

  public function requiredBy()
  {
    return $this->incomingRelations()->where('relation_type', 'requires')->with('source');
  }

  public function relatesTo()
  {
    return $this->outgoingRelations()->where('relation_type', 'relates_to')->with('target');
  }
}
