<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'position',
        'thousand_separator',
        'decimal_separator',
        'decimals',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'decimals' => 'integer',
    ];

    public function financialGoals(): HasMany
    {
        return $this->hasMany(FinancialGoal::class);
    }

    public function format($amount): string
    {
        // Format the number with proper separators
        $formatted = number_format(
            $amount,
            $this->decimals,
            $this->decimal_separator,
            $this->thousand_separator
        );

        // Add currency symbol
        if ($this->position === 'before') {
            return $this->symbol . $formatted;
        } else {
            return $formatted . ' ' . $this->symbol;
        }
    }

    public function parse($formattedAmount): float
    {
        // Remove currency symbol
        $amount = str_replace($this->symbol, '', $formattedAmount);
        
        // Remove thousand separators and convert decimal separator
        $amount = str_replace($this->thousand_separator, '', $amount);
        $amount = str_replace($this->decimal_separator, '.', $amount);

        return (float) $amount;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('code');
    }
}
