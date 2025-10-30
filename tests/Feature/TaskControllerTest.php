<?php

namespace Tests\Feature;

use App\Enums\UserRoles;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private string $apiPrefix = '/api/v1/tasks';

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    /**
     * Helper to act as an admin (has all permissions), so we focus on core behavior.
     */
    private function actingAsAdmin(): User
    {
        $admin = User::factory()->create();
        $admin->assignRole(UserRoles::ADMIN->value);
        $this->actingAs($admin);

        return $admin;
    }

    public function test_index_returns_paginated_tasks(): void
    {
        $this->actingAsAdmin();

        $project = Project::factory()->create();
        Task::factory()->count(5)->create(['project_id' => $project->id]);

        $response = $this->getJson($this->apiPrefix);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);
    }

    public function test_show_returns_task_details(): void
    {
        $this->actingAsAdmin();

        $task = Task::factory()->create();

        $response = $this->getJson("{$this->apiPrefix}/{$task->id}");

        $response->assertStatus(200)
            ->assertJsonPath('data.id', $task->id);
    }

    public function test_update_updates_task(): void
    {
        $this->actingAsAdmin();

        $task = Task::factory()->create();

        $payload = [
            'project_id' => $task->project_id,
            'title' => 'Updated Task Title',
            'priority' => 'high',
        ];

        $response = $this->putJson("{$this->apiPrefix}/{$task->id}", $payload);

        $response->assertStatus(200)
            ->assertJsonPath('data.title', 'Updated Task Title');

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
        ]);
    }

    public function test_destroy_soft_deletes_task(): void
    {
        $this->actingAsAdmin();

        $task = Task::factory()->create();

        $response = $this->deleteJson("{$this->apiPrefix}/{$task->id}");

        $response->assertStatus(200);
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }

    public function test_restore_restores_task(): void
    {
        $this->actingAsAdmin();

        $task = Task::factory()->create();
        $task->delete();

        $response = $this->postJson("{$this->apiPrefix}/{$task->id}/restore");

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'deleted_at' => null,
        ]);
    }

    public function test_sync_relations_basic_success(): void
    {
        $this->actingAsAdmin();

        $project = Project::factory()->create();
        $task = Task::factory()->create(['project_id' => $project->id]);
        $other = Task::factory()->create(['project_id' => $project->id]);
        $milestone = Milestone::factory()->create(['project_id' => $project->id]);

        $response = $this->postJson("{$this->apiPrefix}/{$task->id}/sync-relations", [
            'tasks' => [
                ['id' => $other->id, 'relation_type' => 'blocks'],
            ],
            'milestones' => [
                ['id' => $milestone->id, 'relation_type' => 'relates_to'],
            ],
        ]);

        $response->assertStatus(200);
    }
}
