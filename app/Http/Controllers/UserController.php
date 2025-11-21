<?php

namespace App\Http\Controllers;

use App\Enums\UserRoles;
use App\Http\Resources\UserResource;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

/**
 * @group User Management
 *
 * APIs for managing users
 */
class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * List Users
     *
     * Get a paginated list of all users with optional filtering.
     *
     * @queryParam per_page integer Number of users per page. Defaults to 10. Example: 15
     * @queryParam name string Filter users by name (partial match). Example: John
     * @queryParam email string Filter users by email (partial match). Example: john@example.com
     * @queryParam role string Filter users by role. Example: admin
     * @queryParam roles string Filter users by multiple roles separated by commas. Example: admin,team lead
     *
     * @apiResourceCollection App\Http\Resources\UserResource
     *
     * @apiResourceModel App\Models\User paginate=10
     *
     * @response status=200 scenario="success" {"data": [{"id": 1, "name": "John Doe", "email": "john@example.com"}], "links": {}, "meta": {}}
     * @response status=403 scenario="forbidden" {"message": "This action is unauthorized."}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"per_page": ["The per page must be an integer."]}}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to retrieve user list", "errors": [], "meta": []}
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', User::class)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        $perPage = $request->input('per_page', 10);
        $query = $this->userService->buildFilteredUserQuery($request->all());

        try {
            return UserResource::collection($query->paginate($perPage));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve user list', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()->id,
            ]);

            return ApiResponse::error('Failed to retrieve user list', 500);
        }
    }

    /**
     * Get user
     *
     * Get details of a specific user by ID.
     *
     * @urlParam id integer required The ID of the user. Example: 1
     *
     * @apiResource App\Http\Resources\UserResource
     *
     * @apiResourceModel App\Models\User
     *
     * @response status=200 scenario="success" {"data": {"id": 1, "name": "John Doe", "email": "john@example.com", "roles": ["admin"]}, "message": "User retrieved successfully"}
     * @response status=404 scenario="not found" {"message": "User not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to retrieve user", "errors": [], "meta": []}
     */
    public function show(Request $request, int $user)
    {
        $id = $user;
        $user = User::withTrashed()->find($id);
        if ($request->user()->cannot('view', $user)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }
        try {
            return new UserResource($user->load('roles', 'teams'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve user', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()->id,
            ]);

            return ApiResponse::error('Failed to retrieve user', 500);
        }
    }

    /**
     * Create user
     *
     * Create a new user with assigned roles.
     *
     * @bodyParam name string required The name of the user. Example: John Doe
     * @bodyParam email string required The email address of the user. Must be unique. Example: john.doe@example.com
     * @bodyParam password string required The password for the user account. Must be at least 8 characters. Example: SecurePass123!
     * @bodyParam password_confirmation string required Password confirmation. Must match password field. Example: SecurePass123!
     * @bodyParam roles string[] required Array of roles to assign to the user. Allowed values: admin, project manager, team lead, team member. Example: ["admin", "project manager"]
     *
     * @response status=201 scenario="success" {"data": {"id": 1, "name": "John Doe", "email": "john.doe@example.com", "roles": ["admin"]}, "message": "User created successfully"}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"email": ["The email has already been taken."]}}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to create user", "errors": [], "meta": []}
     */
    public function store(Request $request)
    {
        if ($request->user()->cannot('create', User::class)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }
        // Scribe will automatically extract parameters from this validation
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['required', 'array', 'min:1', 'max:4'],
            'roles.*' => ['required', Rule::in(UserRoles::allRoles())],
        ]);

        try {
            $user = $this->userService->createUser([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => $validatedData['password'],
                'roles' => $validatedData['roles'],
            ]);

            return ApiResponse::success(
                new UserResource($user->fresh()->load('roles')),
                'User created successfully',
                201
            );
        } catch (\Exception $e) {

            Log::error('Failed to create user', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()->id,
            ]);

            return ApiResponse::error('Failed to create user', 500);
        }
    }

    /**
     * Update user
     *
     * Update an existing user's information.
     *
     *
     * @bodyParam name string required The name of the user. Example: John Doe Updated
     * @bodyParam email string required The email address of the user. Example: john.updated@example.com
     * @bodyParam password string The new password (optional). Must be at least 8 characters if provided. Example: NewSecurePass123!
     * @bodyParam password_confirmation string Password confirmation (required if password is provided). Example: NewSecurePass123!
     *
     * @response status=200 scenario="success" {"data": {"id": 1, "name": "John Doe Updated", "email": "john.updated@example.com", "roles": ["admin"]}, "message": "User updated successfully"}
     * @response status=422 scenario="validation error" {"message": "The given data was invalid.", "errors": {"email": ["The email has already been taken."]}}
     * @response status=404 scenario="not found" {"message": "User not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to update user", "errors": [], "meta": []}
     */
    public function update(Request $request, User $user)
    {
        if ($request->user()->cannot('update', $user)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            $user = $this->userService->updateUser($user, $validatedData);

            return ApiResponse::success(
                new UserResource($user),
                'User updated successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to update user', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()->id,
            ]);

            return ApiResponse::error('Failed to update user', 500);
        }
    }

    /**
     * Soft delete user
     *
     * Soft delete a user from the system.
     *
     * @response status=200 scenario="success" {"data": null, "message": "User deleted successfully"}
     * @response status=404 scenario="not found" {"message": "User not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to delete user", "errors": [], "meta": []}
     */
    public function destroy(Request $request, User $user)
    {
        if ($request->user()->cannot('delete', $user)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        try {
            $this->userService->deleteUser($user);

            return ApiResponse::success(null, 'User deleted successfully');
        } catch (\Exception $e) {
            Log::error('Failed to delete user', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()->id,
            ]);

            return ApiResponse::error('Failed to delete user', 500);
        }
    }

    /**
     * Restore soft-deleted user
     *
     * Restore a previously deleted user.
     *
     *
     * @response status=200 scenario="success" {"data": {"id": 1, "name": "John Doe", "email": "john@example.com", "roles": ["admin"]}, "message": "User restored successfully"}
     * @response status=404 scenario="not found" {"message": "User not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to restore user", "errors": [], "meta": []}
     */
    public function restore(Request $request, int $userId)
    {
        $user = User::withTrashed()->find($userId);
        if (! $user) {
            return ApiResponse::error('User not found', 404);
        }

        if ($request->user()->cannot('restore', $user)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }

        try {
            $user = $this->userService->restoreUser($user);

            return ApiResponse::success(
                new UserResource($user),
                'User restored successfully'
            );
        } catch (\Exception $e) {
            Log::error('Failed to restore user', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()->id,
            ]);

            return ApiResponse::error('Failed to restore user', 500);
        }
    }

    /**
     * Assign user role
     *
     * Update the roles assigned to a specific user. Note: Cannot remove 'team lead' role if user is actively leading teams.
     *
     * @bodyParam roles string[] required Array of roles to assign to the user. Allowed values: admin, project manager, team lead, team member. Example: ["team lead", "project manager"]
     *
     * @response status=200 scenario="success" {"data": {"id": 1, "name": "John Doe", "email": "john@example.com", "roles": ["team lead", "project manager"]}, "message": "Roles assigned successfully"}
     * @response status=422 scenario="validation error - active team lead" {"success": false, "message": "Cannot remove team lead role: user is actively leading teams", "errors": {"roles": ["User must be removed as team lead from all teams before removing this role."], "active_teams": ["Backend Team", "DevOps Team"]}}
     * @response status=422 scenario="validation error - invalid roles" {"message": "The given data was invalid.", "errors": {"roles.0": ["The selected roles.0 is invalid."]}}
     * @response status=404 scenario="not found" {"message": "User not found"}
     * @response status=500 scenario="error" {"data": null, "message": "Failed to assign roles", "errors": [], "meta": []}
     */
    public function assignRoles(Request $request, User $user)
    {
        if ($request->user()->cannot('assignRole', $user)) {
            return ApiResponse::error('This action is unauthorized.', 403);
        }
        $validatedData = $request->validate([
            'roles' => ['required', 'array', 'min:1', 'max:4'],
            'roles.*' => ['required', Rule::in(UserRoles::allRoles())],
        ]);

        try {
            $this->userService->assignRoles($user, $validatedData['roles'], $request->user());

            return ApiResponse::success(
                new UserResource($user->fresh()->load('roles')),
                'Roles assigned successfully'
            );
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to assign roles to user', [
                'error' => $e->getMessage(),
                'requested_by' => $request->user()->id,
            ]);

            return ApiResponse::error('Failed to assign roles', 500);
        }
    }
}
