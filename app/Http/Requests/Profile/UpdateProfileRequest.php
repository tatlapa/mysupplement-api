<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user()->id)
            ],
            'name' => [
                'required',
                'string',
                Rule::unique('users')->ignore($this->user()->id)
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'The email address is required.',
            'email.string' => 'The email address must be a string.',
            'email.email' => 'The email address must be a valid email address.',
            'email.max' => 'The email address must not exceed 255 characters.',
            'email.unique' => 'This email address is already in use.',

            'name.required' => 'The name is required.',
            'name.string' => 'The name must be a string.',
            'name.unique' => 'This name is already in use.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
