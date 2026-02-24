<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use App\Models\Exam;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private readonly ReportService $reportService)
    {
    }

    public function index(Request $request)
    {
        return view('reports.index', [
            'batches' => Batch::all(),
            'courses' => Course::all(),
            'exams' => Exam::all(),
            'studentList' => $this->reportService->studentList($request->all()),
            'passRates' => $this->reportService->passRateReport($request->all()),
            'topPerformers' => $this->reportService->topPerformers($request->all()),
            'lowAttendance' => $this->reportService->lowAttendance($request->all()),
        ]);
    }

    public function exportStudents(Request $request)
    {
        $rows = $this->reportService->studentList($request->all())->map(fn ($r) => (array) $r);
        return $this->reportService->streamCsv('students_by_batch_course.csv', array_keys($rows->first() ?? []), $rows);
    }

    public function exportPassRates(Request $request)
    {
        $rows = $this->reportService->passRateReport($request->all())->map(fn ($r) => (array) $r);
        return $this->reportService->streamCsv('pass_rates.csv', array_keys($rows->first() ?? []), $rows);
    }
}
