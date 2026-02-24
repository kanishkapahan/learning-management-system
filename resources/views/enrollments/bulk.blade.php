@extends('layouts.app')
@section('title','Bulk Enrollment')
@section('breadcrumb','Academic / Enrollments / Bulk')
@section('content')

<div class="page-card animate-in">
    <div class="page-card-header">
        <h6 class="page-card-header-title">
            <span class="header-icon info"><i class="fas fa-user-plus"></i></span>
            Bulk Enrollment
        </h6>
    </div>
    <div class="page-card-body">
        <form method="POST" action="{{ route('enrollments.store') }}" class="row g-3 js-confirm-action"
            data-confirm="Enroll the selected students in this batch?" data-confirm-title="Bulk Enrollment">
            @csrf

            <div class="col-12">
                <div class="form-section-title">Enrollment Details</div>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="batch_id">Batch</label>
                <select name="batch_id" id="batch_id" class="form-select">
                    @foreach($batches as $batch)
                    <option value="{{ $batch->id }}">{{ $batch->batch_code }} - {{ $batch->course->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="enrollment_status">Enrollment Status</label>
                <select name="enrollment_status" id="enrollment_status" class="form-select">
                    <option value="active">Active</option>
                    <option value="completed">Completed</option>
                    <option value="dropped">Dropped</option>
                </select>
            </div>

            <div class="col-12 mt-4">
                <div class="form-section-title">Select Students</div>
            </div>
            <div class="col-12">
                <label class="form-label" for="student_ids">Students</label>
                <select name="student_ids[]" id="student_ids" class="form-select" multiple required
                    style="min-height:200px">
                    @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->student_no }} - {{ $student->first_name }} {{
                        $student->last_name }}</option>
                    @endforeach
                </select>
                <div style="font-size:.7rem;color:var(--text-muted);margin-top:.25rem">Hold Ctrl/Cmd to select multiple
                    students</div>
            </div>

            <div class="col-12">
                <div class="form-actions">
                    <button type="submit" class="btn-action btn-action-primary">
                        <i class="fas fa-user-plus"></i> Enroll Selected Students
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection