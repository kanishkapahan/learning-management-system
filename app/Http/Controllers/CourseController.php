<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lecturer;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return view('courses.index', ['courses' => Course::with('lecturer')->latest()->paginate(15)]);
    }

    public function create()
    {
        return view('courses.form', ['course' => new Course(), 'lecturers' => Lecturer::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_code' => ['required', 'string', 'max:50', 'unique:courses,course_code'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'credits' => ['required', 'integer', 'min:1', 'max:10'],
            'level' => ['required', 'integer', 'min:1', 'max:10'],
            'semester' => ['required', 'integer', 'min:1', 'max:12'],
            'lecturer_id' => ['nullable', 'exists:lecturers,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);
        $course = Course::create($data);
        app(\App\Services\ActivityLogService::class)->log('courses.create', 'Course created', $course, $request->user());
        return redirect()->route('courses.index')->with('success', 'Course created.');
    }

    public function edit(Course $course)
    {
        return view('courses.form', ['course' => $course, 'lecturers' => Lecturer::orderBy('name')->get()]);
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'course_code' => ['required', 'string', 'max:50', 'unique:courses,course_code,' . $course->id],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'credits' => ['required', 'integer', 'min:1', 'max:10'],
            'level' => ['required', 'integer', 'min:1', 'max:10'],
            'semester' => ['required', 'integer', 'min:1', 'max:12'],
            'lecturer_id' => ['nullable', 'exists:lecturers,id'],
            'status' => ['required', 'in:active,inactive'],
        ]);
        $course->update($data);
        app(\App\Services\ActivityLogService::class)->log('courses.update', 'Course updated', $course, $request->user());
        return redirect()->route('courses.index')->with('success', 'Course updated.');
    }

    public function destroy(Course $course, Request $request)
    {
        $course->delete();
        app(\App\Services\ActivityLogService::class)->log('courses.delete', 'Course deleted', $course, $request->user());
        return back()->with('success', 'Course deleted.');
    }
}
