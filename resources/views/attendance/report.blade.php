@extends('layouts.app')
@section('title','Low Attendance Report')
@section('breadcrumb','Attendance / Reports')
@section('content')

{{-- Filter & Export Toolbar --}}
<div class="page-toolbar animate-in">
    <form class="d-flex gap-2 align-items-end">
        <div class="filter-group" style="min-width:180px">
            <label
                style="font-size:.7rem;font-weight:600;text-transform:uppercase;letter-spacing:.04em;color:var(--text-muted);display:block;margin-bottom:.25rem">Batch</label>
            <select name="batch_id" class="form-select form-select-sm">
                <option value="">All Batches</option>
                @foreach($batches as $batch)
                <option value="{{ $batch->id }}" @selected(request('batch_id')==$batch->id)>{{ $batch->batch_code }}
                </option>
                @endforeach
            </select>
        </div>
        <button class="btn-action btn-action-outline" style="height:38px">
            <i class="fas fa-filter"></i> Filter
        </button>
    </form>
    <a href="{{ route('attendance.report', array_merge(request()->all(), ['export'=>1])) }}"
        class="btn-action btn-action-primary">
        <i class="fas fa-download"></i> Export CSV
    </a>
</div>

{{-- Report Card --}}
<div class="page-card animate-in">
    <div class="page-card-header">
        <h6 class="page-card-header-title">
            <span class="header-icon danger"><i class="fas fa-user-clock"></i></span>
            Low Attendance Students (&lt;80%)
        </h6>
    </div>
    <div class="page-card-body p-0">
        @if(count($rows))
        <div class="table-responsive">
            <table class="enhanced-table">
                <thead>
                    <tr>
                        <th>Student No</th>
                        <th>Name</th>
                        <th>Batch</th>
                        <th>Attendance %</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                    <tr>
                        <td>
                            <span
                                style="font-family:'SF Mono','Fira Code',monospace;font-size:.8rem;color:var(--accent-primary)">
                                {{ $row->student_no }}
                            </span>
                        </td>
                        <td style="font-weight:500;font-size:.8125rem">{{ $row->student_name }}</td>
                        <td><span style="font-family:'SF Mono','Fira Code',monospace;font-size:.75rem">{{
                                $row->batch_code }}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="pct-bar"><span class="pct-bar-fill bad"
                                        style="width:{{ min($row->attendance_percentage, 100) }}%"></span></span>
                                <span style="font-weight:700;font-size:.8125rem;color:var(--accent-danger)">{{
                                    $row->attendance_percentage }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-check-circle"></i>
            <p>No students with low attendance.</p>
        </div>
        @endif
    </div>
</div>
@endsection