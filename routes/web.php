<?php

use App\Http\Controllers\FinancialGoalController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Financial Goals Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('financial-goals', FinancialGoalController::class);
    Route::post('financial-goals/{financialGoal}/progress', [FinancialGoalController::class, 'updateProgress'])
        ->name('financial-goals.progress.update');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
