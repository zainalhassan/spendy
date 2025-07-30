<?php

namespace App\Policies;

use App\Models\FinancialGoal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FinancialGoalPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Users can view their own goals list
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FinancialGoal $financialGoal): bool
    {
        return $user->id === $financialGoal->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create goals
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FinancialGoal $financialGoal): bool
    {
        return $user->id === $financialGoal->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FinancialGoal $financialGoal): bool
    {
        return $user->id === $financialGoal->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FinancialGoal $financialGoal): bool
    {
        return $user->id === $financialGoal->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FinancialGoal $financialGoal): bool
    {
        return $user->id === $financialGoal->user_id;
    }

    /**
     * Determine whether the user can manage progress for the goal.
     */
    public function manageProgress(User $user, FinancialGoal $financialGoal): bool
    {
        return $user->id === $financialGoal->user_id;
    }
}
