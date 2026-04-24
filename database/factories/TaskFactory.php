<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'name' => ucfirst($this->faker->words(3, true)),
            'priority' => 0, // Seeder assigns final priorities
        ];
    }
}
