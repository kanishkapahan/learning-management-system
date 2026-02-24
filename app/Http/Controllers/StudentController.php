<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Models\Student;
use App\Services\StudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct(private readonly StudentService $studentService)
    {
    }

    public function index(Request $request)
    {
        $students = $this->studentService->paginate(['search' => $request->string('search')->toString()], 15);
        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.form', ['student' => new Student()]);
    }

    public function store(StudentStoreRequest $request)
    {
        $this->studentService->create($request->validated(), $request->file('profile_picture'), $request->user());
        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        return view('students.form', compact('student'));
    }

    public function update(StudentUpdateRequest $request, Student $student)
    {
        $this->studentService->update($student, $request->validated(), $request->file('profile_picture'), $request->user());
        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student, Request $request)
    {
        $student->delete();
        app(\App\Services\ActivityLogService::class)->log('students.delete', 'Student soft deleted', $student, $request->user());
        return back()->with('success', 'Student deleted.');
    }

    public function restore(int $id, Request $request)
    {
        $student = Student::withTrashed()->findOrFail($id);
        $student->restore();
        app(\App\Services\ActivityLogService::class)->log('students.restore', 'Student restored', $student, $request->user());
        return back()->with('success', 'Student restored.');
    }

    public function importForm()
    {
        return view('students.import');
    }

    public function importCsv(Request $request)
    {
        $request->validate(['csv_file' => ['required', 'file', 'mimes:csv,txt']]);
        $report = $this->studentService->importCsv($request->file('csv_file')->getRealPath(), $request->user());

        return back()->with('success', "Imported {$report['success']} students.")->with('import_report', $report);
    }
}
