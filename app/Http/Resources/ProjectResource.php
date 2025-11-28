<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'manager' => new UserResource($this->whenLoaded('manager')),
            'teams' => TeamResource::collection($this->whenLoaded('teams')),
            'tasks_count' => $this->tasksCount(),
            'milestones_count' => $this->milestonesCount(),
            'teams_count' => $this->teamsCount(),
            'is_overdue' => $this->isOverdue(),
            'completed_tasks_count' => $this->compeletedTasksCount(),
            'pending_tasks_count' => $this->pendingTasksCount(),
            'status' => $this->status,
            'start_date' => $this->start_date,
            'due_date' => $this->due_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
