<?php

namespace Tests\Feature;

use App\Enums\UserRoles;
use App\Models\Project;
use App\Models\User;
use App\Services\ProjectService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectModelTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected ProjectService $projectService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->projectService = new ProjectService;
    }

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
        $user = $this->getAuthenticatedUser();

        $manager = User::factory()->create();
        $manager->assignRole(UserRoles::PROJECT_MANAGER->value);
        $project = Project::factory()->create();

        $this->projectService->assignManager($project, $manager->id, $user);
        $this->assertEquals($manager->id, $project->manager->id);
    }

    public function test_non_project_manager_cannot_be_assigned_as_manager(): void
    {
        $user = $this->getAuthenticatedUser();

        $nonManager = User::factory()->create();
        $nonManager->assignRole(UserRoles::TEAM_MEMBER->value);
        $project = Project::factory()->create();

        $this->expectException(\InvalidArgumentException::class);
        $this->projectService->assignManager($project, $nonManager->id, $user);
    }

    public function test_team_can_be_associated_with_project(): void
    {
        $user = $this->getAuthenticatedUser();

        $project = Project::factory()->create();
        $team = \App\Models\Team::factory()->create();
        $teamsToAdd = [$team->id => ['notes' => 'Assigned for development']];

        $invalidTeams = $this->projectService->assignTeams($project, $teamsToAdd, $user);
        $this->assertEmpty($invalidTeams);
        $this->assertCount(1, $project->teams);
        $this->assertTrue($project->hasTeam($team));
    }

    public function test_team_can_removed_from_project(): void
    {
        $user = $this->getAuthenticatedUser();

        $project = Project::factory()->create();
        $team = \App\Models\Team::factory()->create();
        $teamsToAdd = [$team->id => ['notes' => 'Assigned for development']];

        $invalidTeams = $this->projectService->assignTeams($project, $teamsToAdd, $user);
        $this->assertTrue($project->hasTeam($team));

        $invalidTeams = $this->projectService->removeTeams($project, [$team->id], $user);
        $this->assertFalse($project->hasTeam($team));
    }

    public function test_project_can_be_restored(): void
    {
        $projectName = 'New Project';
        $projectDescription = 'Project Description';
        $project = Project::factory()->create([
            'name' => $projectName,
            'description' => $projectDescription,
        ]);

        $project->delete();
        $project->refresh();
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'name' => $project->name,
            'description' => $project->description,
        ]);
        $this->assertNotNull($project->deleted_at);

        $project->restore();
        $project->refresh();

        $this->assertNull($project->deleted_at);
    }
}
