<?php

namespace App\Http\Requests\Advicer;

use Illuminate\Foundation\Http\FormRequest;

class AdvicerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'age'              => 'required|integer|min:1',
            'gender'           => 'required|string|in:male,female,other',
            'goals'            => 'required|array|min:1',
            'goals.*'          => 'string|max:255',
            'healthIssues'     => 'nullable|array',
            'healthIssues.*'   => 'string|max:255',
            'sleepQuality'     => 'required|string|max:100',
            'stressLevel'      => 'required|string|max:100',
        ];
    }
}
