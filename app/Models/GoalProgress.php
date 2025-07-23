<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoalProgress extends Model
{
    use HasFactory;

    protected $table = 'goal_progress';

    protected $fillable = [
        'financial_goal_id',
        'current_amount',
        'month',
        'year',
        'notes',
    ];

    protected $casts = [
        'current_amount' => 'decimal:2',
    ];

    public function financialGoal(): BelongsTo
    {
        return $this->belongsTo(FinancialGoal::class);
    }
}
