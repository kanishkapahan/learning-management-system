<?php

namespace App\Http\Requests;

class ExamUpdateRequest extends ExamStoreRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canDo('exams.update') ?? false;
    }
}
