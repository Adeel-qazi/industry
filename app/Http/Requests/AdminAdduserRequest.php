<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class AdminAdduserRequest extends FormRequest
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
            'name'  =>'required|string|min:3',
            'email' => 'required|email|unique:users',
            'password' => ['required','confirmed',Password::min(5)],
            'role' => 'required|in:user',
            'email_verified' => 'required|boolean'
         ];
    }


    public function messages()
{
    return [
        'name.required' => 'The :attribute field is required',
        'name.string' => 'The :attribute field must be a string',
        'name.min' => 'The :attribute field must be at least :min characters',
        'email.required' => 'The :attribute field is required',
        'email.email' => 'The :attribute field must be a valid email address',
        'email.unique' => 'The :attribute is already taken',
        'password.required' => 'The :attribute field is required',
        'password.min' => 'The :attribute field must be at least :min characters',
        'role.required' => 'The :attribute field is required',
        'role.in' => 'The selected :attribute is invalid',
        'email_verified.required' => 'The :attribute field is required',
        'email_verified.boolean' => 'The :attribute field must be a boolean either true or false',
    ];
}


    public function attributes(): array
    {
        return [
            'name' => 'full name',
            'email' => 'email address',
            'password' => 'password',
            'role' => 'user role',
            'email_verified' => 'email verification status',
        ];
    }
}
