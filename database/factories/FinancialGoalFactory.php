<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinancialGoal>
 */
class FinancialGoalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startAmount = $this->faker->randomFloat(2, 0, 5000);
        $targetAmount = $startAmount + $this->faker->randomFloat(2, 1000, 50000);
        
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->randomElement([
                'Emergency Fund',
                'Car Savings',
                'House Down Payment',
                'Vacation Fund',
                'Investment Portfolio',
                'Wedding Fund',
                'Business Startup',
                'Education Fund',
            ]),
            'description' => $this->faker->optional()->sentence(),
            'start_amount' => $startAmount,
            'target_amount' => $targetAmount,
            'year' => $this->faker->numberBetween(2024, 2026),
            'category_id' => \App\Models\Category::inRandomOrder()->first()->id ?? 1,
            'currency_id' => \App\Models\Currency::inRandomOrder()->first()->id ?? 1,
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }

    /**
     * Indicate that the goal is for savings.
     */
    public function savings(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'savings',
            'name' => 'Emergency Fund',
        ]);
    }

    /**
     * Indicate that the goal is for a car.
     */
    public function car(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'car',
            'name' => 'Car Savings',
        ]);
    }

    /**
     * Indicate that the goal is for a house.
     */
    public function house(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'house',
            'name' => 'House Down Payment',
        ]);
    }

    /**
     * Indicate that the goal is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the goal is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
