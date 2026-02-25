<?php

namespace App\Services;

use App\Models\Result;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportService
{
    public function studentList(array $filters = [])
    {
        return DB::table('enrollments')
            ->join('students', 'students.id', '=', 'enrollments.student_id')
            ->join('batches', 'batches.id', '=', 'enrollments.batch_id')
            ->join('courses', 'courses.id', '=', 'enrollments.course_id')
            ->when($filters['batch_id'] ?? null, fn($q, $id) => $q->where('batches.id', $id))
            ->when($filters['course_id'] ?? null, fn($q, $id) => $q->where('courses.id', $id))
            ->selectRaw('students.student_no, students.first_name, students.last_name, students.email, batches.batch_code, courses.title as course_title')
            ->orderBy('students.student_no')
            ->get();
    }

    public function passRateReport(array $filters = [])
    {
        return DB::table('vw_exam_pass_rates')
            ->join('exams', 'exams.id', '=', 'vw_exam_pass_rates.exam_id')
            ->join('batches', 'batches.id', '=', 'vw_exam_pass_rates.batch_id')
            ->when($filters['batch_id'] ?? null, fn($q, $id) => $q->where('batches.id', $id))
            ->when($filters['exam_id'] ?? null, fn($q, $id) => $q->where('exams.id', $id))
            ->select('exams.exam_title', 'batches.batch_code', 'vw_exam_pass_rates.total_results', 'vw_exam_pass_rates.passed_count', 'vw_exam_pass_rates.pass_rate')
            ->get();
    }

    public function topPerformers(array $filters = [])
    {
        return Result::query()
            ->with(['student', 'exam.course', 'exam.batch'])
            ->when($filters['course_id'] ?? null, function (Builder $q, $courseId): void {
                $q->whereHas('exam', fn($x) => $x->where('course_id', $courseId));
            })
            ->when($filters['batch_id'] ?? null, function (Builder $q, $batchId): void {
                $q->whereHas('exam', fn($x) => $x->where('batch_id', $batchId));
            })
            ->orderByDesc('marks')
            ->limit(20)
            ->get();
    }

    public function lowAttendance(array $filters = [])
    {
        return DB::table('attendances')
            ->join('students', 'students.id', '=', 'attendances.student_id')
            ->join('batches', 'batches.id', '=', 'attendances.batch_id')
            ->when($filters['batch_id'] ?? null, fn($q, $id) => $q->where('batches.id', $id))
            ->selectRaw(
                DB::getDriverName() === 'sqlite'
                    ? 'students.student_no, (students.first_name || \' \' || students.last_name) as student_name, batches.batch_code,
                       ROUND((SUM(CASE WHEN attendances.status IN (\'present\',\'late\') THEN 1 ELSE 0 END) * 1.0 / COUNT(*))*100,2) as attendance_percentage'
                    : 'students.student_no, CONCAT(students.first_name, \' \', students.last_name) as student_name, batches.batch_code,
                       ROUND((SUM(CASE WHEN attendances.status IN (\'present\',\'late\') THEN 1 ELSE 0 END) / COUNT(*))*100,2) as attendance_percentage'
            )
            ->groupBy('students.student_no', 'students.first_name', 'students.last_name', 'batches.batch_code')
            ->having('attendance_percentage', '<', 80)
            ->orderBy('attendance_percentage')
            ->get();
    }

    public function streamCsv(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            foreach ($rows as $row) {
                fputcsv($handle, is_array($row) ? $row : (array) $row);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
