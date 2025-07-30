<?php

namespace App\Services;

use App\Models\FinancialGoal;
use App\Models\GoalProgress;
use App\Models\User;
use Illuminate\Support\Collection;

class FinancialGoalService
{
    public function createGoal(User $user, array $data): FinancialGoal
    {
        // Authorization is handled in the controller
        return $user->financialGoals()->create($data);
    }

    public function updateGoal(FinancialGoal $goal, array $data): FinancialGoal
    {
        // Authorization is handled in the controller
        $goal->update($data);
        return $goal->fresh();
    }

    public function deleteGoal(FinancialGoal $goal): bool
    {
        // Authorization is handled in the controller
        return $goal->delete();
    }

    public function getUserGoals(User $user, int $year = null): Collection
    {
        $query = $user->financialGoals()->with(['progress', 'currency', 'category']);
        
        if ($year) {
            $query->where('year', $year);
        }
        
        return $query->orderBy('year', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->get();
    }

    public function addProgress(FinancialGoal $goal, array $data): GoalProgress
    {
        // Authorization is handled in the controller
        return $goal->progress()->create($data);
    }

    public function updateProgress(GoalProgress $progress, array $data): GoalProgress
    {
        $progress->update($data);
        return $progress->fresh();
    }

    public function deleteProgress(GoalProgress $progress): bool
    {
        return $progress->delete();
    }

    public function getGoalProgress(FinancialGoal $goal): Collection
    {
        return $goal->progress()
                   ->orderBy('year', 'asc')
                   ->orderBy('month', 'asc')
                   ->get();
    }

    public function getGoalSummary(FinancialGoal $goal): array
    {
        $currentAmount = $goal->current_amount;
        $progressPercentage = $goal->progress_percentage;
        $expectedPercentage = $goal->expected_progress_percentage;
        
        $monthlyProgress = $this->getGoalProgress($goal);
        
        return [
            'goal' => $goal,
            'current_amount' => $currentAmount,
            'progress_percentage' => $progressPercentage,
            'expected_percentage' => $expectedPercentage,
            'monthly_progress' => $monthlyProgress,
            'is_ahead_of_schedule' => $progressPercentage > $expectedPercentage,
            'is_behind_schedule' => $progressPercentage < $expectedPercentage,
        ];
    }

    public function getYearlySummary(User $user, int $year): array
    {
        $goals = $this->getUserGoals($user, $year);
        
        $totalProgress = 0;
        $totalExpected = 0;
        $activeGoals = 0;
        
        foreach ($goals as $goal) {
            if ($goal->is_active) {
                $totalProgress += $goal->progress_percentage;
                $totalExpected += $goal->expected_progress_percentage;
                $activeGoals++;
            }
        }
        
        return [
            'year' => $year,
            'goals' => $goals,
            'average_progress' => $activeGoals > 0 ? $totalProgress / $activeGoals : 0,
            'average_expected' => $activeGoals > 0 ? $totalExpected / $activeGoals : 0,
            'active_goals_count' => $activeGoals,
        ];
    }

    public function getAllGoalsSummary(User $user): array
    {
        $goals = $this->getUserGoals($user);
        
        $totalProgress = 0;
        $totalExpected = 0;
        $activeGoals = 0;
        
        foreach ($goals as $goal) {
            if ($goal->is_active) {
                $totalProgress += $goal->progress_percentage;
                $totalExpected += $goal->expected_progress_percentage;
                $activeGoals++;
            }
        }
        
        return [
            'year' => null,
            'goals' => $goals,
            'average_progress' => $activeGoals > 0 ? $totalProgress / $activeGoals : 0,
            'average_expected' => $activeGoals > 0 ? $totalExpected / $activeGoals : 0,
            'active_goals_count' => $activeGoals,
        ];
    }
} 