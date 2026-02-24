@extends('layouts.app')
@section('title','Mark Attendance')
@section('breadcrumb','Attendance / Mark')
@section('content')

<div class="page-card animate-in">
    <div class="page-card-header">
        <h6 class="page-card-header-title">
            <span class="header-icon success"><i class="fas fa-clipboard-check"></i></span>
            Mark Attendance
        </h6>
    </div>
    <div class="page-card-body">
        <form method="POST" action="{{ route('attendance.store') }}" class="js-confirm-action"
            data-confirm="Save attendance records for this batch?" data-confirm-title="Save Attendance">
            @csrf

            {{-- Batch & Date Selection --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label" for="attendance-batch">Batch</label>
                    <select id="attendance-batch" name="batch_id" class="form-select">
                        @foreach($batches as $batch)
                        <option value="{{ $batch->id }}">{{ $batch->batch_code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="att_date">Date</label>
                    <input type="date" name="date" id="att_date" value="{{ now()->toDateString() }}"
                        class="form-control">
                </div>
            </div>

            {{-- Student Panels --}}
            @foreach($batches as $batch)
            <div class="attendance-batch-panel" data-batch="{{ $batch->id }}"
                style="{{ $loop->first ? '' : 'display:none' }}">
                <div class="table-responsive">
                    <table class="enhanced-table attendance-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th style="width:160px">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($batch->students as $student)
                            <tr>
                                <td>
                                    <div class="student-cell">
                                        <span class="student-avatar">{{
                                            strtoupper(substr($student->first_name,0,1).substr($student->last_name,0,1))
                                            }}</span>
                                        <span style="font-size:.8125rem">
                                            <span
                                                style="font-family:'SF Mono','Fira Code',monospace;color:var(--accent-primary);font-size:.75rem">{{
                                                $student->student_no }}</span>
                                            - {{ $student->first_name }} {{ $student->last_name }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <input type="hidden"
                                        name="rows[{{ $loop->parent->index }}{{ $loop->index }}][student_id]"
                                        value="{{ $student->id }}">
                                    <select name="rows[{{ $loop->parent->index }}{{ $loop->index }}][status]"
                                        class="form-select form-select-sm">
                                        <option value="present">Present</option>
                                        <option value="absent">Absent</option>
                                        <option value="late">Late</option>
                                    </select>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach

            <div class="form-actions">
                <button type="submit" class="btn-action btn-action-primary">
                    <i class="fas fa-save"></i> Save Attendance
                </button>
            </div>
        </form>
    </div>
</div>
@endsection