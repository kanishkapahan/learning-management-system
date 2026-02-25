<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Exam;
use App\Models\Lecturer;
use App\Models\Result;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function summary(): array
    {
        return [
            'students' => Student::query()->count(),
            'lecturers' => Lecturer::query()->count(),
            'courses' => Course::query()->count(),
            'batches' => Batch::query()->count(),
            'enrollments' => DB::table('enrollments')->count(),
            'exams' => Exam::query()->count(),
            'published_results' => Result::query()->where('status', 'published')->count(),
        ];
    }

    public function charts(): array
    {
        return [
            'monthlyEnrollments' => DB::table('enrollments')
                ->selectRaw('strftime(\'%Y-%m\', enrolled_at) as month, COUNT(*) as total')
                ->groupBy('month')->orderBy('month')->get(),
            'averageMarksPerCourse' => Result::query()
                ->join('exams', 'exams.id', '=', 'results.exam_id')
                ->join('courses', 'courses.id', '=', 'exams.course_id')
                ->selectRaw('courses.title as label, ROUND(AVG(results.marks),2) as value')
                ->groupBy('courses.id', 'courses.title')->orderBy('courses.title')->get(),
            'passRatePerBatch' => DB::table('vw_exam_pass_rates')
                ->join('batches', 'batches.id', '=', 'vw_exam_pass_rates.batch_id')
                ->selectRaw('batches.batch_code as label, ROUND(AVG(vw_exam_pass_rates.pass_rate),2) as value')
                ->groupBy('batches.id', 'batches.batch_code')->orderBy('batches.batch_code')->get(),
            'resultStatusDistribution' => Result::query()
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')->get(),
        ];
    }

    public function topPerformers(int $limit = 5): \Illuminate\Support\Collection
    {
        return Result::query()
            ->join('students', 'students.id', '=', 'results.student_id')
            ->where('results.status', 'published')
            ->selectRaw('students.id, students.first_name, students.last_name, students.student_no, ROUND(AVG(results.marks),2) as avg_marks, COUNT(results.id) as exam_count')
            ->groupBy('students.id', 'students.first_name', 'students.last_name', 'students.student_no')
            ->orderByDesc('avg_marks')
            ->limit($limit)
            ->get();
    }

    public function systemStats(): array
    {
        return [
            'active_users' => User::query()->where('status', 'active')->count(),
            'total_users' => User::query()->count(),
            'pending_results' => Result::query()->where('status', 'draft')->count(),
            'upcoming_exams' => Exam::query()->where('exam_date', '>=', now()->toDateString())->count(),
        ];
    }

    public function recentActivities(int $limit = 10)
    {
        return ActivityLog::query()->latest()->limit($limit)->get();
    }
}
