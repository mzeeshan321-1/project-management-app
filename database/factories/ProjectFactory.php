<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Tanent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'start_date' => fake()->date(),
            'deadline' => fake()->date(),
            'budget' => fake()->numberBetween(1000, 10000),
            'status' => fake()->randomElement([
                'in_progress',
                'completed',
                'inactive',
                'cancelled',
            ]),
        ];
    }
}

