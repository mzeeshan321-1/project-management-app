<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['debit', 'credit', 'return']),
            'amount' => fake()->numberBetween(1000, 50000),
            'status' => fake()->randomElement(['pending', 'received', 'returned']),
            'note' => fake()->optional()->sentence(),
            'upload_invoice' => null,
        ];
    }
 }
