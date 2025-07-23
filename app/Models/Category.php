<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'color',
        'icon',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function financialGoals(): HasMany
    {
        return $this->hasMany(FinancialGoal::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('display_name');
    }

    public function getSeverityAttribute(): string
    {
        $severities = [
            'savings' => 'success',
            'emergency' => 'danger',
            'car' => 'info',
            'house' => 'warning',
            'investment' => 'primary',
            'vacation' => 'info',
            'education' => 'success',
            'wedding' => 'warning',
            'business' => 'primary',
            'other' => 'secondary',
        ];

        return $severities[$this->name] ?? 'secondary';
    }
}
