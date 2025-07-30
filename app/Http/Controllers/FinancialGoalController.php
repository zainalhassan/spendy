<?php

namespace App\Http\Controllers;

use App\Actions\CreateFinancialGoal;
use App\Actions\UpdateGoalProgress;
use App\Http\Requests\StoreFinancialGoalRequest;
use App\Http\Requests\UpdateFinancialGoalRequest;
use App\Http\Requests\UpdateGoalProgressRequest;
use App\Models\FinancialGoal;
use App\Models\GoalProgress;
use App\Models\Currency;
use App\Models\Category;
use App\Services\FinancialGoalService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FinancialGoalController extends Controller
{
    public function __construct(
        private FinancialGoalService $goalService,
        private CreateFinancialGoal $createGoalAction,
        private UpdateGoalProgress $updateProgressAction
    ) {}

    public function index(Request $request): Response
    {
        $this->authorize('view-financial-goals');
        
        $year = $request->get('year');
        $year = $year ? (int) $year : null;
        
        if ($year) {
            $summary = $this->goalService->getYearlySummary(auth()->user(), $year);
        } else {
            $summary = $this->goalService->getAllGoalsSummary(auth()->user());
        }
        
        return Inertia::render('FinancialGoals/Index', [
            'goals' => $summary['goals'],
            'summary' => $summary,
            'currentYear' => $year,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create-financial-goals');
        
        return Inertia::render('FinancialGoals/Create', [
            'currencies' => Currency::active()->ordered()->get(),
            'categories' => Category::active()->ordered()->get(),
        ]);
    }

    public function store(StoreFinancialGoalRequest $request)
    {
        $this->authorize('create-financial-goals');
        
        $goal = $this->createGoalAction->execute(auth()->user(), $request->validated());
        
        return redirect()->route('financial-goals.show', $goal)
            ->with('success', 'Financial goal created successfully!');
    }

    public function show(FinancialGoal $financialGoal): Response
    {
        $this->authorize('view', $financialGoal);

        $summary = $this->goalService->getGoalSummary($financialGoal->load(['currency', 'category']));
        
        return Inertia::render('FinancialGoals/Show', [
            'goal' => $summary,
        ]);
    }

    public function edit(FinancialGoal $financialGoal): Response
    {
        $this->authorize('update', $financialGoal);

        return Inertia::render('FinancialGoals/Edit', [
            'goal' => $financialGoal->load(['currency', 'category']),
            'currencies' => Currency::active()->ordered()->get(),
            'categories' => Category::active()->ordered()->get(),
        ]);
    }

    public function update(UpdateFinancialGoalRequest $request, FinancialGoal $financialGoal)
    {
        $this->authorize('update', $financialGoal);

        $this->goalService->updateGoal($financialGoal, $request->validated());
        
        return redirect()->route('financial-goals.show', $financialGoal)
            ->with('success', 'Financial goal updated successfully!');
    }

    public function destroy(FinancialGoal $financialGoal)
    {
        $this->authorize('delete', $financialGoal);

        $this->goalService->deleteGoal($financialGoal);
        
        return redirect()->route('financial-goals.index')
            ->with('success', 'Financial goal deleted successfully!');
    }

    public function updateProgress(UpdateGoalProgressRequest $request, FinancialGoal $financialGoal)
    {
        $this->authorize('manage-goal-progress', $financialGoal);

        $progress = $this->updateProgressAction->execute($financialGoal, $request->validated());
        
        return back()->with('success', 'Progress updated successfully!');
    }

    public function updateProgressRecord(UpdateGoalProgressRequest $request, FinancialGoal $financialGoal, GoalProgress $progress)
    {
        $this->authorize('manage-goal-progress', $financialGoal);
        $this->authorize('update', $progress);

        $this->goalService->updateProgress($progress, $request->validated());
        
        return back()->with('success', 'Progress updated successfully!');
    }

    public function deleteProgressRecord(FinancialGoal $financialGoal, GoalProgress $progress)
    {
        $this->authorize('manage-goal-progress', $financialGoal);
        $this->authorize('delete', $progress);

        $this->goalService->deleteProgress($progress);
        
        return back()->with('success', 'Progress record deleted successfully!');
    }
}
