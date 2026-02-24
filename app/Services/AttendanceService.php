<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Batch;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function __construct(private readonly ActivityLogService $activityLogService)
    {
    }

    public function markForBatch(Batch $batch, string $date, array $rows, User $actor): void
    {
        DB::transaction(function () use ($batch, $date, $rows, $actor): void {
            foreach ($rows as $row) {
                Attendance::query()->updateOrCreate(
                    ['student_id' => $row['student_id'], 'batch_id' => $batch->id, 'date' => $date],
                    ['status' => $row['status'], 'marked_by' => $actor->id]
                );
            }
            $this->activityLogService->log('attendance.mark', 'Attendance marked', $batch, $actor, ['date' => $date]);
        });
    }

    public function attendancePercentage(int $studentId, ?int $batchId = null): float
    {
        $q = Attendance::query()->where('student_id', $studentId);
        if ($batchId) {
            $q->where('batch_id', $batchId);
        }
        $total = (clone $q)->count();
        if ($total === 0) {
            return 0;
        }
        $present = (clone $q)->whereIn('status', ['present', 'late'])->count();
        return round(($present / $total) * 100, 2);
    }
}
