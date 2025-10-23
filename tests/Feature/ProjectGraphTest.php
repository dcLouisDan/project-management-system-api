<?php

namespace Tests\Feature;

use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Enums\UserRoles;
use App\Enums\ProjectRelationTypes;
use App\Models\Milestone;
use App\Models\ProjectRelation;
use App\Models\Task;

class ProjectGraphTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function getAuthenticatedUserAndProject()
    {
        // Seed roles and permissions
        $this->seed(RolesAndPermissionsSeeder::class);

        // Create a user and authenticate
        $user = \App\Models\User::factory()->create();
        $user->assignRole(UserRoles::ADMIN->value);
        $this->actingAs($user);

        // Create a project and assign the user as a member
        $project = \App\Models\Project::factory()->create();

        return [$user, $project];
    }

    public function test_can_create_project_relation(): void
    {
        [$user, $project] = $this->getAuthenticatedUserAndProject();

        $sourceMilestone = Milestone::factory()->create(['project_id' => $project->id]);
        $targetTask = Task::factory()->create(['project_id' => $project->id, 'assigned_by_id' => $user->id]);
        $relationData = [
            'project_id' => $project->id,
            'created_by_id' => $user->id,
            'source_type' => Milestone::class,
            'source_id' => $sourceMilestone->id,
            'target_type' => Task::class,
            'target_id' => $targetTask->id,
            'relation_type' => ProjectRelationTypes::PARENT_OF->value,
        ];

        ProjectRelation::create($relationData);

        $this->assertDatabaseHas('project_relations', $relationData);
    }
}
