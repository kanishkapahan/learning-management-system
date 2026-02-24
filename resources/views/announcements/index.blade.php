@extends('layouts.app')
@section('title','Announcements')
@section('breadcrumb','Communication / Announcements')
@section('content')

<div class="row g-3">
    {{-- Create Form --}}
    <div class="col-lg-5">
        <div class="page-card animate-in">
            <div class="page-card-header">
                <h6 class="page-card-header-title">
                    <span class="header-icon primary"><i class="fas fa-bullhorn"></i></span>
                    New Announcement
                </h6>
            </div>
            <div class="page-card-body">
                <form method="POST" action="{{ route('announcements.store') }}" class="row g-3 js-confirm-action"
                    data-confirm="Publish this announcement?" data-confirm-title="Publish Announcement">
                    @csrf
                    <div class="col-12">
                        <label class="form-label" for="ann_title">Title</label>
                        <input name="title" id="ann_title" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="ann_body">Body</label>
                        <textarea name="body" id="ann_body" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="target_role">Target Role</label>
                        <select name="target_role" id="target_role" class="form-select">
                            <option value="all">All</option>
                            <option value="students">Students</option>
                            <option value="lecturers">Lecturers</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="batch_id">Batch (optional)</label>
                        <select name="batch_id" id="batch_id" class="form-select">
                            <option value="">All Batches</option>
                            @foreach($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->batch_code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="publish_at">Publish At</label>
                        <input type="datetime-local" name="publish_at" id="publish_at" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="expires_at">Expires At</label>
                        <input type="datetime-local" name="expires_at" id="expires_at" class="form-control">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn-action btn-action-primary w-100">
                            <i class="fas fa-paper-plane"></i> Publish Announcement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Announcements List --}}
    <div class="col-lg-7">
        <div class="page-card animate-in">
            <div class="page-card-header">
                <h6 class="page-card-header-title">
                    <span class="header-icon info"><i class="fas fa-list"></i></span>
                    Recent Announcements
                </h6>
                <span class="text-muted" style="font-size:.75rem">{{ $announcements->total() }} total</span>
            </div>
            <div class="page-card-body p-0">
                @if($announcements->count())
                <div class="px-3 pt-3">
                    @foreach($announcements as $a)
                    <div class="announcement-item">
                        <div class="announcement-dot"></div>
                        <div style="flex:1;min-width:0">
                            <div style="font-weight:600;font-size:.8125rem;color:var(--text-primary)">{{ $a->title }}
                            </div>
                            <div class="announcement-meta">
                                <span class="target-badge {{ $a->target_role }}">
                                    <i
                                        class="fas fa-{{ $a->target_role === 'all' ? 'globe' : ($a->target_role === 'students' ? 'user-graduate' : 'chalkboard-teacher') }}"></i>
                                    {{ ucfirst($a->target_role) }}
                                </span>
                                @if($a->batch)
                                <span class="ms-1" style="font-family:'SF Mono','Fira Code',monospace">{{
                                    $a->batch->batch_code }}</span>
                                @endif
                                <span class="ms-2">{{ optional($a->publish_at)->format('Y-m-d H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($announcements->hasPages())
                <div class="px-3 pb-3 pt-2 d-flex justify-content-end">
                    {{ $announcements->links() }}
                </div>
                @endif
                @else
                <div class="empty-state">
                    <i class="fas fa-bullhorn"></i>
                    <p>No announcements yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection