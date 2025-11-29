<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;

class DeleteUserRequest extends FormRequest {
    public function rules(): array {
        return ['password' => ['required', 'string', 'current_password']];
    }

    public function messages(): array {
        return [
            'password.required' => 'The password is required.',
            'password.string' => 'The password must be a string.',
        ];
    }

    public function authorize(): bool {
        return true;
    }
}