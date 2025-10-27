<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;

class ProjectService
{
    public function buildFilteredQuery(array $filters)
    {
        $query = Project::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['manager_id'])) {
            $query->where('manager_id', $filters['manager_id']);
        }

        if (isset($filters['start_date_from']) && isset($filters['start_date_to'])) {
            $query->whereBetween('start_date', [
                $filters['start_date_from'],
                $filters['start_date_to'],
            ]);
        }

        return $query;
    }

    public function createProject(array $data)
    {
        $manager = User::find($data['manager_id'] ?? null);
        if ($manager && ! $manager->isQualifiedAsProjectManager()) {
            throw new \InvalidArgumentException('The specified manager is not qualified to manage projects.', 400);
        }

        return Project::create($data);
    }
}
