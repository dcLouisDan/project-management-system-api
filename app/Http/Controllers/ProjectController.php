<?php

namespace App\Http\Controllers;

use App\Enums\ProgressStatus;
use App\Http\Responses\ApiResponse;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @group Project Management
 * APIs for managing projects
 */
class ProjectController extends Controller
{
    protected function buildFilteredQuery(Request $request)
    {
        $query = Project::query();

        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->has('manager_id')) {
            $query->where('manager_id', $request->input('manager_id'));
        }

        if ($request->has('start_date_from') && $request->has('start_date_to')) {
            $query->whereBetween('start_date', [
                $request->input('start_date_from'),
                $request->input('start_date_to')
            ]);
        }

        return $query;
    }

    /**
     * List projects
     * 
     * Get a paginated list of all projects with optional filtering.
     * 
     * @queryParam status string Filter by project status. Example: in_progress
     * @queryParam manager_id integer Filter by manager ID. Example: 3
     * @queryParam start_date_from date Filter by start date (from). Example: 2024-01-01
     * @queryParam start_date_to date Filter by start date (to). Example: 2024-12-31
     * @queryParam per_page integer Number of items per page. Default is 15. Example: 10
     * 
     * @apiResourceModel App\Models\Project paginate=15
     * 
     * @response status=200 scenario="success" {"data": [{"id": 1, "name": "New Website", "description": "Build company website", "status": "in_progress", "start_date": "2024-01-01", "due_date": "2024-06-30"}], "links": {}, "meta": {}}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to retrieve projects: Database connection error", "errors": [], "meta": []}
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Project::class)) {
            return ApiResponse::error('Unauthorized to list projects.', 403);
        }

        $perPage = $request->input('per_page', 15);
        $query = $this->buildFilteredQuery($request);

        try {
            $projects = $query->paginate($perPage);
            return $projects;
        } catch (\Exception $e) {
            Log::error('Error fetching projects', ['error' => $e->getMessage(), 'requested_by' => $request->user()->id]);
            return ApiResponse::error('Failed to retrieve projects: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get project
     * 
     * Get details of a specific project by ID, including its manager and assigned teams.
     * 
     * @apiResourceModel App\Models\Project
     * 
     * @response status=200 scenario="success" {"data": {"id": 1, "name": "New Website", "description": "Build company website", "status": "in_progress", "manager": {"id": 2, "name": "Jane Manager"}, "teams": []}, "message": "Project details retrieved successfully."}
     * @response status=404 scenario="not found" {"message": "Project not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to retrieve project details: Internal server error", "errors": [], "meta": []}
     */
    public function show(Request $request, Project $project)
    {
        if ($request->user()->cannot('view', $project)) {
            return ApiResponse::error('Unauthorized to view project.', 403);
        }

        try {
            return ApiResponse::success($project->load(['manager', 'teams']), 'Project details retrieved successfully.', 200);
        } catch (\Exception $e) {
            Log::error('Error fetching project details', ['error' => $e->getMessage(), 'project_id' => $project->id, 'requested_by' => $request->user()->id]);
            return ApiResponse::error('Failed to retrieve project details: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Create project
     * 
     * Create a new project with the provided details.
     * 
     * @bodyParam name string required Name of the project. Must be unique. Example: New Website
     * @bodyParam description string Description of the project. Example: Build a responsive company website
     * @bodyParam manager_id integer The ID of the project manager. Must be a user with admin or project manager role. Example: 3
     * @bodyParam status string required Current status of the project. Allowed values: not started, in progress, completed, on hold. Example: in_progress
     * @bodyParam start_date date required Project start date. Example: 2024-01-01
     * @bodyParam due_date date Project due date. Must be on or after start_date. Example: 2024-06-30
     * 
     * @response status=201 scenario="success" {"data": {"id": 1, "name": "New Website", "description": "Build a responsive company website", "manager_id": 3, "status": "in_progress", "start_date": "2024-01-01", "due_date": "2024-06-30", "created_at": "2024-01-01T12:00:00Z", "updated_at": "2024-01-01T12:00:00Z"}, "message": "Project created successfully."}
     * @response status=400 scenario="unqualified manager" {"data": null, "message": "The specified manager is not qualified to manage projects.", "errors": [], "meta": []}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"name": ["The name has already been taken."]}}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to create project: Internal server error", "errors": [], "meta": []}
     */
    public function store(Request $request)
    {
        if ($request->user()->cannot('create', Project::class)) {
            return ApiResponse::error('Unauthorized to create project.', 403);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|unique:projects,name',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'required|string|in:' . implode(',', ProgressStatus::allStatuses()),
            'start_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        try {
            $manager = User::find($validatedData['manager_id'] ?? null);
            if ($manager && !$manager->isQualifiedAsProjectManager()) {
                return ApiResponse::error('The specified manager is not qualified to manage projects.', 400);
            }

            $project = Project::create($validatedData);
            return ApiResponse::success($project, 'Project created successfully.', 201);
        } catch (\Exception $e) {
            Log::error('Error creating project', ['error' => $e->getMessage(), 'requested_by' => $request->user()->id]);
            return ApiResponse::error('Failed to create project: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update project
     * 
     * Update an existing project's details.
     * 
     * @bodyParam name string Name of the project. Must be unique. Example: Updated Website Project
     * @bodyParam description string Description of the project. Example: Updated project description
     * @bodyParam manager_id integer The ID of the project manager. Must be a user with admin or project manager role. Example: 4
     * @bodyParam status string Current status of the project. Allowed values: not started, in progress, completed, on hold. Example: completed
     * @bodyParam start_date date Project start date. Example: 2024-01-15
     * @bodyParam due_date date Project due date. Must be on or after start_date. Example: 2024-07-31
     * 
     * @response status=200 scenario="success" {"data": {"id": 1, "name": "Updated Website Project", "description": "Updated project description", "manager_id": 4, "status": "completed", "start_date": "2024-01-15", "due_date": "2024-07-31", "created_at": "2024-01-01T12:00:00Z", "updated_at": "2024-02-01T12:00:00Z"}, "message": "Project updated successfully."}
     * @response status=400 scenario="unqualified manager" {"data": null, "message": "The specified manager is not qualified to manage projects.", "errors": [], "meta": []}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"name": ["The name has already been taken."]}}
     * @response status=404 scenario="not found" {"message": "Project not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to update project: Internal server error", "errors": [], "meta": []}
     */
    public function update(Request $request, Project $project)
    {
        if ($request->user()->cannot('update', $project)) {
            return ApiResponse::error('Unauthorized to update project.', 403);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|unique:projects,name,' . $project->id,
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'sometimes|required|string|in:' . implode(',', ProgressStatus::allStatuses()),
            'start_date' => 'sometimes|required|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        try {
            if (isset($validatedData['manager_id'])) {
                $manager = User::find($validatedData['manager_id']);
                if ($manager && !$manager->isQualifiedAsProjectManager()) {
                    return ApiResponse::error('The specified manager is not qualified to manage projects.', 400);
                }
            }

            $project->update($validatedData);
            return ApiResponse::success($project, 'Project updated successfully.', 200);
        } catch (\Exception $e) {
            Log::error('Error updating project', ['error' => $e->getMessage(), 'project_id' => $project->id, 'requested_by' => $request->user()->id]);
            return ApiResponse::error('Failed to update project: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Soft delete project
     * 
     * Soft delete a project from the system.
     * 
     * @response status=200 scenario="success" {"data": null, "message": "Project deleted successfully."}
     * @response status=404 scenario="not found" {"message": "Project not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to delete project: Internal server error", "errors": [], "meta": []}
     */
    public function destroy(Request $request, Project $project)
    {
        if ($request->user()->cannot('delete', $project)) {
            return ApiResponse::error('Unauthorized to delete project.', 403);
        }

        try {
            $project->delete();
            return ApiResponse::success(null, 'Project deleted successfully.', 200);
        } catch (\Exception $e) {
            Log::error('Error deleting project', ['error' => $e->getMessage(), 'project_id' => $project->id, 'requested_by' => $request->user()->id]);
            return ApiResponse::error('Failed to delete project: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Restore soft-deleted project
     * 
     * Restore a previously deleted project.
     * 
     * @response status=200 scenario="success" {"data": {"id": 1, "name": "New Website", "description": "Build company website", "status": "in_progress"}, "message": "Project restored successfully."}
     * @response status=404 scenario="not found" {"message": "Project not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to restore project: Internal server error", "errors": [], "meta": []}
     */
    public function restore(Request $request, Project $project)
    {
        if ($request->user()->cannot('restore', $project)) {
            return ApiResponse::error('Unauthorized to restore project.', 403);
        }

        try {
            $project->restore();
            return ApiResponse::success($project, 'Project restored successfully.', 200);
        } catch (\Exception $e) {
            Log::error('Error restoring project', ['error' => $e->getMessage(), 'project_id' => $project->id, 'requested_by' => $request->user()->id]);
            return ApiResponse::error('Failed to restore project: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Set project manager
     * 
     * Assign or change the project manager for a specific project.
     * 
     * @bodyParam manager_id integer required The ID of the user to assign as project manager. Must be a user with admin or project manager role. Example: 5
     * 
     * @response status=200 scenario="success" {"data": {"id": 1, "name": "New Website", "description": "Build company website", "manager_id": 5, "status": "in_progress"}, "message": "Project manager assigned successfully."}
     * @response status=400 scenario="unqualified manager" {"data": null, "message": "The specified manager is not qualified to manage projects.", "errors": [], "meta": []}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"manager_id": ["The selected manager id is invalid."]}}
     * @response status=404 scenario="not found" {"message": "Project not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to assign project manager: Internal server error", "errors": [], "meta": []}
     */
    public function setManager(Request $request, Project $project)
    {
        if ($request->user()->cannot('update', $project)) {
            return ApiResponse::error('Unauthorized to assign project manager.', 403);
        }

        $validatedData = $request->validate([
            'manager_id' => 'required|exists:users,id',
        ]);

        try {
            $manager = User::find($validatedData['manager_id']);
            if (!$manager->isQualifiedAsProjectManager()) {
                return ApiResponse::error('The specified manager is not qualified to manage projects.', 400);
            }

            $project->manager_id = $validatedData['manager_id'];
            $project->save();

            return ApiResponse::success($project, 'Project manager assigned successfully.', 200);
        } catch (\Exception $e) {
            Log::error('Error assigning project manager', ['error' => $e->getMessage(), 'project_id' => $project->id, 'requested_by' => $request->user()->id]);
            return ApiResponse::error('Failed to assign project manager: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Assign teams to project
     * 
     * Assign multiple teams to a project in a single request.
     * 
     * @bodyParam team_ids integer[] required Array of team IDs to assign to the project. Example: [1, 3, 5]
     * 
     * @response status=200 scenario="success" {"data": {"project": {"id": 1, "name": "New Website", "teams": [{"id": 1, "name": "Development Team"}, {"id": 3, "name": "QA Team"}]}, "invalid_team_ids": []}, "message": "Teams assigned to project successfully."}
     * @response status=200 scenario="success with invalid teams" {"data": {"project": {"id": 1, "name": "New Website", "teams": [{"id": 1, "name": "Development Team"}]}, "invalid_team_ids": [99]}, "message": "Teams assigned to project successfully."}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"team_ids.0": ["The selected team_ids.0 is invalid."]}}
     * @response status=404 scenario="not found" {"message": "Project not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to assign teams to project: Internal server error", "errors": [], "meta": []}
     */
    public function assignTeams(Request $request, Project $project)
    {
        if ($request->user()->cannot('assignTeam', $project)) {
            return ApiResponse::error('Unauthorized to assign teams to project.', 403);
        }

        $validatedData = $request->validate([
            'team_ids' => 'required|array',
            'team_ids.*' => 'integer|exists:teams,id',
        ]);

        try {
            $invalidTeamIds = $project->assignTeams($validatedData['team_ids']);
            return ApiResponse::success(
                [
                    'project' => $project->load('teams'),
                    'invalid_team_ids' => $invalidTeamIds
                ],
                'Teams assigned to project successfully.',
                200
            );
        } catch (\Exception $e) {
            Log::error('Error assigning teams to project', ['error' => $e->getMessage(), 'project_id' => $project->id, 'requested_by' => $request->user()->id]);
            return ApiResponse::error('Failed to assign teams to project: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove teams from project
     * 
     * Remove multiple teams from a project in a single request.
     * 
     *@response status=200 scenario="success" {"data": {"project": {"id": 1, "name": "New Website", "teams": []}, "invalid_team_ids": []}, "message": "Teams removed from project successfully."}
     * @response status=200 scenario="success with invalid teams" {"data": {"project": {"id": 1, "name": "New Website", "teams": [{"id": 2, "name": "Design Team"}]}, "invalid_team_ids": [99]}, "message": "Teams removed from project successfully."}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"team_ids.0": ["The selected team_ids.0 is invalid."]}}
     * @response status=404 scenario="not found" {"message": "Project not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to remove teams from project: Internal server error", "errors": [], "meta": []} 
     */
    public function removeTeams(Request $request, Project $project)
    {
        if ($request->user()->cannot('removeTeam', $project)) {
            return ApiResponse::error('Unauthorized to remove teams from project.', 403);
        }

        $validatedData = $request->validate([
            'team_ids' => 'required|array',
            'team_ids.*' => 'integer|exists:teams,id',
        ]);

        try {
            $invalidTeamIds = $project->removeTeams($validatedData['team_ids']);
            return ApiResponse::success(
                [
                    'project' => $project->load('teams'),
                    'invalid_team_ids' => $invalidTeamIds
                ],
                'Teams removed from project successfully.',
                200
            );
        } catch (\Exception $e) {
            Log::error('Error removing teams from project', ['error' => $e->getMessage(), 'project_id' => $project->id, 'requested_by' => $request->user()->id]);
            return ApiResponse::error('Failed to remove teams from project: ' . $e->getMessage(), 500);
        }
    }
}
