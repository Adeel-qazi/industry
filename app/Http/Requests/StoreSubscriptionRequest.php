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
            'start_date' => 'required|date',
            'close_date' => 'required|date',
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
            'start_date.required' => 'The start date field must be required',
            'start_date.date' => 'The start date field must be a valid date',
            'close_date.required' => 'The close date field must be required',
            'close_date.date' => 'The close date field must be a valid date',
            'active.required' => 'The active field must be required',
            'active.in' => 'The active field must be one of the allowed values: allowed_value1, allowed_value2',
        ];


    }
}
