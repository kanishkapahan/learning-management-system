<?php

namespace App\Services;

use App\Models\Batch;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    public function __construct(private readonly ActivityLogService $activityLogService)
    {
    }

    public function bulkEnroll(Batch $batch, array $studentIds, string $status, $actor = null): int
    {
        $count = 0;

        DB::transaction(function () use ($batch, $studentIds, $status, $actor, &$count): void {
            foreach ($studentIds as $studentId) {
                DB::table('enrollments')->updateOrInsert(
                    ['student_id' => $studentId, 'batch_id' => $batch->id, 'course_id' => $batch->course_id],
                    [
                        'enrolled_at' => Carbon::now(),
                        'enrollment_status' => $status,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]
                );
                $count++;
            }

            $this->activityLogService->log('enrollments.bulk', 'Bulk enrollment completed', $batch, $actor, ['count' => $count]);
        });

        return $count;
    }
}
