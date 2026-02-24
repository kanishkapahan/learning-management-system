<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index()
    {
        return view('batches.index', ['batches' => Batch::with('course')->latest()->paginate(15)]);
    }

    public function create()
    {
        return view('batches.form', ['batch' => new Batch(), 'courses' => Course::orderBy('title')->get()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'batch_code' => ['required', 'string', 'max:50', 'unique:batches,batch_code'],
            'year' => ['required', 'integer', 'min:2020', 'max:2100'],
            'intake' => ['required', 'in:Jan,May,Sep'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', 'in:active,completed,planned'],
        ]);
        $batch = Batch::create($data);
        app(\App\Services\ActivityLogService::class)->log('batches.create', 'Batch created', $batch, $request->user());
        return redirect()->route('batches.index')->with('success', 'Batch created.');
    }

    public function edit(Batch $batch)
    {
        return view('batches.form', ['batch' => $batch, 'courses' => Course::orderBy('title')->get()]);
    }

    public function update(Request $request, Batch $batch)
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'batch_code' => ['required', 'string', 'max:50', 'unique:batches,batch_code,' . $batch->id],
            'year' => ['required', 'integer', 'min:2020', 'max:2100'],
            'intake' => ['required', 'in:Jan,May,Sep'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', 'in:active,completed,planned'],
        ]);
        $batch->update($data);
        app(\App\Services\ActivityLogService::class)->log('batches.update', 'Batch updated', $batch, $request->user());
        return redirect()->route('batches.index')->with('success', 'Batch updated.');
    }

    public function destroy(Batch $batch, Request $request)
    {
        $batch->delete();
        app(\App\Services\ActivityLogService::class)->log('batches.delete', 'Batch deleted', $batch, $request->user());
        return back()->with('success', 'Batch deleted.');
    }
}
