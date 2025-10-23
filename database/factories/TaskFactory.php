<?php

namespace Database\Factories;

use App\Enums\PriorityLevel;
use App\Enums\ProgressStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => \App\Models\Project::factory(),
            'assigned_to_id' => \App\Models\User::factory(),
            'assigned_by_id' => \App\Models\User::factory(),
            'title' => $this->faker->unique()->sentence(6),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(ProgressStatus::allStatuses()),
            'priority' => $this->faker->randomElement(PriorityLevel::allLevels()),
            'due_date' => $this->faker->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
        ];
    }
}
