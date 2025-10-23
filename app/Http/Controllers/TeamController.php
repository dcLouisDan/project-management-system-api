<?php

namespace App\Http\Controllers;

use App\Enums\UserRoles;
use App\Events\TeamLeaderAssigned;
use App\Events\TeamLeaderDemoted;
use App\Events\TeamMemberAdded;
use App\Events\TeamMemberRemoved;
use App\Events\TeamMembersBulkAdded;
use App\Events\TeamMembersBulkRemoved;
use App\Events\TeamProjectAssigned;
use App\Events\TeamProjectRemoved;
use App\Http\Responses\ApiResponse;
use App\Models\Project;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

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
     * 
     * @response status=200 scenario="success" {"data": [{"id": 1, "name": "Development Team", "description": "Handles all development tasks"}], "links": {}, "meta": {}}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to retrieve teams: Database connection error", "errors": [], "meta": []}
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Team::class)) {
            return ApiResponse::error(
                message: 'Unauthorized to list teams',
                statusCode: 403
            );
        }

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
     * @bodyParam name string required Name of the Team. Must be unique. Example: Development Team
     * @bodyParam description string Description of the Team. Example: Team responsible for product development
     * 
     * @response status=201 scenario="success" {"data": {"id": 1, "name": "Development Team", "description": "Handles all development tasks", "created_at": "2024-01-01T12:00:00Z", "updated_at": "2024-01-01T12:00:00Z"}, "message": "Team created successfully"}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"name": ["The name has already been taken."]}}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to create team: Internal server error", "errors": [], "meta": []}
     */
    public function store(Request $request)
    {
        if ($request->user()->cannot('create', Team::class)) {
            return ApiResponse::error(
                message: 'Unauthorized to create team',
                statusCode: 403
            );
        }

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
     * 
     * Get details of a specific team by ID, including its members.
     * 
     * 
     * @apiResourceModel App\Models\Team
     * 
     * @response status=200 scenario="success" {"data": {"id": 1, "name": "Development Team", "description": "Handles all development tasks", "users": []}, "message": "Team retrieved successfully"}
     * @response status=404 scenario="not found" {"message": "Team not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to retrieve team", "errors": [], "meta": []}
     */
    public function show(Team $team)
    {
        if (request()->user()->cannot('view', $team)) {
            return ApiResponse::error(
                message: 'Unauthorized to view team',
                statusCode: 403
            );
        }

        return ApiResponse::success(
            data: $team->load('users'),
            message: 'Team retrieved successfully'
        );
    }


    /**
     * Update team details
     * 
     * Update the details of an existing team.
     * 
     * 
     * @bodyParam name string Name of the Team. Must be unique. Example: Updated Development Team
     * @bodyParam description string Description of the Team. Example: Updated team description
     * 
     * @response status=200 scenario="success" {"data": {"id": 1, "name": "Updated Development Team", "description": "Updated description", "created_at": "2024-01-01T12:00:00Z", "updated_at": "2024-01-02T12:00:00Z"}, "message": "Team updated successfully"}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"name": ["The name has already been taken."]}}
     * @response status=404 scenario="not found" {"message": "Team not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to update team: Internal server error", "errors": [], "meta": []}
     */
    public function update(Request $request, Team $team)
    {
        if ($request->user()->cannot('update', $team)) {
            return ApiResponse::error(
                message: 'Unauthorized to update team',
                statusCode: 403
            );
        }

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
     * 
     * 
     * @response status=200 scenario="success" {"data": null, "message": "Team deleted successfully"}
     * @response status=404 scenario="not found" {"message": "Team not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to delete team: Internal server error", "errors": [], "meta": []}
     */
    public function destroy(Team $team)
    {
        if (request()->user()->cannot('delete', $team)) {
            return ApiResponse::error(
                message: 'Unauthorized to delete team',
                statusCode: 403
            );
        }

        try {
            $team->forceDelete();

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

    /**
     * Add member to team
     * 
     * Add a single user to a team with a specified role.
     * 
     * 
     * @bodyParam user_id integer required The ID of the user to add. Example: 5
     * @bodyParam role string required The role to assign. Allowed values: team lead, team member. Example: team member
     * 
     * @response status=200 scenario="success" {"data": null, "message": "User added to team successfully"}
     * @response status=400 scenario="invalid argument" {"data": null, "message": "Failed to add user to team: Invalid user or role", "errors": [], "meta": []}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"user_id": ["The selected user id is invalid."]}}
     * @response status=404 scenario="not found" {"message": "Team not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to add user to team: Internal server error", "errors": [], "meta": []}
     */
    public function addMember(Request $request, Team $team)
    {
        if ($request->user()->cannot('addMember', $team)) {
            return ApiResponse::error(
                message: 'Unauthorized to add members to team',
                statusCode: 403
            );
        }
        $teamRoles = [UserRoles::TEAM_LEAD->value, UserRoles::TEAM_MEMBER->value];
        $validatedData = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role' => ['required', Rule::in($teamRoles)],
        ]);

        try {
            $team->users()->attach($validatedData['user_id'], ['role' => $validatedData['role']]);

            TeamMemberAdded::dispatch(
                $team,
                User::find($validatedData['user_id']),
                $validatedData['role'],
                $request->user()
            );

            return ApiResponse::success(
                message: 'User added to team successfully'
            );
        } catch (\InvalidArgumentException $e) {
            Log::error('Invalid argument.', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()?->id,
            ]);
            return ApiResponse::error(
                message: 'Failed to add user to team: ' . $e->getMessage(),
                statusCode: 400
            );
        } catch (\Exception $e) {
            Log::error('Error adding user to team.', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()?->id,
            ]);
            return ApiResponse::error(
                message: 'Failed to add user to team: ' . $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * Add multiple members to team
     * 
     * Add multiple users to a team with their specified roles in a single request.
     * 
     * 
     * @bodyParam members object[] required Array of members to add.
     * @bodyParam members[].user_id integer required The ID of the user to add. Example: 5
     * @bodyParam members[].role string required The role to assign. Allowed values: team lead, team member. Example: team member
     * 
     * @response status=200 scenario="success" {"data": {"invalid_users": []}, "message": "Users added to team successfully"}
     * @response status=200 scenario="success with invalid users" {"data": {"invalid_users": [3, 7]}, "message": "Users added to team successfully"}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"members.0.user_id": ["The selected members.0.user_id is invalid."]}}
     * @response status=404 scenario="not found" {"message": "Team not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to add users to team: Internal server error", "errors": [], "meta": []}
     */
    public function addMembers(Request $request, Team $team)
    {
        if ($request->user()->cannot('addMember', $team)) {
            return ApiResponse::error(
                message: 'Unauthorized to add members to team',
                statusCode: 403
            );
        }

        $teamRoles = [UserRoles::TEAM_LEAD->value, UserRoles::TEAM_MEMBER->value];
        $validatedData = $request->validate([
            'members' => ['required', 'array'],
            'members.*.user_id' => ['required', 'exists:users,id'],
            'members.*.role' => ['required', Rule::in($teamRoles)],
        ]);

        $usersWithRoles = [];
        foreach ($validatedData['members'] as $member) {
            $usersWithRoles[$member['user_id']] = $member['role'];
        }

        try {
            ['valid_users' => $validUsers, 'invalid_users' => $invalidUsers] = $team->addMembers($usersWithRoles);

            TeamMembersBulkAdded::dispatch(
                $team,
                $validUsers,
                $request->user()
            );

            return ApiResponse::success(
                message: 'Users added to team successfully',
                data: ['invalid_users' => $invalidUsers]
            );
        } catch (\Exception $e) {
            Log::error('Error adding users to team.', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()?->id,
            ]);
            return ApiResponse::error(
                message: 'Failed to add users to team: ' . $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * Set team leader
     * 
     * Set a user as the team leader. If there's an existing leader, they will be demoted to team member.
     * 
     * 
     * @bodyParam user_id integer required The ID of the user to set as leader. Example: 5
     * 
     * @response status=200 scenario="success" {"data": {"demoted_lead": {"id": 3, "name": "Previous Leader"}}, "message": "Team leader set successfully"}
     * @response status=200 scenario="success - no previous leader" {"data": {"demoted_lead": null}, "message": "Team leader set successfully"}
     * @response status=200 scenario="user already leader" {"data": null, "message": "User is already the team lead"}
     * @response status=400 scenario="invalid argument" {"data": null, "message": "Failed to set team leader: User does not have the team lead role", "errors": [], "meta": []}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"user_id": ["The selected user id is invalid."]}}
     * @response status=404 scenario="not found" {"message": "Team not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to set team leader: Internal server error", "errors": [], "meta": []}
     */
    public function setLeader(Request $request, Team $team)
    {
        if ($request->user()->cannot('assignRoles', $team)) {
            return ApiResponse::error(
                message: 'Unauthorized to set team leader',
                statusCode: 403
            );
        }
        $validatedData = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $currentLead = $team->lead();
        $demotedLead = null;
        if ($currentLead && $currentLead->id == $validatedData['user_id']) {
            return ApiResponse::success(
                message: 'User is already the team lead'
            );
        }


        try {
            DB::beginTransaction();

            if ($currentLead !== null) {
                // Demote current lead to member
                $team->demoteLeader();
                $demotedLead = $currentLead;

                TeamLeaderDemoted::dispatch(
                    $team,
                    $demotedLead,
                    $request->user()
                );
            }

            $user = User::findOrFail($validatedData['user_id']);
            $team->setLeader($user);

            TeamLeaderAssigned::dispatch(
                $team,
                $demotedLead,
                $user,
                $request->user()
            );

            DB::commit();

            return ApiResponse::success(
                data: ['demoted_lead' => $demotedLead],
                message: 'Team leader set successfully'
            );
        } catch (\InvalidArgumentException $e) {
            DB::rollBack();
            Log::error('Invalid argument.', [
                'error' => 'User does not have the team lead role.',
                'requested_by' => $request->user()?->id,
            ]);
            return ApiResponse::error(
                message: 'Failed to set team leader: ' . $e->getMessage(),
                statusCode: 400
            );
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error setting team leader.', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()?->id,
            ]);
            return ApiResponse::error(
                message: 'Failed to set team leader: ' . $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * Remove member from team
     * 
     * Remove a single user from a team.
     * 
     * 
     * @bodyParam user_id integer required The ID of the user to remove. Example: 5
     * 
     * @response status=200 scenario="success" {"data": null, "message": "User removed from team successfully"}
     * @response status=400 scenario="invalid argument" {"data": null, "message": "Failed to remove user from team: User not in team", "errors": [], "meta": []}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"user_id": ["The selected user id is invalid."]}}
     * @response status=404 scenario="not found" {"message": "Team not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to remove user from team: Internal server error", "errors": [], "meta": []}
     */
    public function removeMember(Request $request, Team $team, User $user)
    {
        if ($request->user()->cannot('removeMember', $team)) {
            return ApiResponse::error(
                message: 'Unauthorized to remove members from team',
                statusCode: 403
            );
        }

        try {
            $team->users()->detach($user->id);

            TeamMemberRemoved::dispatch(
                $team,
                $user,
                $request->user()
            );

            return ApiResponse::success(
                message: 'User removed from team successfully'
            );
        } catch (\InvalidArgumentException $e) {
            Log::error('Invalid argument.', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()?->id,
            ]);
            return ApiResponse::error(
                message: 'Failed to remove user from team: ' . $e->getMessage(),
                statusCode: 400
            );
        } catch (\Exception $e) {
            Log::error('Error removing user from team.', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()?->id,
            ]);
            return ApiResponse::error(
                message: 'Failed to remove user from team: ' . $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * Remove multiple members from team
     * 
     * Remove multiple users from a team in a single request.
     * 
     * 
     * @bodyParam user_ids integer[] required Array of user IDs to remove from the team. Example: [5, 8, 12]
     * 
     * @response status=200 scenario="success" {"data": null, "message": "Users removed from team successfully"}
     * @response status=400 scenario="invalid argument" {"data": null, "message": "Failed to remove users from team: Invalid user IDs", "errors": [], "meta": []}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"user_ids.0": ["The selected user_ids.0 is invalid."]}}
     * @response status=404 scenario="not found" {"message": "Team not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to remove users from team: Internal server error", "errors": [], "meta": []}
     */
    public function removeMembers(Request $request, Team $team)
    {
        if ($request->user()->cannot('removeMember', $team)) {
            return ApiResponse::error(
                message: 'Unauthorized to remove members from team',
                statusCode: 403
            );
        }

        $validatedData = $request->validate([
            'user_ids' => ['required', 'array'],
            'user_ids.*' => ['exists:users,id'],
        ]);

        try {
            $team->removeMembers($validatedData['user_ids']);

            TeamMembersBulkRemoved::dispatch(
                $team,
                $validatedData['user_ids'],
                $request->user()
            );

            return ApiResponse::success(
                message: 'Users removed from team successfully'
            );
        } catch (\InvalidArgumentException $e) {
            Log::error('Invalid argument.', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()?->id,
            ]);
            return ApiResponse::error(
                message: 'Failed to remove users from team: ' . $e->getMessage(),
                statusCode: 400
            );
        } catch (\Exception $e) {
            Log::error('Error removing users from team.', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()?->id,
            ]);
            return ApiResponse::error(
                message: 'Failed to remove users from team: ' . $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * Assign project to team
     * 
     * Assign a project to the team to work on.
     * 
     * @bodyParam project_id integer required The ID of the project to assign. Example: 3
     * 
     * @response status=200 scenario="success" {"data": null, "message": "Project assigned to team successfully"}
     * @response status=400 scenario="invalid argument" {"data": null, "message": "Failed to assign project to team: Project already assigned to team", "errors": [], "meta": []}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"project_id": ["The selected project id is invalid."]}}
     * @response status=404 scenario="not found" {"message": "Team not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to assign project to team: Internal server error", "errors": [], "meta": []}
     */
    public function assignProject(Request $request, Team $team)
    {
        if ($request->user()->cannot('assignProject', $team)) {
            return ApiResponse::error(
                message: 'Unauthorized to assign project to team',
                statusCode: 403
            );
        }

        $validatedData = $request->validate([
            'project_id' => ['required', 'exists:projects,id'],
        ]);

        try {
            if ($team->worksOnProject($validatedData['project_id'])) {
                throw new \InvalidArgumentException("Project already assigned to team.");
            }
            $project = Project::findOrFail($validatedData['project_id']);
            $team->assignProject($project);

            TeamProjectAssigned::dispatch($team, $project, $request->user());

            return ApiResponse::success(
                message: 'Project assigned to team successfully'
            );
        } catch (\InvalidArgumentException $e) {
            Log::error('Invalid argument.', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()?->id,
            ]);
            return ApiResponse::error(
                message: 'Failed to assign project to team: ' . $e->getMessage(),
                statusCode: 400
            );
        } catch (\Exception $e) {
            Log::error('Error assigning project to team.', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()?->id,
            ]);
            return ApiResponse::error(
                message: 'Failed to assign project to team: ' . $e->getMessage(),
                statusCode: 500
            );
        }
    }

    /**
     * Remove Project from team
     * 
     * Remove a project assignment from the team.
     * 
     * @bodyParam project_id integer required The ID of the project to remove. Example: 3
     * 
     * @response status=200 scenario="success" {"data": null, "message": "Project removed from team successfully"}
     * @response status=400 scenario="invalid argument" {"data": null, "message": "Failed to remove project from team: Project not found", "errors": [], "meta": []}
     * @response status=404 scenario="not found" {"message": "Team not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to remove project from team: Internal server error", "errors": [], "meta": []}
     */
    public function removeProject(Request $request, Team $team, Project $project)
    {
        if ($request->user()->cannot('removeProject', $team)) {
            return ApiResponse::error(
                message: 'Unauthorized to remove project from team',
                statusCode: 403
            );
        }
        try {
            $team->removeProject($project);

            TeamProjectRemoved::dispatch($team, $project, $request->user());

            return ApiResponse::success(
                message: 'Project removed from team successfully'
            );
        } catch (\InvalidArgumentException $e) {
            Log::error('Invalid argument.', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()?->id,
            ]);
            return ApiResponse::error(
                message: 'Failed to remove project from team: ' . $e->getMessage(),
                statusCode: 400
            );
        } catch (\Exception $e) {
            Log::error('Error removing project from team.', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()?->id,
            ]);
            return ApiResponse::error(
                message: 'Failed to remove project from team: ' . $e->getMessage(),
                statusCode: 500
            );
        }
    }
}
