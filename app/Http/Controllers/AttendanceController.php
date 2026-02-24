<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceMarkRequest;
use App\Models\Batch;
use App\Services\AttendanceService;
use App\Services\ReportService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(
        private readonly AttendanceService $attendanceService,
        private readonly ReportService $reportService
    ) {
    }

    public function create()
    {
        return view('attendance.mark', ['batches' => Batch::with('students')->get()]);
    }

    public function store(AttendanceMarkRequest $request)
    {
        $batch = Batch::findOrFail($request->integer('batch_id'));
        $this->attendanceService->markForBatch($batch, $request->date('date')->toDateString(), $request->input('rows'), $request->user());
        return back()->with('success', 'Attendance saved.');
    }

    public function report(Request $request)
    {
        $rows = $this->reportService->lowAttendance($request->only(['batch_id']));
        if ($request->boolean('export')) {
            return $this->reportService->streamCsv('low_attendance.csv', ['student_no', 'student_name', 'batch_code', 'attendance_percentage'], $rows);
        }
        return view('attendance.report', ['rows' => $rows, 'batches' => Batch::all()]);
    }
}
