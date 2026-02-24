@extends('layouts.app')
@section('title','Results')
@section('breadcrumb','Results / Workflow')
@section('content')

{{-- Page Toolbar --}}
<div class="page-toolbar animate-in">
    <div></div>
    <div class="page-toolbar-actions">
        <a href="{{ route('results.import.form') }}" class="btn-action btn-action-outline">
            <i class="fas fa-file-csv"></i> Bulk Upload CSV
        </a>
        <a href="{{ route('results.create') }}" class="btn-action btn-action-primary">
            <i class="fas fa-plus"></i> Enter Result
        </a>
    </div>
</div>

{{-- Results Card --}}
<div class="page-card animate-in">
    <div class="page-card-header">
        <h6 class="page-card-header-title">
            <span class="header-icon success"><i class="fas fa-poll"></i></span>
            Result Workflow
        </h6>
        <span class="text-muted" style="font-size:.75rem">{{ $results->total() }} total</span>
    </div>
    <div class="page-card-body p-0">
        @if($results->count())
        <div class="table-responsive">
            <table class="enhanced-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Exam</th>
                        <th>Marks</th>
                        <th>Grade</th>
                        <th>Status</th>
                        <th class="text-end" style="width:140px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $result)
                    <tr>
                        <td>
                            <span
                                style="font-family:'SF Mono','Fira Code',monospace;font-size:.8rem;color:var(--accent-primary)">
                                {{ $result->student->student_no ?? '' }}
                            </span>
                        </td>
                        <td style="font-weight:500;font-size:.8125rem">{{ $result->exam->exam_title ?? '' }}</td>
                        <td style="font-weight:700;font-size:.875rem">{{ $result->marks }}</td>
                        <td>
                            @if($result->grade)
                            <span style="font-weight:700;font-size:.8125rem;color:var(--accent-primary)">{{
                                $result->grade }}</span>
                            @else
                            <span style="color:var(--text-muted)">-</span>
                            @endif
                        </td>
                        <td><span class="status-badge {{ $result->status }}">{{ $result->status }}</span></td>
                        <td>
                            <div class="action-group justify-content-end">
                                @if($result->status === 'draft')
                                <form method="POST" action="{{ route('results.approve', $result) }}"
                                    class="js-confirm-action" data-confirm="Approve this result?">
                                    @csrf
                                    <button type="submit" class="action-btn approve" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                                @if($result->status === 'approved')
                                <form method="POST" action="{{ route('results.publish', $result) }}"
                                    class="js-confirm-action" data-confirm="Publish this result?">
                                    @csrf
                                    <button type="submit" class="action-btn publish" title="Publish">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($results->hasPages())
        <div class="px-3 pb-3 pt-2 d-flex justify-content-end">
            {{ $results->links() }}
        </div>
        @endif
        @else
        <div class="empty-state">
            <i class="fas fa-poll"></i>
            <p>No results found.</p>
        </div>
        @endif
    </div>
</div>
@endsection