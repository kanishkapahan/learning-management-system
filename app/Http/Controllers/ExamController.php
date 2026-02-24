<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamStoreRequest;
use App\Http\Requests\ExamUpdateRequest;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Exam;
use App\Services\ExamService;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function __construct(private readonly ExamService $examService)
    {
    }

    public function index(Request $request)
    {
        $exams = $this->examService->paginate($request->only(['course_id', 'batch_id']));
        return view('exams.index', ['exams' => $exams, 'courses' => Course::all(), 'batches' => Batch::all()]);
    }

    public function create()
    {
        return view('exams.form', ['exam' => new Exam(), 'courses' => Course::all(), 'batches' => Batch::all()]);
    }

    public function store(ExamStoreRequest $request)
    {
        $this->examService->create($request->validated(), $request->user());
        return redirect()->route('exams.index')->with('success', 'Exam created.');
    }

    public function edit(Exam $exam)
    {
        return view('exams.form', ['exam' => $exam->load('components'), 'courses' => Course::all(), 'batches' => Batch::all()]);
    }

    public function update(ExamUpdateRequest $request, Exam $exam)
    {
        $this->examService->update($exam, $request->validated(), $request->user());
        return redirect()->route('exams.index')->with('success', 'Exam updated.');
    }

    public function destroy(Exam $exam, Request $request)
    {
        $exam->delete();
        app(\App\Services\ActivityLogService::class)->log('exams.delete', 'Exam deleted', $exam, $request->user());
        return back()->with('success', 'Exam deleted.');
    }

    public function timetable()
    {
        $rows = Exam::with(['course', 'batch'])->orderBy('exam_date')->orderBy('start_time')->get();
        return view('exams.timetable', compact('rows'));
    }
}
