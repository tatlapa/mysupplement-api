<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
            'hostname' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'The email address is required.',
            'email.string' => 'The email address must be a string.',
            'email.email' => 'The email address must be a valid email address.',
            'email.max' => 'The email address must not exceed 255 characters.',

            'password.required' => 'The password is required.',
            'password.string' => 'The password must be a string.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
