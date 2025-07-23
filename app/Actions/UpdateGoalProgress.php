<?php

namespace App\Actions;

use App\Models\FinancialGoal;
use App\Services\FinancialGoalService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UpdateGoalProgress
{
    public function __construct(
        private FinancialGoalService $goalService
    ) {}

    public function execute(FinancialGoal $goal, array $data)
    {
        $this->validate($data);
        
        // Check if progress for this month/year already exists
        $existingProgress = $goal->progress()
            ->where('month', $data['month'])
            ->where('year', $data['year'])
            ->first();
            
        if ($existingProgress) {
            return $this->goalService->updateProgress($existingProgress, $data);
        }
        
        return $this->goalService->addProgress($goal, $data);
    }

    private function validate(array $data): void
    {
        $validator = Validator::make($data, [
            'current_amount' => 'required|numeric|min:0',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2030',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
} 