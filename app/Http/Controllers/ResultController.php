<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResultStoreRequest;
use App\Models\Exam;
use App\Models\Result;
use App\Services\ResultService;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function __construct(private readonly ResultService $resultService)
    {
    }

    public function index(Request $request)
    {
        $results = $this->resultService->paginate($request->only(['exam_id', 'status']));
        return view('results.index', ['results' => $results, 'exams' => Exam::orderBy('exam_title')->get()]);
    }

    public function create()
    {
        return view('results.form', [
            'exams' => Exam::orderBy('exam_title')->get(),
            'students' => \App\Models\Student::orderBy('student_no')->get(),
        ]);
    }

    public function store(ResultStoreRequest $request)
    {
        $this->resultService->saveDraft($request->validated(), $request->user());
        return redirect()->route('results.index')->with('success', 'Result saved as draft.');
    }

    public function approve(Result $result, Request $request)
    {
        $this->resultService->approve($result, $request->user());
        return back()->with('success', 'Result approved.');
    }

    public function publish(Result $result, Request $request)
    {
        $this->resultService->publish($result, $request->user());
        return back()->with('success', 'Result published.');
    }

    public function bulkUploadForm()
    {
        return view('results.import');
    }

    public function bulkUpload(Request $request)
    {
        $request->validate(['csv_file' => ['required', 'file', 'mimes:csv,txt']]);
        $report = app(\App\Services\ResultService::class)->bulkUploadCsv($request->file('csv_file')->getRealPath(), $request->user());
        return back()->with('success', "Processed {$report['success']} rows.")->with('import_report', $report);
    }

    public function recalculate(Exam $exam, Request $request)
    {
        $count = app(\App\Services\ResultService::class)->recalculateExamGrades($exam->load('results'), $request->user());
        return back()->with('success', "Recalculated {$count} results.");
    }
}
