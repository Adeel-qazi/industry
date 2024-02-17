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
            'start_date' => 'nullable|date',
            'close_date' => 'nullable|date',
            'active' => 'nullable|boolean',
        ];
    }

    public function messages()
    {
        return [
            'plan_name.min' => 'The plan name field must be at least :min characters long',
            'price.numeric' => 'The price field must be numeric',
            'start_date.date' => 'The start date field must be a valid date',
            'close_date.date' => 'The close date field must be a valid date',
            'active.in' => 'The active field must be one of the allowed values:',
        ];


    }
}
