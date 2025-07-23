<?php

use App\Models\FinancialGoal;
use App\Models\GoalProgress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

test('user can view financial goals index', function () {
    $response = $this->get('/financial-goals');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('FinancialGoals/Index')
        ->has('summary')
        ->has('currentYear')
    );
});

test('user can view create goal page', function () {
    $response = $this->get('/financial-goals/create');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('FinancialGoals/Create')
    );
});

test('user can create a financial goal', function () {
    $category = \App\Models\Category::where('name', 'emergency')->first();
    $currency = \App\Models\Currency::where('code', 'USD')->first();
    
    $goalData = [
        'name' => 'Emergency Fund',
        'description' => 'Save for emergencies',
        'category_id' => $category->id,
        'currency_id' => $currency->id,
        'year' => 2025,
        'start_amount' => 1000.00,
        'target_amount' => 10000.00,
        'expected_amount' => 5000.00,
    ];

    $response = $this->post('/financial-goals', $goalData);

    $response->assertRedirect();
    $this->assertDatabaseHas('financial_goals', [
        'user_id' => $this->user->id,
        'name' => 'Emergency Fund',
        'category_id' => $category->id,
        'currency_id' => $currency->id,
        'year' => 2025,
    ]);
});

test('user cannot create goal with invalid data', function () {
    $invalidData = [
        'name' => '',
        'target_amount' => 500.00, // Less than start amount
        'start_amount' => 1000.00,
        'currency_id' => 99999, // Invalid currency ID
    ];

    $response = $this->post('/financial-goals', $invalidData);

    $response->assertSessionHasErrors(['name', 'target_amount', 'currency_id']);
});

test('user can view a specific goal', function () {
    $goal = FinancialGoal::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $response = $this->get("/financial-goals/{$goal->id}");

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('FinancialGoals/Show')
        ->has('goal')
    );
});

test('user can update goal progress', function () {
    $goal = FinancialGoal::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $progressData = [
        'current_amount' => 2500.00,
        'month' => 6,
        'year' => 2025,
        'notes' => 'Halfway through the year!',
    ];

    $response = $this->post("/financial-goals/{$goal->id}/progress", $progressData);

    $response->assertRedirect();
    $this->assertDatabaseHas('goal_progress', [
        'financial_goal_id' => $goal->id,
        'current_amount' => 2500.00,
        'month' => 6,
        'year' => 2025,
    ]);
});

test('user can update existing progress for same month', function () {
    $goal = FinancialGoal::factory()->create([
        'user_id' => $this->user->id,
    ]);

    // Create initial progress
    GoalProgress::factory()->create([
        'financial_goal_id' => $goal->id,
        'current_amount' => 2000.00,
        'month' => 6,
        'year' => 2025,
    ]);

    // Update progress for same month
    $updatedData = [
        'current_amount' => 3000.00,
        'month' => 6,
        'year' => 2025,
        'notes' => 'Updated amount',
    ];

    $response = $this->post("/financial-goals/{$goal->id}/progress", $updatedData);

    $response->assertRedirect();
    $this->assertDatabaseHas('goal_progress', [
        'financial_goal_id' => $goal->id,
        'current_amount' => 3000.00,
        'month' => 6,
        'year' => 2025,
    ]);
});

test('user can edit a goal', function () {
    $goal = FinancialGoal::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $response = $this->get("/financial-goals/{$goal->id}/edit");

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('FinancialGoals/Edit')
        ->has('goal')
    );
});

test('user can update a goal', function () {
    $goal = FinancialGoal::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $category = \App\Models\Category::where('name', 'savings')->first();
    $currency = \App\Models\Currency::where('code', 'EUR')->first();

    $updateData = [
        'name' => 'Updated Emergency Fund',
        'description' => 'Updated description',
        'category_id' => $category->id,
        'currency_id' => $currency->id,
        'year' => 2025,
        'start_amount' => 1000.00,
        'target_amount' => 15000.00,
        'expected_amount' => 7500.00,
        'is_active' => true,
    ];

    $response = $this->put("/financial-goals/{$goal->id}", $updateData);

    $response->assertRedirect();
    $this->assertDatabaseHas('financial_goals', [
        'id' => $goal->id,
        'name' => 'Updated Emergency Fund',
        'target_amount' => 15000.00,
        'currency_id' => $currency->id,
    ]);
});

test('user can delete a goal', function () {
    $goal = FinancialGoal::factory()->create([
        'user_id' => $this->user->id,
    ]);

    $response = $this->delete("/financial-goals/{$goal->id}");

    $response->assertRedirect('/financial-goals');
    $this->assertDatabaseMissing('financial_goals', [
        'id' => $goal->id,
    ]);
});

test('user cannot access other users goals', function () {
    $otherUser = User::factory()->create();
    $goal = FinancialGoal::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $response = $this->get("/financial-goals/{$goal->id}");

    $response->assertStatus(404);
});

test('goal progress percentage is calculated correctly', function () {
    $goal = FinancialGoal::factory()->create([
        'user_id' => $this->user->id,
        'start_amount' => 1000.00,
        'target_amount' => 10000.00,
    ]);

    // Add progress
    GoalProgress::factory()->create([
        'financial_goal_id' => $goal->id,
        'current_amount' => 5500.00,
        'month' => 6,
        'year' => 2025,
    ]);

    $goal->refresh();
    
    // Progress should be 50% (5500 - 1000) / (10000 - 1000) * 100
    expect($goal->progress_percentage)->toBe(50.0);
});

test('goal expected progress percentage is calculated correctly', function () {
    $goal = FinancialGoal::factory()->create([
        'user_id' => $this->user->id,
        'start_amount' => 1000.00,
        'target_amount' => 10000.00,
        'expected_amount' => 5500.00,
    ]);

    $goal->refresh();
    
    // Expected progress should be 50% (5500 - 1000) / (10000 - 1000) * 100
    expect($goal->expected_progress_percentage)->toBe(50.0);
});

test('goals are filtered by year', function () {
    FinancialGoal::factory()->create([
        'user_id' => $this->user->id,
        'year' => 2024,
    ]);

    FinancialGoal::factory()->create([
        'user_id' => $this->user->id,
        'year' => 2025,
    ]);

    $response = $this->get('/financial-goals?year=2025');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('FinancialGoals/Index')
        ->where('currentYear', 2025)
        ->where('summary.goals', fn ($goals) => $goals->count() === 1)
    );
});
