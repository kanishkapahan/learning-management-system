<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use App\Models\ResourceDownloadLog;
use App\Models\ResourceFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function index()
    {
        return view('resources.index', [
            'resources' => ResourceFile::with(['course', 'batch'])->latest()->paginate(15),
            'courses' => Course::all(),
            'batches' => Batch::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'batch_id' => ['nullable', 'exists:batches,id'],
            'title' => ['required', 'string', 'max:255'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,ppt,pptx'],
        ]);
        $file = $request->file('file');
        $resource = ResourceFile::create([
            'course_id' => $data['course_id'],
            'batch_id' => $data['batch_id'] ?? null,
            'title' => $data['title'],
            'file_path' => $file->store('resources', 'public'),
            'mime_type' => $file->getClientMimeType(),
            'uploaded_by' => $request->user()?->id,
        ]);
        app(\App\Services\ActivityLogService::class)->log('resources.upload', 'Resource uploaded', $resource, $request->user());
        return back()->with('success', 'Resource uploaded.');
    }

    public function download(ResourceFile $resource, Request $request)
    {
        // Enrollment-based access control for students
        if ($request->user()?->hasRole('STUDENT')) {
            $studentId = $request->user()->studentProfile?->id;
            $allowed = \DB::table('enrollments')
                ->where('student_id', $studentId)
                ->where('course_id', $resource->course_id)
                ->when($resource->batch_id, fn ($q) => $q->where('batch_id', $resource->batch_id))
                ->exists();
            abort_unless($allowed, 403);
        }

        ResourceDownloadLog::create([
            'resource_id' => $resource->id,
            'user_id' => $request->user()?->id,
            'downloaded_at' => now(),
        ]);
        app(\App\Services\ActivityLogService::class)->log('resources.download', 'Resource downloaded', $resource, $request->user());

        return Storage::disk('public')->download($resource->file_path, basename($resource->file_path));
    }
}
