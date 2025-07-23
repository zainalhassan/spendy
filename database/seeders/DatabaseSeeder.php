<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\FinancialGoal;
use App\Models\GoalProgress;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create sample financial goals
        $goals = [
            [
                'name' => 'Emergency Fund',
                'description' => 'Save for unexpected expenses',
                'start_amount' => 1000.00,
                'target_amount' => 10000.00,
                'expected_amount' => 5000.00,
                'year' => 2025,
                'category_id' => 2, // emergency
                'currency_id' => 1, // USD
                'is_active' => true
            ],
            [
                'name' => 'Car Savings',
                'description' => 'Save for a new car',
                'start_amount' => 5000.00,
                'target_amount' => 25000.00,
                'expected_amount' => 15000.00,
                'year' => 2025,
                'category_id' => 3, // car
                'currency_id' => 1, // USD
                'is_active' => true
            ],
            [
                'name' => 'Vacation Fund',
                'description' => 'Save for summer vacation',
                'start_amount' => 0.00,
                'target_amount' => 5000.00,
                'expected_amount' => 2500.00,
                'year' => 2025,
                'category_id' => 6, // vacation
                'currency_id' => 1, // USD
                'is_active' => true
            ]
        ];

        foreach ($goals as $goalData) {
            $goal = FinancialGoal::create(array_merge($goalData, ['user_id' => $user->id]));
            
            // Add some progress for each goal
            $months = [3, 6, 9];
            foreach ($months as $month) {
                $progressAmount = $goal->start_amount + (($goal->target_amount - $goal->start_amount) * $month / 12);
                GoalProgress::create([
                    'financial_goal_id' => $goal->id,
                    'current_amount' => $progressAmount,
                    'month' => $month,
                    'year' => 2025,
                    'notes' => 'Monthly progress update'
                ]);
            }
        }
    }
}
