<?php

namespace Tests\Feature;

use App\Enums\UserRoles;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_user_can_be_created(): void
    {
        $email = $this->faker->unique()->safeEmail();
        $name = $this->faker->name();

        User::factory()->create(['email' => $email, 'name' => $name]);

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'name' => $name,
        ]);
    }

    public function test_user_can_be_assigned_a_role(): void
    {
        $user = User::factory()->create();

        $user->assignRole(UserRoles::ADMIN->value);

        $this->assertTrue($user->hasRole(UserRoles::ADMIN->value));
    }

    public function test_user_can_have_multiple_roles(): void
    {
        $user = User::factory()->create();

        $user->assignRole(UserRoles::PROJECT_MANAGER->value);
        $user->assignRole(UserRoles::TEAM_LEAD->value);

        $this->assertTrue($user->hasRole(UserRoles::PROJECT_MANAGER->value));
        $this->assertTrue($user->hasRole(UserRoles::TEAM_LEAD->value));
    }

    public function test_user_inherits_role_permissions(): void
    {
        $user = User::factory()->create();

        $user->assignRole(UserRoles::ADMIN->value);

        $this->assertTrue($user->can('create user'));
        $this->assertTrue($user->can('create project'));
    }

    public function test_deleted_users_can_be_fetched(): void
    {
        User::factory()->count(10)->create();

        $userToDelete = User::orderBy('created_at', 'desc')->first();

        $userToDelete->delete();

        $deletedUsers = User::onlyTrashed()->get();
        $this->assertCount(1, $deletedUsers);
        $this->assertTrue($deletedUsers->first()->id === $userToDelete->id);
    }
}
