<?php

namespace App\Services;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Exam;
use App\Models\User;
use App\Repositories\Contracts\ExamRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ExamService
{
    public function __construct(
        private readonly ExamRepositoryInterface $exams,
        private readonly ActivityLogService $activityLogService
    ) {
    }

    public function paginate(array $filters = [], int $perPage = 15)
    {
        return $this->exams->paginate($filters, $perPage);
    }

    public function create(array $data, User $actor): Exam
    {
        return DB::transaction(function () use ($data, $actor): Exam {
            $this->validateBusinessRules($data);
            $this->ensureLecturerCanManage($actor, (int) $data['course_id']);
            $data['created_by'] = $actor->id;

            $exam = $this->exams->create($data);
            $this->activityLogService->log('exams.create', 'Exam created', $exam, $actor);
            return $exam;
        });
    }

    public function update(Exam $exam, array $data, User $actor): Exam
    {
        return DB::transaction(function () use ($exam, $data, $actor): Exam {
            $payload = array_merge($exam->toArray(), $data);
            $this->validateBusinessRules($payload);
            $this->ensureLecturerCanManage($actor, (int) $payload['course_id']);

            $exam = $this->exams->update($exam, $data);
            $this->activityLogService->log('exams.update', 'Exam updated', $exam, $actor);
            return $exam;
        });
    }

    private function validateBusinessRules(array $data): void
    {
        $batch = Batch::query()->findOrFail((int) $data['batch_id']);
        if (Carbon::parse($data['exam_date'])->lt($batch->start_date)) {
            throw ValidationException::withMessages(['exam_date' => 'Exam date cannot be before batch start date.']);
        }
        if ((float) $data['pass_marks'] >= (float) $data['total_marks']) {
            throw ValidationException::withMessages(['pass_marks' => 'Pass marks must be less than total marks.']);
        }
    }

    private function ensureLecturerCanManage(User $user, int $courseId): void
    {
        if (! $user->hasRole('LECTURER')) {
            return;
        }

        $lecturerId = $user->lecturerProfile?->id;
        $allowed = Course::query()->whereKey($courseId)->where('lecturer_id', $lecturerId)->exists();

        if (! $allowed) {
            throw ValidationException::withMessages(['course_id' => 'Only assigned lecturer can manage this course exam.']);
        }
    }
}
