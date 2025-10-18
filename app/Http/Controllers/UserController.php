<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Enums\UserRoles;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    private function validationRules(?int $id = null, bool $isCreating = false): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ];

        if ($id) {
            $rules['email'][] = 'unique:users,email,' . $id;
        } else {
            $rules['email'][] = 'unique:users';
        }

        if ($isCreating) {
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
            $rules['roles'] = ['required', 'array', 'min:1', 'max:4'];
            $rules['roles.*'] = ['required', Rule::in(UserRoles::allRoles())];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:8', 'confirmed'];
        }

        return $rules;
    }

    private function buildFilteredUserQuery(Request $request)
    {
        $query = User::query();

        // Apply filters based on request parameters
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }

        if ($request->has('role')) {
            $role = $request->input('role');
            $query->role($role);
        }
        return $query;
    }

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = $this->buildFilteredUserQuery($request);

        try {
            $users = UserResource::collection($query->paginate(10));
            Log::info('Retrieved user list', ['count' => $users->count(), 'requested_by' => $request->user()->id]);
            return $users;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve user list', ['error' => $e->getMessage(), 'requested_by' => $request->user()->id]);
            return new ApiResponse(null, 'Failed to retrieve user list: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified user.
     */
    public function show(Request $request, User $user)
    {
        try {
            Log::info('Retrieved user', ['user_id' => $user->id, 'requested_by' => $request->user()->id]);
            return new ApiResponse(
                new UserResource($user),
                'User retrieved successfully',
                200
            );
        } catch (\Exception $e) {
            Log::error('Failed to retrieve user', ['error' => $e->getMessage(), 'requested_by' => $request->user()->id]);
            return new ApiResponse(null, 'Failed to retrieve user: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        Log::info('Creating user', ['requested_by' => $request->user()->id, 'input_data' => $request->input()->all()]);
        $validatedData = $request->validate($this->validationRules(null, true));

        try {

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
            ]);

            $user->syncRoles($validatedData['roles']);
            Log::info('User created successfully', ['user_id' => $user->id, 'requested_by' => $request->user()->id]);
            return new ApiResponse($user, 'User created successfully', 201);
        } catch (\Exception $e) {
            Log::error('Failed to create user', ['error' => $e->getMessage(), 'requested_by' => $request->user()->id]);
            return new ApiResponse(null, 'Failed to create user: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        Log::info('Updating user', ['user_id' => $user->id, 'requested_by' => $request->user()->id, 'input_data' => $request->input()->all()]);
        $validatedData = $request->validate($this->validationRules($user->id, false));

        try {
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];

            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }

            $user->save();

            Log::info('User updated successfully', ['user_id' => $user->id, 'requested_by' => $request->user()->id]);
            return new ApiResponse($user, 'User updated successfully', 200);
        } catch (\Exception $e) {
            Log::error('Failed to update user', ['error' => $e->getMessage(), 'requested_by' => $request->user()->id]);
            return new ApiResponse(null, 'Failed to update user: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Request $request, User $user)
    {
        try {
            $user->delete();
            Log::info('User deleted successfully', ['user_id' => $user->id, 'requested_by' => $request->user()->id]);
            return new ApiResponse(null, 'User deleted successfully', 200);
        } catch (\Exception $e) {
            Log::error('Failed to delete user', ['error' => $e->getMessage(), 'requested_by' => $request->user()->id]);
            return new ApiResponse(null, 'Failed to delete user: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Restore the specified soft-deleted user.
     */
    public function restore(Request $request, User $user)
    {
        try {
            $user->restore();
            Log::info('User restored successfully', ['user_id' => $user->id, 'requested_by' => $request->user()->id]);
            return new ApiResponse($user, 'User restored successfully', 200);
        } catch (\Exception $e) {
            Log::error('Failed to restore user', ['error' => $e->getMessage(), 'requested_by' => $request->user()->id]);
            return new ApiResponse(null, 'Failed to restore user: ' . $e->getMessage(), 500);
        }
    }
}
