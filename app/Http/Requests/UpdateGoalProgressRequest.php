<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateGoalProgressRequest extends FormRequest
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
            'current_amount' => 'required|numeric|min:0',
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020|max:2030',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'current_amount.required' => 'A progress amount is required.',
            'current_amount.numeric' => 'The progress amount must be a valid number.',
            'current_amount.min' => 'The progress amount cannot be negative.',
            'month.required' => 'A month is required.',
            'month.integer' => 'The month must be a valid number.',
            'month.between' => 'The month must be between 1 and 12.',
            'year.required' => 'A year is required.',
            'year.integer' => 'The year must be a valid number.',
            'year.min' => 'The year must be 2020 or later.',
            'year.max' => 'The year cannot be later than 2030.',
            'notes.max' => 'The notes cannot exceed 500 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'current_amount' => 'progress amount',
            'month' => 'month',
            'year' => 'year',
            'notes' => 'notes',
        ];
    }
}
