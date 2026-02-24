<?php

namespace App\Repositories\Eloquent;

use App\Models\Result;
use App\Repositories\Contracts\ResultRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ResultRepository implements ResultRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Result::query()
            ->with(['student', 'exam.course', 'exam.batch'])
            ->when($filters['exam_id'] ?? null, fn ($q, $examId) => $q->where('exam_id', $examId))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function upsertByStudentAndExam(array $payload): Result
    {
        return Result::query()->updateOrCreate(
            ['student_id' => $payload['student_id'], 'exam_id' => $payload['exam_id']],
            $payload
        )->load(['student', 'exam']);
    }
}
