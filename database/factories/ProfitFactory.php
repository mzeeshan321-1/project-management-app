<?php

namespace Database\Factories;

use App\Models\Profit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profit>
 */
class ProfitFactory extends Factory
{
    protected $model = Profit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $projectBudget = fake()->numberBetween(5000, 50000);
        $expertCost = fake()->numberBetween(1000, intval($projectBudget * 0.8));
        $profit = $projectBudget - $expertCost;
        $profitPercentage = $projectBudget > 0 ? round(($profit / $projectBudget) * 100, 2) : 0;

        return [
            'expert_cost' => $expertCost,
            'profit' => $profit,
            'profit_percentage' => $profitPercentage,
            'note' => fake()->optional()->sentence(),
        ];
    }
}
