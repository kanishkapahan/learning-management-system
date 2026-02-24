<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SettingsUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole('SUPER_ADMIN') ?? false;
    }

    public function rules(): array
    {
        return [
            'academic_year' => ['required', 'string', 'max:20'],
            'enable_self_registration' => ['nullable', 'boolean'],
            'grade_thresholds' => ['required', 'array', 'min:1'],
            'grade_thresholds.*.grade' => ['required', 'string', 'max:2'],
            'grade_thresholds.*.min' => ['required', 'numeric', 'between:0,100'],
            'system_logo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
