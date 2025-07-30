<?php

namespace App\Providers;

use App\Models\FinancialGoal;
use App\Models\GoalProgress;
use App\Models\User;
use App\Policies\FinancialGoalPolicy;
use App\Policies\GoalProgressPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        FinancialGoal::class => FinancialGoalPolicy::class,
        GoalProgress::class => GoalProgressPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for financial goals
        Gate::define('view-financial-goals', function (User $user) {
            return true; // Any authenticated user can view their goals
        });

        Gate::define('create-financial-goals', function (User $user) {
            return true; // Any authenticated user can create goals
        });

        Gate::define('manage-financial-goal', function (User $user, FinancialGoal $financialGoal) {
            return $user->id === $financialGoal->user_id;
        });

        Gate::define('manage-goal-progress', function (User $user, FinancialGoal $financialGoal) {
            return $user->id === $financialGoal->user_id;
        });

        Gate::define('manage-progress-record', function (User $user, GoalProgress $goalProgress) {
            return $user->id === $goalProgress->financialGoal->user_id;
        });
    }
}
