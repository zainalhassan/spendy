<?php

use App\Http\Controllers\FinancialGoalController;
use App\Services\FinancialGoalService;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    $goalService = app(FinancialGoalService::class);
    $user = auth()->user();
    $currentYear = date('Y');
    
    // Get current year summary
    $summary = $goalService->getYearlySummary($user, $currentYear);
    
    // Get recent goals (last 3 goals)
    $recentGoals = $user->financialGoals()
        ->with(['currency', 'category'])
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get();
    
    // Calculate dashboard stats
    $stats = [
        'active_goals_count' => $summary['active_goals_count'],
        'average_progress' => $summary['average_progress'],
        'total_saved' => $recentGoals->sum(function($goal) {
            return $goal->current_amount ?? $goal->start_amount;
        }),
        'monthly_growth' => 0 // This would need more complex calculation
    ];
    
    return Inertia::render('Dashboard', [
        'stats' => $stats,
        'recentGoals' => $recentGoals
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Financial Goals Routes
Route::middleware(['auth', 'verified', 'financial.goal.authorization'])->group(function () {
    Route::resource('financial-goals', FinancialGoalController::class);
    Route::post('financial-goals/{financialGoal}/progress', [FinancialGoalController::class, 'updateProgress'])
        ->name('financial-goals.progress.update');
    Route::put('financial-goals/{financialGoal}/progress/{progress}', [FinancialGoalController::class, 'updateProgressRecord'])
        ->name('financial-goals.progress.edit');
    Route::delete('financial-goals/{financialGoal}/progress/{progress}', [FinancialGoalController::class, 'deleteProgressRecord'])
        ->name('financial-goals.progress.delete');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
