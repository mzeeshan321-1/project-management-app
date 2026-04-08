<?php

namespace Database\Factories;

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
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'due_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('d-M-Y'),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed', 'on_hold', 'cancelled']),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
        ];
    }
}
