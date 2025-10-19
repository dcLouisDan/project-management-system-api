<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @group Team Management
 *
 * APIs for managing teams
 */
class TeamController extends Controller
{

    private function buildFilteredQuery(Request $request)
    {
        $query = Team::query();

        // Apply filters based on request parameters
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('has_leader')) {
            $hasLeader = filter_var($request->input('has_leader'), FILTER_VALIDATE_BOOLEAN);
            if ($hasLeader) {
                $query->whereHas('users', function ($q) {
                    $q->where('role', 'team_lead');
                });
            } else {
                $query->whereDoesntHave('users', function ($q) {
                    $q->where('role', 'team_lead');
                });
            }
        }

        return $query;
    }
    /**
     * List teams
     * 
     * Get a paginated list of teams with optional filters.
     * 
     * @queryParam name string Filter teams by name (partial match). Example: Development
     * @queryParam has_leader boolean Filter teams by whether they have a leader assigned. Example: true
     * @queryParam per_page integer Number of results per page. Default is 15. Example: 10
     * 
     * @apiResourceModel App\Models\Team paginate=15
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $query = $this->buildFilteredQuery($request);
        try {
            $teams = $query->paginate($perPage);

            return $teams;
        } catch (\Exception $e) {
            Log::error('Error fetching teams.', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()?->id,
            ]);
            return ApiResponse::error(
                message: 'Failed to retrieve teams: ' . $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * Create team
     * 
     * Create a new team with the provided details.
     * 
     * @response status=201 scenario="success" {"data": {"id": 1, "name": "Development Team", "description": "Handles all development tasks", "created_at": "2024-01-01T12:00:00Z", "updated_at": "2024-01-01T12:00:00Z"}, "message": "Team created successfully"}
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            // Name of the Team. Example: Development
            'name' => 'required|string|max:255|unique:teams,name',
            // Description of the Team. Example: Team responsible for product development
            'description' => 'nullable|string',
        ]);

        try {
            $team = Team::create($validatedData);

            return ApiResponse::success(
                data: $team,
                message: 'Team created successfully',
                statusCode: 201
            );
        } catch (\Exception $e) {
            Log::error('Error creating team.', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()?->id,
            ]);
            return ApiResponse::error(
                message: 'Failed to create team: ' . $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * Get team
     */
    public function show(Team $team)
    {
        return ApiResponse::success(
            data: $team->load('users'),
            message: 'Team retrieved successfully'
        );
    }


    /**
     * Update team details
     * 
     * Update the details of an existing team.
     */
    public function update(Request $request, Team $team)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:teams,name,' . $team->id,
            'description' => 'nullable|string',
        ]);

        try {
            $team->update($validatedData);

            return ApiResponse::success(
                data: $team,
                message: 'Team updated successfully'
            );
        } catch (\Exception $e) {
            Log::error('Error updating team.', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()?->id,
            ]);
            return ApiResponse::error(
                message: 'Failed to update team: ' . $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * Delete team
     * 
     * Permanently delete a team from the system.
     */
    public function destroy(Team $team)
    {
        try {
            $team->delete();

            return ApiResponse::success(
                message: 'Team deleted successfully'
            );
        } catch (\Exception $e) {
            Log::error('Error deleting team.', [
                'error' => $e->getMessage(),
                'requested_by' => request()->user()?->id,
            ]);
            return ApiResponse::error(
                message: 'Failed to delete team: ' . $e->getMessage(),
                statusCode: 500
            );
        }
    }
}
