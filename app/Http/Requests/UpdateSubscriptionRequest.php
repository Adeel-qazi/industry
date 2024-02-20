<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'plan_name' => 'nullable|min:3',
            'price' => 'nullable|numeric',
            'duration' => 'nullable|numeric',
            'duration_unit' => 'nullable|in:days,weeks,months,years',
            'active' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'plan_name.min' => 'The plan name field must be at least :min characters long',
            'price.numeric' => 'The price field must be numeric',
            'duration.numeric' => 'The duration field must be numeric',
            'duration_unit.in' => 'The duration unit field must be one of the allowed values: days, weeks, months, years.',
            'active.in' => 'The active field must be one of the allowed values: true, false.',
        ];

    }
}
