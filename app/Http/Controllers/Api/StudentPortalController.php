<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Result;
use Illuminate\Http\Request;

class StudentPortalController extends Controller
{
    public function results(Request $request)
    {
        $studentId = $request->user()->studentProfile?->id;
        abort_unless($studentId, 403);

        $rows = Result::query()
            ->with(['exam.course', 'exam.batch'])
            ->where('student_id', $studentId)
            ->where('status', 'published')
            ->get();

        return response()->json($rows);
    }

    public function attendance(Request $request)
    {
        $studentId = $request->user()->studentProfile?->id;
        abort_unless($studentId, 403);

        $rows = Attendance::query()->where('student_id', $studentId)->orderByDesc('date')->get();
        return response()->json($rows);
    }
}
