<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.required' => 'The current password is required.',
            'current_password.string' => 'The current password must be a string.',
            'current_password.current_password' => 'The current password is incorrect.',

            'password.required' => 'The new password is required.',
            'password.string' => 'The new password must be a string.',
            'password.min' => 'The new password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',

            'password_confirmation.required' => 'The password confirmation is required.',
            'password_confirmation.string' => 'The password confirmation must be a string.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
