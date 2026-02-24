<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canDo('students.update') ?? false;
    }

    public function rules(): array
    {
        $student = $this->route('student');
        $id = is_object($student) ? $student->id : $student;

        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', Rule::unique('students', 'email')->ignore($id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'dob' => ['nullable', 'date'],
            'address' => ['nullable', 'string'],
            'gender' => ['nullable', 'in:male,female,other'],
            'status' => ['required', 'in:active,inactive,suspended'],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
