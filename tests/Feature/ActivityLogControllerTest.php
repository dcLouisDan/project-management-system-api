<?php

namespace Tests\Feature;

use App\Enums\UserRoles;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActivityLogControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private string $apiPrefix = '/activity-logs';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    protected function createAndAuthenticateUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole(UserRoles::ADMIN->value);
        $this->actingAs($user, 'web');

        return $user;
    }

    public function test_activity_log_index_route_requires_authentication(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        $response = $this->getJson($this->apiPrefix);

        $response->assertStatus(403); // Unauthorized
    }

    public function test_activity_log_index_route_returns_paginated_activity_logs(): void
    {
        $user = $this->createAndAuthenticateUser();

        $response = $this->getJson($this->apiPrefix);

        $response->assertStatus(200);
    }
}
