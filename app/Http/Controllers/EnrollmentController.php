<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulkEnrollmentRequest;
use App\Models\Batch;
use App\Models\Student;
use App\Services\EnrollmentService;

class EnrollmentController extends Controller
{
    public function __construct(private readonly EnrollmentService $enrollmentService)
    {
    }

    public function create()
    {
        return view('enrollments.bulk', [
            'batches' => \App\Models\Batch::with('course')->get(),
            'students' => Student::orderBy('student_no')->get(),
        ]);
    }

    public function store(BulkEnrollmentRequest $request)
    {
        $batch = Batch::findOrFail($request->integer('batch_id'));
        $count = $this->enrollmentService->bulkEnroll(
            $batch,
            $request->input('student_ids', []),
            $request->string('enrollment_status')->toString(),
            $request->user()
        );

        return redirect()->route('enrollments.create')->with('success', "{$count} students enrolled.");
    }
}
