<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Spatie\Permission\Models\Role;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $userCount = User::count();
        if ($userCount > 0) {
            abort(403, 'User registration is disabled. Please contact your administrator.');
        }

        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            abort(405, 'Admin role does not exist. Please create it first.');
        }

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);


            $user->assignRole($adminRole);
            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            abort(500, 'Failed to create user: ' . $e->getMessage());
        }
    }
}
