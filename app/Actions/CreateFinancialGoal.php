<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Currency;
use App\Models\Category;
use App\Services\FinancialGoalService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateFinancialGoal
{
    public function __construct(
        private FinancialGoalService $goalService
    ) {}

    public function execute(User $user, array $data)
    {
        $this->validate($data);
        
        return $this->goalService->createGoal($user, $data);
    }

    private function validate(array $data): void
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_amount' => 'required|numeric|min:0',
            'target_amount' => 'required|numeric|min:0|gt:start_amount',
            'expected_amount' => 'nullable|numeric|min:0',
            'year' => 'required|integer|min:2020|max:2030',
            'category_id' => 'required|exists:categories,id',
            'currency_id' => 'required|exists:currencies,id',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
} 