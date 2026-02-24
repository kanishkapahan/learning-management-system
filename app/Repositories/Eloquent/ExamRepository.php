<?php

namespace App\Repositories\Eloquent;

use App\Models\Exam;
use App\Repositories\Contracts\ExamRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExamRepository implements ExamRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return Exam::query()
            ->with(['course', 'batch', 'components'])
            ->when($filters['course_id'] ?? null, fn ($q, $courseId) => $q->where('course_id', $courseId))
            ->when($filters['batch_id'] ?? null, fn ($q, $batchId) => $q->where('batch_id', $batchId))
            ->orderBy('exam_date', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function create(array $data): Exam
    {
        $components = $data['components'] ?? [];
        unset($data['components']);

        $exam = Exam::query()->create($data);
        if ($components) {
            $exam->components()->create($components);
        }

        return $exam->load(['course', 'batch', 'components']);
    }

    public function update(Exam $exam, array $data): Exam
    {
        $components = $data['components'] ?? null;
        unset($data['components']);

        $exam->update($data);
        if (is_array($components)) {
            $exam->components()->updateOrCreate(['exam_id' => $exam->id], $components);
        }

        return $exam->load(['course', 'batch', 'components']);
    }
}
