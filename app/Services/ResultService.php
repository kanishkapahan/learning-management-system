<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\Result;
use App\Models\User;
use App\Repositories\Contracts\ResultRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ResultService
{
    public function __construct(
        private readonly ResultRepositoryInterface $results,
        private readonly GradeService $gradeService,
        private readonly ActivityLogService $activityLogService
    ) {
    }

    public function paginate(array $filters = [], int $perPage = 15)
    {
        return $this->results->paginate($filters, $perPage);
    }

    public function saveDraft(array $payload, User $actor): Result
    {
        $exam = Exam::query()->findOrFail($payload['exam_id']);
        if ((float) $payload['marks'] > (float) $exam->total_marks) {
            throw \Illuminate\Validation\ValidationException::withMessages(['marks' => 'Marks cannot exceed total marks.']);
        }

        $calc = $this->gradeService->calculate((float) $payload['marks'], (float) $exam->total_marks, (float) $exam->pass_marks);

        $result = $this->results->upsertByStudentAndExam([
            'student_id' => $payload['student_id'],
            'exam_id' => $payload['exam_id'],
            'marks' => $payload['marks'],
            'grade' => $calc['grade'],
            'pass_fail' => $calc['pass_fail'],
            'remarks' => $payload['remarks'] ?? null,
            'entered_by' => $actor->id,
            'status' => 'draft',
        ]);

        $this->activityLogService->log('results.draft', 'Result saved as draft', $result, $actor);
        return $result;
    }

    public function approve(Result $result, User $actor): Result
    {
        $result->update(['status' => 'approved', 'approved_by' => $actor->id]);
        $this->activityLogService->log('results.approve', 'Result approved', $result, $actor);
        return $result->refresh();
    }

    public function publish(Result $result, User $actor): Result
    {
        $result->update([
            'status' => 'published',
            'approved_by' => $result->approved_by ?: $actor->id,
            'published_at' => now(),
        ]);
        $this->activityLogService->log('results.publish', 'Result published', $result, $actor);
        return $result->refresh();
    }

    public function bulkUploadCsv(string $path, User $actor): array
    {
        $handle = fopen($path, 'r');
        $headers = null;
        $success = 0;
        $errors = [];
        $lineNo = 0;

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                $lineNo++;
                if ($headers === null) {
                    $headers = array_map('trim', $row);
                    continue;
                }
                $data = array_combine($headers, $row);

                try {
                    if (! is_array($data)) {
                        throw new \RuntimeException('Invalid CSV row.');
                    }
                    $this->saveDraft([
                        'student_id' => (int) ($data['student_id'] ?? 0),
                        'exam_id' => (int) ($data['exam_id'] ?? 0),
                        'marks' => (float) ($data['marks'] ?? 0),
                        'remarks' => $data['remarks'] ?? null,
                    ], $actor);
                    $success++;
                } catch (\Throwable $e) {
                    $errors[] = ['line' => $lineNo, 'error' => $e->getMessage()];
                }
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        } finally {
            fclose($handle);
        }

        return compact('success', 'errors');
    }

    public function recalculateExamGrades(Exam $exam, User $actor): int
    {
        $count = 0;
        foreach ($exam->results as $result) {
            $calc = $this->gradeService->calculate((float) $result->marks, (float) $exam->total_marks, (float) $exam->pass_marks);
            $result->update([
                'grade' => $calc['grade'],
                'pass_fail' => $calc['pass_fail'],
            ]);
            $count++;
        }

        $this->activityLogService->log('results.recalculate', 'Grades recalculated', $exam, $actor, ['count' => $count]);
        return $count;
    }
}
