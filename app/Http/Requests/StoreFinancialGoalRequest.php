<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFinancialGoalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'currency_id' => 'required|exists:currencies,id',
            'target_amount' => 'required|numeric|min:0.01',
            'start_amount' => 'nullable|numeric|min:0',
            'expected_amount' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'year' => 'required|integer|min:2020|max:2030',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'A goal name is required.',
            'name.max' => 'The goal name cannot exceed 255 characters.',
            'category_id.required' => 'Please select a category for your goal.',
            'category_id.exists' => 'Please select a valid category.',
            'currency_id.required' => 'Please select a currency.',
            'currency_id.exists' => 'Please select a valid currency.',
            'target_amount.required' => 'A target amount is required.',
            'target_amount.numeric' => 'The target amount must be a valid number.',
            'target_amount.min' => 'The target amount must be greater than 0.',
            'start_amount.numeric' => 'The starting amount must be a valid number.',
            'start_amount.min' => 'The starting amount cannot be negative.',
            'expected_amount.numeric' => 'The expected amount must be a valid number.',
            'expected_amount.min' => 'The expected amount cannot be negative.',
            'description.max' => 'The description cannot exceed 1000 characters.',
            'year.required' => 'A year is required.',
            'year.integer' => 'The year must be a valid number.',
            'year.min' => 'The year must be 2020 or later.',
            'year.max' => 'The year cannot be later than 2030.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'goal name',
            'target_amount' => 'target amount',
            'start_amount' => 'starting amount',
            'expected_amount' => 'expected amount',
        ];
    }
}
