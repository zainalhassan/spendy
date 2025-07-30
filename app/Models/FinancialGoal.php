<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FinancialGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'start_amount',
        'target_amount',
        'year',
        'category_id',
        'currency_id',
        'is_active',
    ];

    protected $casts = [
        'start_amount' => 'decimal:2',
        'target_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'current_amount',
        'progress_percentage',
        'expected_progress_percentage',
        'formatted_start_amount',
        'formatted_target_amount',
        'formatted_expected_amount',
        'formatted_current_amount',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(GoalProgress::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getCurrentAmountAttribute()
    {
        $latestProgress = $this->progress()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->first();
            
        return $latestProgress ? $latestProgress->current_amount : $this->start_amount;
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->target_amount <= 0) {
            return 0;
        }
        
        $current = $this->current_amount;
        
        return min(100, max(0, ($current / $this->target_amount) * 100));
    }

    public function getExpectedProgressPercentageAttribute()
    {
        if ($this->target_amount <= 0) {
            return 0;
        }
        
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $goalYear = $this->year;
        
        // Calculate how many months have passed since the start of the goal year
        $monthsPassed = 0;
        if ($currentYear >= $goalYear) {
            $monthsPassed = min(12, $currentMonth);
        }
        
        // Calculate expected amount for current month
        $expectedAmount = $this->getExpectedAmountForMonth($currentMonth, $currentYear);
        
        return min(100, max(0, ($expectedAmount / $this->target_amount) * 100));
    }

    public function getExpectedAmountForMonth($month, $year = null)
    {
        if ($year === null) {
            $year = $this->year;
        }
        
        // Calculate total months in the goal period (assuming 12 months)
        $totalMonths = 12;
        
        // Calculate how many months have passed (1-12)
        $monthsPassed = min(12, $month);
        
        // Calculate the total amount to save (target - start)
        $totalToSave = $this->target_amount - $this->start_amount;
        
        // Calculate monthly savings rate
        $monthlySavingsRate = $totalToSave / $totalMonths;
        
        // Calculate expected amount for this month
        $expectedAmount = $this->start_amount + ($monthlySavingsRate * $monthsPassed);
        
        return min($this->target_amount, max($this->start_amount, $expectedAmount));
    }

    public function getFormattedStartAmountAttribute()
    {
        return $this->currency->format($this->start_amount);
    }

    public function getFormattedTargetAmountAttribute()
    {
        return $this->currency->format($this->target_amount);
    }

    public function getFormattedExpectedAmountAttribute()
    {
        $expectedAmount = $this->getExpectedAmountForMonth(now()->month, now()->year);
        return $this->currency->format($expectedAmount);
    }

    public function getFormattedCurrentAmountAttribute()
    {
        return $this->currency->format($this->current_amount);
    }

    /**
     * Check if the given user can manage this goal.
     */
    public function canBeManagedBy(User $user): bool
    {
        return $user->id === $this->user_id;
    }

    /**
     * Check if the given user can view this goal.
     */
    public function canBeViewedBy(User $user): bool
    {
        return $user->id === $this->user_id;
    }
}
