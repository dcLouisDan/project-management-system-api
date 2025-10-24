<?php

namespace Tests\Feature;

use App\Enums\UserRoles;
use App\Models\Project;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectModelTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function getAuthenticatedUser()
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $user = User::factory()->create();
        $user->assignRole(UserRoles::ADMIN->value);
        $this->actingAs($user);
        return $user;
    }

    public function test_project_can_be_created(): void
    {
        $this->getAuthenticatedUser();

        $projectName = 'New Project';
        $projectDescription = 'Project Description';
        Project::factory()->create([
            'name' => $projectName,
            'description' => $projectDescription,
        ]);

        $this->assertDatabaseHas('projects', [
            'name' => $projectName,
            'description' => $projectDescription,
        ]);
    }

    public function test_project_manager_can_be_assigned(): void
    {
        $this->getAuthenticatedUser();

        $manager = User::factory()->create();
        $manager->assignRole(UserRoles::PROJECT_MANAGER->value);
        $project = Project::factory()->create();

        $project->setManager($manager);

        $this->assertEquals($manager->id, $project->manager->id);
    }

    public function test_non_project_manager_cannot_be_assigned_as_manager(): void
    {
        $this->getAuthenticatedUser();

        $nonManager = User::factory()->create();
        $nonManager->assignRole(UserRoles::TEAM_MEMBER->value);
        $project = Project::factory()->create();

        $this->expectException(\InvalidArgumentException::class);
        $project->setManager($nonManager);
    }

    public function test_team_can_be_associated_with_project(): void
    {
        $this->getAuthenticatedUser();

        $project = Project::factory()->create();
        $team = \App\Models\Team::factory()->create();
        $teamsToAdd = [$team->id => ['notes' => 'Assigned for development']];

        $invalidTeams = $project->assignTeams($teamsToAdd);
        $this->assertEmpty($invalidTeams);
        $this->assertCount(1, $project->teams);
        $this->assertTrue($project->hasTeam($team));
    }

    public function test_team_can_removed_from_project(): void
    {
        $this->getAuthenticatedUser();

        $project = Project::factory()->create();
        $team = \App\Models\Team::factory()->create();
        $teamsToAdd = [$team->id => ['notes' => 'Assigned for development']];

        $project->assignTeams($teamsToAdd);
        $this->assertTrue($project->hasTeam($team));

        $project->teams()->detach($team->id);
        $this->assertFalse($project->hasTeam($team));
    }
}
