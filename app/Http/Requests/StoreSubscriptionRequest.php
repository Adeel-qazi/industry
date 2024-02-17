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
           'start_date' => 'required',
           'close_date' => 'required',
           'active'     => 'required|in:'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title field must be required',
            'title.min' => 'The title field must be at least :min characters long',
            'description.min' => 'The description field must be at least :min characters long',
            'price.required' => 'The price field must be required',
            'duration.required' => 'The duration field must be required',
            'duration_unit.required' => 'The duration_unit field must be required',
        ];


    }
}
