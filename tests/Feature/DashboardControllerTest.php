<?php

namespace Tests\Feature;

use App\Enums\UserRoles;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
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

    public function test_dashboard_stats_returns_stats(): void
    {
        $user = $this->createAndAuthenticateUser();

        $response = $this->getJson('/dashboard/stats');

        $response->assertStatus(200);
    }

    public function test_dashboard_recent_projects_returns_recent_projects(): void
    {
        $user = $this->createAndAuthenticateUser();

        $response = $this->getJson('/dashboard/recent-projects');

        $response->assertStatus(200);
    }

    public function test_dashboard_recent_tasks_returns_recent_tasks(): void
    {
        $user = $this->createAndAuthenticateUser();

        $response = $this->getJson('/dashboard/recent-tasks');

        $response->assertStatus(200);
    }
}
