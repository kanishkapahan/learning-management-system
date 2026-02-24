<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceMarkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canDo('attendance.mark') ?? false;
    }

    public function rules(): array
    {
        return [
            'batch_id' => ['required', 'exists:batches,id'],
            'date' => ['required', 'date'],
            'rows' => ['required', 'array', 'min:1'],
            'rows.*.student_id' => ['required', 'exists:students,id'],
            'rows.*.status' => ['required', 'in:present,absent,late'],
        ];
    }
}
