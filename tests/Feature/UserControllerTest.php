<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private string $apiPrefix = '/users';

    public function test_user_index_route_requires_authentication(): void
    {
        $response = $this->getJson($this->apiPrefix);

        $response->assertStatus(403); // Unauthorized
    }

    public function test_user_index_route_returns_paginated_users(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->givePermissionTo('list users');
        $this->actingAs($user, 'web');

        // Create some users
        User::factory()->count(15)->create();

        $response = $this->getJson($this->apiPrefix);

        $response->assertStatus(200);
    }

    public function test_user_view_route_requires_user_permission(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        $otherUser = User::factory()->create();

        $response = $this->getJson("{$this->apiPrefix}/{$otherUser->id}");

        $response->assertStatus(403); // Forbidden
    }

    public function test_user_with_permission_can_view_user(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->givePermissionTo('view user');
        $this->actingAs($user, 'web');

        $otherUser = User::factory()->create();

        $response = $this->getJson("{$this->apiPrefix}/{$otherUser->id}");

        $response->assertStatus(200);
    }

    public function test_user_create_route_requires_create_user_permission(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        $response = $this->postJson($this->apiPrefix, [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password',
        ]);

        $response->assertStatus(403); // Forbidden
    }

    public function test_user_with_permission_can_create_user(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->givePermissionTo('create user');
        $this->actingAs($user, 'web');

        $response = $this->postJson($this->apiPrefix, [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'roles' => ['admin'],
        ]);

        $response->assertStatus(201); // Created
        $this->assertDatabaseHas('users', [
            'email' => $response->json('data.email'),
        ]);
    }

    public function test_user_update_route_requires_update_user_permission(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        $response = $this->putJson("{$this->apiPrefix}/{$user->id}", [
            'name' => 'Updated Name',
        ]);

        $response->assertStatus(403); // Forbidden
    }

    public function test_user_with_permission_can_update_user(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->givePermissionTo('update user');
        $this->actingAs($user, 'web');

        $response = $this->putJson("{$this->apiPrefix}/{$user->id}", [
            'name' => 'Updated Name',
            'email' => 'updated.email@example.com',
        ]);

        $response->assertStatus(200); // OK
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
        ]);
    }

    public function test_user_delete_route_requires_delete_user_permission(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        $response = $this->deleteJson("{$this->apiPrefix}/{$user->id}");

        $response->assertStatus(403); // Forbidden
    }

    public function test_user_with_permission_can_delete_user(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->givePermissionTo('delete user');
        $this->actingAs($user, 'web');

        $response = $this->deleteJson("{$this->apiPrefix}/{$user->id}");

        $response->assertStatus(200); // OK
        $this->assertSoftDeleted('users', [
            'id' => $user->id,
        ]);
    }

    public function test_user_restore_route_requires_restore_user_permission(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $userToRestore = User::factory()->create();
        $userToRestore->delete();
        $this->actingAs($user, 'web');

        $response = $this->postJson("{$this->apiPrefix}/{$userToRestore->id}/restore");

        $response->assertStatus(403); // Forbidden
    }

    public function test_user_with_permission_can_restore_user(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->delete();
        $adminUser = User::factory()->create();
        $adminUser->givePermissionTo('restore user');
        $this->actingAs($adminUser, 'web');

        $response = $this->postJson("{$this->apiPrefix}/{$user->id}/restore");

        $response->assertStatus(200); // OK
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => null,
        ]);
    }

    public function test_user_assign_roles_route_requires_assign_role_user_permission(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $userToAssign = User::factory()->create();
        $this->actingAs($user, 'web');

        $response = $this->postJson("{$this->apiPrefix}/{$userToAssign->id}/roles", [
            'roles' => ['admin'],
        ]);

        $response->assertStatus(403); // Forbidden
    }

    public function test_user_with_permission_can_assign_roles(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $userToAssign = User::factory()->create();
        $user->givePermissionTo('assign role user');
        $this->actingAs($user, 'web');

        $response = $this->postJson("{$this->apiPrefix}/{$userToAssign->id}/roles", [
            'roles' => ['admin'],
        ]);

        $response->assertStatus(200); // OK
        $this->assertTrue($userToAssign->fresh()->hasRole('admin'));
    }
}
