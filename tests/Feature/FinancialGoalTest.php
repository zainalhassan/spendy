<?php

use App\Models\Category;
use App\Models\Currency;
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

    $response->assertStatus(403);
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
    
    // Progress should be 55% (5500 / 10000) * 100
    expect(round($goal->progress_percentage, 1))->toBe(55.0);
});

test('goal expected progress percentage is calculated correctly', function () {
    $goal = FinancialGoal::factory()->create([
        'user_id' => $this->user->id,
        'start_amount' => 1000.00,
        'target_amount' => 10000.00,
        'year' => 2025,
    ]);

    $goal->refresh();
    
    // For June (month 6), expected progress should be 50% 
    // (1000 + (9000/12 * 6)) / 10000 = (1000 + 4500) / 10000 = 5500 / 10000 = 55%
    $expectedAmount = $goal->getExpectedAmountForMonth(6, 2025);
    $expectedPercentage = ($expectedAmount / 10000) * 100;
    
    expect(round($expectedPercentage, 1))->toBe(55.0);
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

test('all goals are shown by default', function () {
    FinancialGoal::factory()->create([
        'user_id' => $this->user->id,
        'year' => 2024,
    ]);

    FinancialGoal::factory()->create([
        'user_id' => $this->user->id,
        'year' => 2025,
    ]);

    $response = $this->get('/financial-goals');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('FinancialGoals/Index')
        ->where('currentYear', null)
        ->where('summary.goals', fn ($goals) => $goals->count() === 2)
    );
});

test('user can edit progress record', function () {
    $goal = FinancialGoal::factory()->create([
        'user_id' => $this->user->id,
    ]);

    // Create initial progress
    $progress = GoalProgress::factory()->create([
        'financial_goal_id' => $goal->id,
        'current_amount' => 2000.00,
        'month' => 6,
        'year' => 2025,
        'notes' => 'Initial progress',
    ]);

    // Update progress
    $updatedData = [
        'current_amount' => 3500.00,
        'month' => 6,
        'year' => 2025,
        'notes' => 'Updated progress notes',
    ];

    $response = $this->put("/financial-goals/{$goal->id}/progress/{$progress->id}", $updatedData);

    $response->assertRedirect();
    $this->assertDatabaseHas('goal_progress', [
        'id' => $progress->id,
        'financial_goal_id' => $goal->id,
        'current_amount' => 3500.00,
        'month' => 6,
        'year' => 2025,
        'notes' => 'Updated progress notes',
    ]);
});

test('user cannot edit other users progress', function () {
    $otherUser = User::factory()->create();
    $otherGoal = FinancialGoal::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $progress = GoalProgress::factory()->create([
        'financial_goal_id' => $otherGoal->id,
        'current_amount' => 2000.00,
        'month' => 6,
        'year' => 2025,
    ]);

    $response = $this->put("/financial-goals/{$otherGoal->id}/progress/{$progress->id}", [
        'current_amount' => 3500.00,
        'month' => 6,
        'year' => 2025,
    ]);

    $response->assertStatus(403);
});

test('user can delete progress record', function () {
    $goal = FinancialGoal::factory()->create([
        'user_id' => $this->user->id,
    ]);

    // Create progress
    $progress = GoalProgress::factory()->create([
        'financial_goal_id' => $goal->id,
        'current_amount' => 2000.00,
        'month' => 6,
        'year' => 2025,
        'notes' => 'Test progress',
    ]);

    $response = $this->delete("/financial-goals/{$goal->id}/progress/{$progress->id}");

    $response->assertRedirect();
    $this->assertDatabaseMissing('goal_progress', [
        'id' => $progress->id,
    ]);
});

test('user cannot delete other users progress', function () {
    $otherUser = User::factory()->create();
    $otherGoal = FinancialGoal::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $progress = GoalProgress::factory()->create([
        'financial_goal_id' => $otherGoal->id,
        'current_amount' => 2000.00,
        'month' => 6,
        'year' => 2025,
    ]);

    $response = $this->delete("/financial-goals/{$otherGoal->id}/progress/{$progress->id}");

    $response->assertStatus(403);
    $this->assertDatabaseHas('goal_progress', [
        'id' => $progress->id,
    ]);
});

test('unauthenticated user cannot access financial goals', function () {
    auth()->logout();
    
    $response = $this->get('/financial-goals');

    $response->assertRedirect('/login');
});

test('unauthenticated user cannot create financial goals', function () {
    auth()->logout();
    
    $response = $this->get('/financial-goals/create');

    $response->assertRedirect('/login');
});

test('unauthenticated user cannot view specific goal', function () {
    $goal = FinancialGoal::factory()->create([
        'user_id' => User::factory()->create()->id,
    ]);

    $response = $this->get("/financial-goals/{$goal->id}");

    $response->assertStatus(403);
});

test('user cannot access other users goal details', function () {
    $otherUser = User::factory()->create();
    $goal = FinancialGoal::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $response = $this->get("/financial-goals/{$goal->id}");

    $response->assertStatus(403);
});

test('user cannot edit other users goal', function () {
    $otherUser = User::factory()->create();
    $goal = FinancialGoal::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $response = $this->get("/financial-goals/{$goal->id}/edit");

    $response->assertStatus(403);
});

test('user cannot update other users goal', function () {
    $otherUser = User::factory()->create();
    $goal = FinancialGoal::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $updateData = [
        'name' => 'Updated Goal',
        'target_amount' => 15000.00,
        'start_amount' => 1000.00,
        'year' => 2025,
        'category_id' => Category::factory()->create()->id,
        'currency_id' => Currency::factory()->create()->id,
    ];

    $response = $this->put("/financial-goals/{$goal->id}", $updateData);

    $response->assertStatus(403);
});

test('user cannot delete other users goal', function () {
    $otherUser = User::factory()->create();
    $goal = FinancialGoal::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $response = $this->delete("/financial-goals/{$goal->id}");

    $response->assertStatus(403);
    $this->assertDatabaseHas('financial_goals', [
        'id' => $goal->id,
    ]);
});

test('user cannot update progress for other users goal', function () {
    $otherUser = User::factory()->create();
    $otherGoal = FinancialGoal::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $progressData = [
        'current_amount' => 2500.00,
        'month' => 6,
        'year' => 2025,
        'notes' => 'Test progress',
    ];

    $response = $this->post("/financial-goals/{$otherGoal->id}/progress", $progressData);

    $response->assertStatus(403);
});

test('user cannot edit progress for other users goal', function () {
    $otherUser = User::factory()->create();
    $otherGoal = FinancialGoal::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $progress = GoalProgress::factory()->create([
        'financial_goal_id' => $otherGoal->id,
        'current_amount' => 2000.00,
        'month' => 6,
        'year' => 2025,
    ]);

    $updateData = [
        'current_amount' => 3500.00,
        'month' => 6,
        'year' => 2025,
        'notes' => 'Updated progress',
    ];

    $response = $this->put("/financial-goals/{$otherGoal->id}/progress/{$progress->id}", $updateData);

    $response->assertStatus(403);
});
