<?php

namespace Tests\Feature;

use App\Enums\UserRoles;
use App\Models\Team;
use App\Models\User;
use App\Services\TeamService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected TeamService $teamService;

    private string $apiPrefix = '/teams';

    protected function setUp(): void
    {
        parent::setUp();
        $this->teamService = new TeamService;
    }

    protected function createAndAuthenticateUser(): \App\Models\User
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->assignRole(UserRoles::ADMIN->value);
        $this->actingAs($user, 'web');

        return $user;
    }

    public function test_team_index_route_requires_authentication(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');
        $response = $this->getJson($this->apiPrefix);

        $response->assertStatus(403); // Unauthorized
    }

    public function test_team_index_route_returns_paginated_teams(): void
    {
        $user = $this->createAndAuthenticateUser();

        // Create some teams
        Team::factory()->count(15)->create();
        $this->actingAs($user, 'web');

        $response = $this->getJson($this->apiPrefix);

        $response->assertStatus(200);
    }

    public function test_team_view_route_requires_user_permission(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        $team = Team::factory()->create();

        $response = $this->getJson("{$this->apiPrefix}/{$team->id}");

        $response->assertStatus(403); // Forbidden
    }

    public function test_team_view_route_returns_team_details(): void
    {
        $user = $this->createAndAuthenticateUser();

        $team = Team::factory()->create();
        $member1 = User::factory()->create();
        $member1->assignRole(UserRoles::TEAM_MEMBER->value);

        $member2 = User::factory()->create();
        $member2->assignRole(UserRoles::TEAM_MEMBER->value);

        $this->teamService->addMembers($team, [$member1->id => UserRoles::TEAM_MEMBER->value, $member2->id => UserRoles::TEAM_MEMBER->value], $member1);
        $lead = User::factory()->create();
        $lead->assignRole(UserRoles::TEAM_LEAD->value);
        $this->teamService->setLeader($team, $lead->id, $lead);
        $this->actingAs($user, 'web');
        $team->refresh();
        $this->debug('Team users', $team->users()->get());

        $response = $this->getJson("{$this->apiPrefix}/{$team->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $team->id,
                'name' => $team->name,
                'description' => $team->description,
            ]);
    }

    public function test_team_create_route_requires_user_permission(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        $response = $this->postJson($this->apiPrefix, [
            'name' => 'New Team',
            'description' => 'Team Description',
        ]);

        $response->assertStatus(403); // Forbidden
    }

    public function test_user_with_permission_can_create_team(): void
    {
        $user = $this->createAndAuthenticateUser();

        $response = $this->postJson($this->apiPrefix, [
            'name' => 'New Team',
            'description' => 'Team Description',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('teams', [
            'name' => 'New Team',
            'description' => 'Team Description',
        ]);
    }

    public function test_team_update_route_requires_user_permission(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        $team = Team::factory()->create();

        $response = $this->putJson("$this->apiPrefix/{$team->id}", [
            'name' => 'Updated Team Name',
            'description' => 'Updated Description',
        ]);

        $response->assertStatus(403); // Forbidden
    }

    public function test_user_with_permission_can_update_team(): void
    {
        $user = $this->createAndAuthenticateUser();
        $team = Team::factory()->create();

        $this->actingAs($user, 'web');

        $response = $this->putJson("{$this->apiPrefix}/{$team->id}", [
            'name' => 'Updated Team Name',
            'description' => 'Updated Description',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'name' => 'Updated Team Name',
            'description' => 'Updated Description',
        ]);
    }

    public function test_team_delete_route_requires_user_permission(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        $team = Team::factory()->create();

        $response = $this->deleteJson("{$this->apiPrefix}/{$team->id}");

        $response->assertStatus(403); // Forbidden
    }

    public function test_user_with_permission_can_delete_team(): void
    {
        $user = $this->createAndAuthenticateUser();
        $team = Team::factory()->create();

        $this->actingAs($user, 'web');

        $response = $this->deleteJson("{$this->apiPrefix}/{$team->id}");

        $response->assertStatus(200);
        $team->refresh();
        $this->assertNotNull($team->deleted_at);
    }

    public function test_team_restore_route_requires_user_permission(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'web');

        $team = Team::factory()->create();

        $response = $this->postJson("{$this->apiPrefix}/{$team->id}/restore");

        $response->assertStatus(403); // Forbidden
    }

    public function test_user_with_permission_can_restore_team(): void
    {
        $user = $this->createAndAuthenticateUser();
        $team = Team::factory()->create();

        $team->delete();
        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'name' => $team->name,
            'description' => $team->description,
        ]);

        $this->actingAs($user, 'web');

        $response = $this->postJson("{$this->apiPrefix}/{$team->id}/restore");

        $this->debugResponse($response);
        $response->assertStatus(200);
        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'name' => $team->name,
            'description' => $team->description,
        ]);

    }

    // public function test_add_member_routes_requires_permission(): void
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user, 'web');

    //     $team = Team::factory()->create();
    //     $member = User::factory()->create();

    //     $response = $this->postJson("{$this->apiPrefix}/{$team->id}/members", [
    //         'user_id' => $member->id,
    //         'role' => UserRoles::TEAM_MEMBER->value,
    //     ]);

    //     $response->assertStatus(403); // Forbidden

    //     $response = $this->postJson("{$this->apiPrefix}/{$team->id}/members/bulk", [
    //         'user_ids' => [$member->id],
    //         'role' => UserRoles::TEAM_MEMBER->value,
    //     ]);
    //     $response->assertStatus(403); // Forbidden
    // }

    // public function test_user_with_permission_can_add_member(): void
    // {
    //     $user = $this->createAndAuthenticateUser();
    //     $team = Team::factory()->create();
    //     $member = User::factory()->create();
    //     $member->assignRole(UserRoles::TEAM_MEMBER->value);

    //     $this->actingAs($user, 'web');

    //     $response = $this->postJson("{$this->apiPrefix}/{$team->id}/members", [
    //         'user_id' => $member->id,
    //         'role' => UserRoles::TEAM_MEMBER->value,
    //     ]);

    //     $response->assertStatus(200);
    //     $this->assertDatabaseHas('team_user', [
    //         'team_id' => $team->id,
    //         'user_id' => $member->id,
    //         'role' => UserRoles::TEAM_MEMBER->value,
    //     ]);
    // }

    // public function test_user_with_persmission_can_add_members_in_bulk(): void
    // {
    //     $user = $this->createAndAuthenticateUser();
    //     $team = Team::factory()->create();

    //     $member1 = User::factory()->create();
    //     $member1->assignRole(UserRoles::TEAM_MEMBER->value);

    //     $member2 = User::factory()->create();
    //     $member2->assignRole(UserRoles::TEAM_MEMBER->value);

    //     $this->actingAs($user, 'web');

    //     $response = $this->postJson("{$this->apiPrefix}/{$team->id}/members/bulk", [
    //         'members' => [
    //             ['user_id' => $member1->id, 'role' => UserRoles::TEAM_MEMBER->value],
    //             ['user_id' => $member2->id, 'role' => UserRoles::TEAM_MEMBER->value],
    //         ],
    //     ]);

    //     $response->assertStatus(200);
    //     $this->assertDatabaseHas('team_user', [
    //         'team_id' => $team->id,
    //         'user_id' => $member1->id,
    //         'role' => UserRoles::TEAM_MEMBER->value,
    //     ]);
    //     $this->assertDatabaseHas('team_user', [
    //         'team_id' => $team->id,
    //         'user_id' => $member2->id,
    //         'role' => UserRoles::TEAM_MEMBER->value,
    //     ]);
    // }

    // public function test_set_leader_route_requires_permission(): void
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user, 'web');

    //     $team = Team::factory()->create();
    //     $member = User::factory()->create();

    //     $response = $this->postJson("{$this->apiPrefix}/{$team->id}/lead", [
    //         'user_id' => $member->id,
    //     ]);

    //     $response->assertStatus(403); // Forbidden
    // }

    // public function test_user_with_permission_can_set_team_leader(): void
    // {
    //     $user = $this->createAndAuthenticateUser();
    //     $team = Team::factory()->create();
    //     $member = User::factory()->create();
    //     $member->assignRole(UserRoles::TEAM_LEAD->value);

    //     $this->actingAs($user, 'web');

    //     $response = $this->postJson("{$this->apiPrefix}/{$team->id}/lead", [
    //         'user_id' => $member->id,
    //     ]);

    //     $response->assertStatus(200);
    //     $this->assertDatabaseHas('team_user', [
    //         'team_id' => $team->id,
    //         'user_id' => $member->id,
    //         'role' => UserRoles::TEAM_LEAD->value,
    //     ]);
    // }

    // public function test_remove_team_member_route_requires_permission(): void
    // {
    //     $user = User::factory()->create();
    //     $team = Team::factory()->create();
    //     $member = User::factory()->create();

    //     $this->actingAs($user, 'web');

    //     $response = $this->deleteJson("{$this->apiPrefix}/{$team->id}/members/{$member->id}");

    //     $response->assertStatus(403); // Forbidden

    //     $response = $this->deleteJson("{$this->apiPrefix}/{$team->id}/members", [
    //         'user_ids' => [$member->id],
    //     ]);
    //     $response->assertStatus(403); // Forbidden
    // }

    // public function test_user_with_permission_can_remove_team_member(): void
    // {
    //     $user = $this->createAndAuthenticateUser();
    //     $team = Team::factory()->create();
    //     $member = User::factory()->create();
    //     $member->assignRole(UserRoles::TEAM_MEMBER->value);
    //     $this->teamService->addMember($team, $member, UserRoles::TEAM_MEMBER->value, $user);

    //     $this->actingAs($user, 'web');

    //     $response = $this->deleteJson("{$this->apiPrefix}/{$team->id}/members/{$member->id}");

    //     $response->assertStatus(200);
    //     $this->assertDatabaseMissing('team_user', [
    //         'team_id' => $team->id,
    //         'user_id' => $member->id,
    //     ]);
    // }

    // public function test_user_with_permission_can_remove_team_members_in_bulk(): void
    // {
    //     $user = $this->createAndAuthenticateUser();
    //     $team = Team::factory()->create();

    //     $member1 = User::factory()->create();
    //     $member1->assignRole(UserRoles::TEAM_MEMBER->value);

    //     $member2 = User::factory()->create();
    //     $member2->assignRole(UserRoles::TEAM_MEMBER->value);

    //     $this->teamService->addMembers($team, [
    //         $member1->id => UserRoles::TEAM_MEMBER->value,
    //         $member2->id => UserRoles::TEAM_MEMBER->value,
    //     ], $user);
    //     $this->actingAs($user, 'web');

    //     $response = $this->deleteJson("{$this->apiPrefix}/{$team->id}/members", [
    //         'user_ids' => [$member1->id, $member2->id],
    //     ]);

    //     $response->assertStatus(200);
    //     $this->assertDatabaseMissing('team_user', [
    //         'team_id' => $team->id,
    //         'user_id' => $member1->id,
    //     ]);
    //     $this->assertDatabaseMissing('team_user', [
    //         'team_id' => $team->id,
    //         'user_id' => $member2->id,
    //     ]);
    // }

    // public function test_assign_project_route_requires_permission(): void
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user, 'web');

    //     $team = Team::factory()->create();
    //     $project = \App\Models\Project::factory()->create();

    //     $response = $this->postJson("{$this->apiPrefix}/{$team->id}/projects", [
    //         'project_id' => $project->id,
    //     ]);

    //     $response->assertStatus(403); // Forbidden
    // }

    // public function test_user_with_permission_can_assign_project_to_team(): void
    // {
    //     $user = $this->createAndAuthenticateUser();
    //     $team = Team::factory()->create();
    //     $project = \App\Models\Project::factory()->create();

    //     $this->actingAs($user, 'web');

    //     $response = $this->postJson("{$this->apiPrefix}/{$team->id}/projects", [
    //         'project_id' => $project->id,
    //         'notes' => 'Project assigned to team for collaboration',
    //     ]);

    //     $response->assertStatus(200);
    //     $this->assertDatabaseHas('project_team', [
    //         'team_id' => $team->id,
    //         'project_id' => $project->id,
    //     ]);
    // }

    // public function test_remove_project_route_requires_permission(): void
    // {
    //     $user = User::factory()->create();
    //     $this->actingAs($user, 'web');

    //     $team = Team::factory()->create();
    //     $project = \App\Models\Project::factory()->create();
    //     $this->teamService->assignProject($team, $project, null, $user);

    //     $response = $this->deleteJson("{$this->apiPrefix}/{$team->id}/projects/{$project->id}");

    //     $response->assertStatus(403); // Forbidden
    // }

    // public function test_user_with_permission_can_remove_project_from_team(): void
    // {
    //     $user = $this->createAndAuthenticateUser();
    //     $team = Team::factory()->create();
    //     $project = \App\Models\Project::factory()->create();
    //     $this->teamService->assignProject($team, $project, null, $user);

    //     $this->actingAs($user, 'web');

    //     $response = $this->deleteJson("{$this->apiPrefix}/{$team->id}/projects/{$project->id}");
    //     $response->assertStatus(200);
    //     $this->assertDatabaseMissing('project_team', [
    //         'project_id' => $project->id,
    //     ]);
    // }
}
