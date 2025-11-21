<?php

namespace Tests\Feature;

use App\Enums\ProgressStatus;
use App\Enums\UserRoles;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskReviewControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private string $apiPrefix = '/tasks';
    private TaskService $taskService;
    private User $auth;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
        $this->auth = $this->createAndAuthenticateUser();
        $this->taskService = new TaskService();
    }

    protected function createAndAuthenticateUser(): User
    {
        $user = User::factory()->create();
        $user->assignRole(UserRoles::ADMIN->value);
        $this->actingAs($user, 'web');

        return $user;
    }

    public function test_task_can_start()
    {
        $task = Task::factory()->create([
            'assigned_to_id' => $this->auth->id
        ]);
        $this->postJson($this->apiPrefix . '/' . $task->id . '/start');

        $task->refresh();

        $this->assertEquals(ProgressStatus::IN_PROGRESS->value, $task->status);
    }

    public function test_task_cannot_be_started_by_unauthorized_user()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create([
            'assigned_to_id' => $user->id
        ]);
        $response = $this->postJson($this->apiPrefix . '/' . $task->id . '/start');

        $response->assertStatus(403);
    }

    public function test_task_can_be_submitted_for_review()
    {
        $task = Task::factory()->create([
            'assigned_to_id' => $this->auth->id,
            'assigned_by_id' => $this->auth->id,
            'status' => ProgressStatus::IN_PROGRESS->value
        ]);

        $data = [
            'notes' => 'The quick brown fox jumps over the lazy dog'
        ];

        $response = $this->postJson($this->apiPrefix . '/' . $task->id . '/submit', $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('task_reviews', [
            'submission_notes' => $data['notes']
        ]);
    }

    // public function test_task_review_can_start()
    // {
    //     $task = Task::factory()->create([
    //         'assigned_to_id' => $this->auth->id
    //     ]);
    //     $this->postJson($this->apiPrefix . '/' . $task->id . '/start');

    //     $task->refresh();

    //     $this->assertEquals(ProgressStatus::IN_PROGRESS->value, $task->status);
    // }

    // public function test_task_review_cannot_be_started_by_unauthorized_user()
    // {
    //     $user = User::factory()->create();
    //     $task = Task::factory()->create([
    //         'assigned_to_id' => $user->id
    //     ]);
    //     $response = $this->postJson($this->apiPrefix . '/' . $task->id . '/start');

    //     $response->assertStatus(403);
    // }
}
