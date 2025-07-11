<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function rules(): array
    {
        $user = $this->user();
        $hasPassword = !empty($user->password);

        $rules = [
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string'],
        ];

        // Si l'utilisateur a un mot de passe, exiger le mot de passe actuel
        if ($hasPassword) {
            $rules['current_password'] = ['required', 'string', 'current_password'];
        }

        return $rules;
    }

    public function messages(): array
    {
        $user = $this->user();
        $hasPassword = !empty($user->password);

        $messages = [
            'password.required' => 'The new password is required.',
            'password.string' => 'The new password must be a string.',
            'password.min' => 'The new password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',

            'password_confirmation.required' => 'The password confirmation is required.',
            'password_confirmation.string' => 'The password confirmation must be a string.',
        ];

        // Messages pour current_password seulement si l'utilisateur a un mot de passe
        if ($hasPassword) {
            $messages['current_password.required'] = 'The current password is required.';
            $messages['current_password.string'] = 'The current password must be a string.';
            $messages['current_password.current_password'] = 'The current password is incorrect.';
        }

        return $messages;
    }

    public function authorize(): bool
    {
        return true;
    }
}
