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
        'expected_amount',
        'year',
        'category_id',
        'currency_id',
        'is_active',
    ];

    protected $casts = [
        'start_amount' => 'decimal:2',
        'target_amount' => 'decimal:2',
        'expected_amount' => 'decimal:2',
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
        if ($this->target_amount <= $this->start_amount) {
            return 0;
        }
        
        $current = $this->current_amount;
        $total = $this->target_amount - $this->start_amount;
        $progress = $current - $this->start_amount;
        
        return min(100, max(0, ($progress / $total) * 100));
    }

    public function getExpectedProgressPercentageAttribute()
    {
        if (!$this->expected_amount || $this->target_amount <= $this->start_amount) {
            return 0;
        }
        
        $total = $this->target_amount - $this->start_amount;
        $expected = $this->expected_amount - $this->start_amount;
        
        return min(100, max(0, ($expected / $total) * 100));
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
        return $this->expected_amount ? $this->currency->format($this->expected_amount) : null;
    }

    public function getFormattedCurrentAmountAttribute()
    {
        return $this->currency->format($this->current_amount);
    }
}
