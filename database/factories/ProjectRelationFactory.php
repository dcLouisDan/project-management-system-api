<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectRelation>
 */
class ProjectRelationFactory extends Factory
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
            'created_by_id' => \App\Models\User::factory(),
            'source_type' => $this->faker->randomElement(['App\Models\Task', 'App\Models\Milestone']),
            'source_id' => null, // To be set after creation
            'target_type' => $this->faker->randomElement(['App\Models\Task', 'App\Models\Milestone']),
            'target_id' => null, // To be set after creation
            'relation_type' => $this->faker->randomElement(['depends_on', 'related_to']),
        ];
    }
}
