<?php

namespace App\Policies;

use App\Models\GoalProgress;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GoalProgressPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Users can view their own progress
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GoalProgress $goalProgress): bool
    {
        return $user->id === $goalProgress->financialGoal->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create progress
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GoalProgress $goalProgress): bool
    {
        return $user->id === $goalProgress->financialGoal->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GoalProgress $goalProgress): bool
    {
        return $user->id === $goalProgress->financialGoal->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GoalProgress $goalProgress): bool
    {
        return $user->id === $goalProgress->financialGoal->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GoalProgress $goalProgress): bool
    {
        return $user->id === $goalProgress->financialGoal->user_id;
    }
}
