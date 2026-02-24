@extends('layouts.app')
@section('title','Exams')
@section('breadcrumb','Exams / Manage')
@section('content')

{{-- Page Toolbar --}}
<div class="page-toolbar animate-in">
    <div></div>
    <div class="page-toolbar-actions">
        <a href="{{ route('exams.timetable') }}" class="btn-action btn-action-outline">
            <i class="fas fa-calendar-alt"></i> Timetable
        </a>
        <a href="{{ route('exams.create') }}" class="btn-action btn-action-primary">
            <i class="fas fa-plus"></i> New Exam
        </a>
    </div>
</div>

{{-- Exams Card --}}
<div class="page-card animate-in">
    <div class="page-card-header">
        <h6 class="page-card-header-title">
            <span class="header-icon warning"><i class="fas fa-file-alt"></i></span>
            All Exams
        </h6>
        <span class="text-muted" style="font-size:.75rem">{{ $exams->total() }} total</span>
    </div>
    <div class="page-card-body p-0">
        @if($exams->count())
        <div class="table-responsive">
            <table class="enhanced-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Course</th>
                        <th>Batch</th>
                        <th>Date & Time</th>
                        <th>Marks (Total / Pass)</th>
                        <th>Status</th>
                        <th class="text-end" style="width:100px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exams as $exam)
                    <tr>
                        <td style="font-weight:600;font-size:.8125rem">{{ $exam->exam_title }}</td>
                        <td style="font-size:.8125rem">{{ $exam->course->title ?? '-' }}</td>
                        <td>
                            <span
                                style="font-family:'SF Mono','Fira Code',monospace;font-size:.75rem;color:var(--accent-primary)">
                                {{ $exam->batch->batch_code ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <div style="font-size:.8125rem;font-weight:500">{{ $exam->exam_date?->format('Y-m-d') }}
                            </div>
                            <div style="font-size:.7rem;color:var(--text-muted)">{{ $exam->start_time }}</div>
                        </td>
                        <td>
                            <span style="font-weight:700;font-size:.8125rem">{{ $exam->total_marks }}</span>
                            <span style="color:var(--text-muted);font-size:.75rem"> / {{ $exam->pass_marks }}</span>
                        </td>
                        <td><span class="status-badge {{ $exam->status }}">{{ $exam->status }}</span></td>
                        <td>
                            <div class="action-group justify-content-end">
                                <a href="{{ route('exams.edit', $exam) }}" class="action-btn edit" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form method="POST" action="{{ route('exams.destroy', $exam) }}"
                                    class="js-confirm-action" data-confirm="Delete this exam?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="action-btn delete" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($exams->hasPages())
        <div class="px-3 pb-3 pt-2 d-flex justify-content-end">
            {{ $exams->links() }}
        </div>
        @endif
        @else
        <div class="empty-state">
            <i class="fas fa-file-alt"></i>
            <p>No exams found.</p>
        </div>
        @endif
    </div>
</div>
@endsection