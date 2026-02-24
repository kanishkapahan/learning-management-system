<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canDo('exams.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'exam_title' => ['required', 'string', 'max:255'],
            'course_id' => ['required', 'exists:courses,id'],
            'batch_id' => ['required', 'exists:batches,id'],
            'exam_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'total_marks' => ['required', 'numeric', 'min:1'],
            'pass_marks' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:draft,scheduled,held'],
            'components' => ['nullable', 'array'],
            'components.mcq_marks' => ['nullable', 'numeric', 'min:0'],
            'components.theory_marks' => ['nullable', 'numeric', 'min:0'],
            'components.practical_marks' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
