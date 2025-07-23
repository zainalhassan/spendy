<?php

namespace Database\Factories;

use App\Models\FinancialGoal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GoalProgress>
 */
class GoalProgressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'financial_goal_id' => FinancialGoal::factory(),
            'current_amount' => $this->faker->randomFloat(2, 100, 10000),
            'month' => $this->faker->numberBetween(1, 12),
            'year' => $this->faker->numberBetween(2024, 2026),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the progress is for a specific month.
     */
    public function forMonth(int $month): static
    {
        return $this->state(fn (array $attributes) => [
            'month' => $month,
        ]);
    }

    /**
     * Indicate that the progress is for a specific year.
     */
    public function forYear(int $year): static
    {
        return $this->state(fn (array $attributes) => [
            'year' => $year,
        ]);
    }

    /**
     * Indicate that the progress has notes.
     */
    public function withNotes(): static
    {
        return $this->state(fn (array $attributes) => [
            'notes' => $this->faker->sentence(),
        ]);
    }

    /**
     * Indicate that the progress is for the current month.
     */
    public function currentMonth(): static
    {
        return $this->state(fn (array $attributes) => [
            'month' => now()->month,
            'year' => now()->year,
        ]);
    }
}
