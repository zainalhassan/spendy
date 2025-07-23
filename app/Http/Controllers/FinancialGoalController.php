<?php

namespace App\Http\Controllers;

use App\Actions\CreateFinancialGoal;
use App\Actions\UpdateGoalProgress;
use App\Models\FinancialGoal;
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
        $year = (int) $request->get('year', date('Y'));
        $summary = $this->goalService->getYearlySummary(auth()->user(), $year);
        
        return Inertia::render('FinancialGoals/Index', [
            'summary' => $summary,
            'currentYear' => $year,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('FinancialGoals/Create', [
            'currencies' => Currency::active()->ordered()->get(),
            'categories' => Category::active()->ordered()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $goal = $this->createGoalAction->execute(auth()->user(), $request->all());
        
        return redirect()->route('financial-goals.show', $goal)
            ->with('success', 'Financial goal created successfully!');
    }

    public function show(FinancialGoal $financialGoal): Response
    {
        // Check if the goal belongs to the authenticated user
        if ($financialGoal->user_id !== auth()->id()) {
            abort(404);
        }

        $summary = $this->goalService->getGoalSummary($financialGoal->load(['currency', 'category']));
        
        return Inertia::render('FinancialGoals/Show', [
            'goal' => $summary,
        ]);
    }

    public function edit(FinancialGoal $financialGoal): Response
    {
        // Check if the goal belongs to the authenticated user
        if ($financialGoal->user_id !== auth()->id()) {
            abort(404);
        }

        return Inertia::render('FinancialGoals/Edit', [
            'goal' => $financialGoal->load(['currency', 'category']),
            'currencies' => Currency::active()->ordered()->get(),
            'categories' => Category::active()->ordered()->get(),
        ]);
    }

    public function update(Request $request, FinancialGoal $financialGoal)
    {
        // Check if the goal belongs to the authenticated user
        if ($financialGoal->user_id !== auth()->id()) {
            abort(404);
        }

        $this->goalService->updateGoal($financialGoal, $request->all());
        
        return redirect()->route('financial-goals.show', $financialGoal)
            ->with('success', 'Financial goal updated successfully!');
    }

    public function destroy(FinancialGoal $financialGoal)
    {
        // Check if the goal belongs to the authenticated user
        if ($financialGoal->user_id !== auth()->id()) {
            abort(404);
        }

        $this->goalService->deleteGoal($financialGoal);
        
        return redirect()->route('financial-goals.index')
            ->with('success', 'Financial goal deleted successfully!');
    }

    public function updateProgress(Request $request, FinancialGoal $financialGoal)
    {
        // Check if the goal belongs to the authenticated user
        if ($financialGoal->user_id !== auth()->id()) {
            abort(404);
        }

        $progress = $this->updateProgressAction->execute($financialGoal, $request->all());
        
        return back()->with('success', 'Progress updated successfully!');
    }
}
