<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    public function index()
    {
        return view('lecturers.index', ['lecturers' => Lecturer::latest()->paginate(15)]);
    }

    public function create()
    {
        return view('lecturers.form', ['lecturer' => new Lecturer()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_no' => ['nullable', 'string', 'max:50', 'unique:lecturers,employee_no'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:lecturers,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'department' => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);
        $data['employee_no'] = $data['employee_no'] ?: 'EMP-' . now()->format('Y') . '-' . str_pad((string) (Lecturer::withTrashed()->count() + 1), 4, '0', STR_PAD_LEFT);
        $lecturer = Lecturer::create($data);
        app(\App\Services\ActivityLogService::class)->log('lecturers.create', 'Lecturer created', $lecturer, $request->user());
        return redirect()->route('lecturers.index')->with('success', 'Lecturer created.');
    }

    public function edit(Lecturer $lecturer)
    {
        return view('lecturers.form', compact('lecturer'));
    }

    public function update(Request $request, Lecturer $lecturer)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:lecturers,email,' . $lecturer->id],
            'phone' => ['nullable', 'string', 'max:30'],
            'department' => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:active,inactive'],
        ]);
        $lecturer->update($data);
        app(\App\Services\ActivityLogService::class)->log('lecturers.update', 'Lecturer updated', $lecturer, $request->user());
        return redirect()->route('lecturers.index')->with('success', 'Lecturer updated.');
    }

    public function destroy(Lecturer $lecturer, Request $request)
    {
        $lecturer->delete();
        app(\App\Services\ActivityLogService::class)->log('lecturers.delete', 'Lecturer deleted', $lecturer, $request->user());
        return back()->with('success', 'Lecturer deleted.');
    }
}
