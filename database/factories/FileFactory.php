<?php

namespace Database\Factories;

use App\Models\file;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\file>
 */
class FileFactory extends Factory
{
        protected $model = file::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'file_name' => fake()->word(),
            'file_type' => fake()->randomElement(['document', 'image']),
            'file_url' => fake()->url(),
            'description' => fake()->optional()->sentence(),
        ];
    }
}
