<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Tanent;
use App\Models\Expert;
use App\Models\Client;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function superAdmin()
    {
        return $this->afterCreating(function ($user) {
            $user->assignRole('super-admin');
        });
    }

    public function Tanent()
    {
        return $this->afterCreating(function ($user) {
            // assign role
            $user->assignRole('middleman');

            // create tenant record for this user
            Tanent::create([
                'user_id' => $user->id,
            ]);
        });
    }

    public function Expert($tenantId = null)
    {
        return $this->afterCreating(function ($user) use ($tenantId) {
            $user->assignRole('expert');

            Expert::create([
                'user_id' => $user->id,
                'tanent_id' => $tenantId,
                'specialization' => fake()->word(),
                'skills' => fake()->words(3, true),
            ]);
        });
    }
    
    public function Client($tenantId)
    {
        return $this->afterCreating(function ($user) use ($tenantId) {
            $user->assignRole('client');

            Client::create([
                'user_id' => $user->id,
                'tanent_id' => $tenantId,
                'industry' => fake()->word(),
            ]);
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
