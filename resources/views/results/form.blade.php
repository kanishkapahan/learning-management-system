@extends('layouts.app')
@section('title','Enter Result')
@section('breadcrumb','Results / Enter')
@section('content')

<div class="page-card animate-in">
    <div class="page-card-header">
        <h6 class="page-card-header-title">
            <span class="header-icon success"><i class="fas fa-plus"></i></span>
            Enter New Result
        </h6>
    </div>
    <div class="page-card-body">
        <form method="POST" action="{{ route('results.store') }}" class="row g-3">
            @csrf

            <div class="col-12">
                <div class="form-section-title">Student & Exam</div>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="student_id">Student</label>
                <select name="student_id" id="student_id" class="form-select" required>
                    @foreach($students as $student)
                    <option value="{{ $student->id }}">{{ $student->student_no }} - {{ $student->first_name }} {{
                        $student->last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="exam_id">Exam</label>
                <select name="exam_id" id="exam_id" class="form-select" required>
                    @foreach($exams as $exam)
                    <option value="{{ $exam->id }}" data-total="{{ $exam->total_marks }}">{{ $exam->exam_title }} ({{
                        $exam->total_marks }})</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 mt-4">
                <div class="form-section-title">Result Details</div>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="marks">Marks Obtained</label>
                <input name="marks" id="marks" type="number" step="0.01" min="0" class="form-control" required>
            </div>
            <div class="col-md-8">
                <label class="form-label" for="remarks">Remarks</label>
                <input name="remarks" id="remarks" class="form-control" placeholder="Optional remarks...">
            </div>

            <div class="col-12">
                <div class="form-actions">
                    <button type="submit" class="btn-action btn-action-primary">
                        <i class="fas fa-save"></i> Save Draft Result
                    </button>
                    <a href="{{ route('results.index') }}" class="btn-action btn-action-outline">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection