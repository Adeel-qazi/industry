<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
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
            'plan_name' => 'required|min:3',
            'price' => 'required|numeric',
            'duration' => 'required|numeric',
            'duration_unit' => 'required|in:days,weeks,months,years',
            // 'active' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'plan_name.required' => 'The plan name field must be required',
            'plan_name.min' => 'The plan name field must be at least :min characters long',
            'price.required' => 'The price field must be required',
            'price.numeric' => 'The price field must be numeric',
            'duration.required' => 'The duration field is required.',
            'duration.numeric' => 'The duration field must be a numeric number.',
            'duration_unit.required' => 'The duration unit field is required.',
            'duration_unit.in' => 'The duration unit field must be one of the allowed values: days, weeks, months, years.',
            'active.required' => 'The active field must be required',
            'active.in' => 'The active field must be one of the allowed values: allowed_value1, allowed_value2',
        ];


    }
}
