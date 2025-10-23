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
use App\Services\DependencyValidator;
use App\Services\ProjectGraphCache;
use Illuminate\Database\Eloquent\Model;

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

    protected function createBasicRequiresRelation(int $projectId, Model $source, Model $target, int $createdById): ProjectRelation
    {
        return ProjectRelation::create([
            'project_id' => $projectId,
            'created_by_id' => $createdById,
            'source_type' => get_class($source),
            'source_id' => $source->id,
            'target_type' => get_class($target),
            'target_id' => $target->id,
            'relation_type' => ProjectRelationTypes::REQUIRES->value,
        ]);
    }

    protected function buildBasicRequiresGraph(): array
    {
        [$user, $project] = $this->getAuthenticatedUserAndProject();

        $milestone1 = Milestone::factory()->create(['project_id' => $project->id]);
        $task1 = Task::factory()->create(['project_id' => $project->id, 'assigned_by_id' => $user->id]);
        $task2 = Task::factory()->create(['project_id' => $project->id, 'assigned_by_id' => $user->id]);

        $this->createBasicRequiresRelation($project->id, $milestone1, $task1, $user->id);
        $this->createBasicRequiresRelation($project->id, $milestone1, $task2, $user->id);

        $graph = ProjectGraphCache::build($project->id);

        return [$graph, $project, $user, $milestone1, $task1, $task2];
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

    public function test_circular_dependency_detection(): void
    {
        [$graph, $project, $user, $milestone1, $task1, $task2] = $this->buildBasicRequiresGraph();

        // Check for circular dependency
        $hasCircularDependency = DependencyValidator::hasCircularDependency($graph, $task1, $milestone1);

        $this->assertTrue($hasCircularDependency, 'Circular dependency was not detected as expected.');
    }
}
