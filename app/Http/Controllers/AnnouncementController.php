<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Batch;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        return view('announcements.index', ['announcements' => Announcement::with('batch')->latest()->paginate(15), 'batches' => Batch::all()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'target_role' => ['required', 'in:all,students,lecturers'],
            'batch_id' => ['nullable', 'exists:batches,id'],
            'publish_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:publish_at'],
        ]);
        $data['created_by'] = $request->user()?->id;
        $announcement = Announcement::create($data);
        app(\App\Services\ActivityLogService::class)->log('announcements.create', 'Announcement created', $announcement, $request->user());
        return back()->with('success', 'Announcement created.');
    }
}
