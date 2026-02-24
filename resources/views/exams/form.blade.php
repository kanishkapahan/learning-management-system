@extends('layouts.app')
@section('title', $exam->exists ? 'Edit Exam' : 'Create Exam')
@section('breadcrumb','Exams / Form')
@section('content')

<div class="page-card animate-in">
    <div class="page-card-header">
        <h6 class="page-card-header-title">
            <span class="header-icon warning"><i
                    class="fas fa-{{ $exam->exists ? 'pen-to-square' : 'plus' }}"></i></span>
            {{ $exam->exists ? 'Edit Exam' : 'New Exam' }}
        </h6>
    </div>
    <div class="page-card-body">
        <form method="POST" action="{{ $exam->exists ? route('exams.update', $exam) : route('exams.store') }}"
            class="row g-3">
            @csrf
            @if($exam->exists) @method('PUT') @endif

            {{-- Basic Details --}}
            <div class="col-12">
                <div class="form-section-title">Exam Details</div>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="exam_title">Exam Title</label>
                <input name="exam_title" id="exam_title" class="form-control"
                    value="{{ old('exam_title', $exam->exam_title) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="course_id">Course</label>
                <select name="course_id" id="course_id" class="form-select" required>
                    @foreach($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id', $exam->course_id) == $course->id)>{{
                        $course->course_code }} - {{ $course->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="batch_id">Batch</label>
                <select name="batch_id" id="batch_id" class="form-select" required>
                    @foreach($batches as $batch)
                    <option value="{{ $batch->id }}" @selected(old('batch_id', $exam->batch_id) == $batch->id)>{{
                        $batch->batch_code }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Schedule --}}
            <div class="col-12 mt-4">
                <div class="form-section-title">Schedule</div>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="exam_date">Exam Date</label>
                <input name="exam_date" id="exam_date" type="date" class="form-control"
                    value="{{ old('exam_date', optional($exam->exam_date)->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="start_time">Start Time</label>
                <input name="start_time" id="start_time" type="time" class="form-control"
                    value="{{ old('start_time', $exam->start_time) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="duration_minutes">Duration (minutes)</label>
                <input name="duration_minutes" id="duration_minutes" type="number" min="1" class="form-control"
                    value="{{ old('duration_minutes', $exam->duration_minutes) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="status">Status</label>
                <select name="status" id="status" class="form-select">
                    @foreach(['draft' => 'Draft', 'scheduled' => 'Scheduled', 'held' => 'Held'] as $val => $label)
                    <option value="{{ $val }}" @selected(old('status', $exam->status) === $val)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Marks --}}
            <div class="col-12 mt-4">
                <div class="form-section-title">Marks & Components</div>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="total_marks">Total Marks</label>
                <input name="total_marks" id="total_marks" type="number" step="0.01" class="form-control"
                    value="{{ old('total_marks', $exam->total_marks) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="pass_marks">Pass Marks</label>
                <input name="pass_marks" id="pass_marks" type="number" step="0.01" class="form-control"
                    value="{{ old('pass_marks', $exam->pass_marks) }}" required>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="mcq_marks">MCQ</label>
                <input name="components[mcq_marks]" id="mcq_marks" type="number" step="0.01" class="form-control"
                    value="{{ old('components.mcq_marks', $exam->components->first()->mcq_marks ?? 0) }}">
            </div>
            <div class="col-md-2">
                <label class="form-label" for="theory_marks">Theory</label>
                <input name="components[theory_marks]" id="theory_marks" type="number" step="0.01" class="form-control"
                    value="{{ old('components.theory_marks', $exam->components->first()->theory_marks ?? 0) }}">
            </div>
            <div class="col-md-2">
                <label class="form-label" for="practical_marks">Practical</label>
                <input name="components[practical_marks]" id="practical_marks" type="number" step="0.01"
                    class="form-control"
                    value="{{ old('components.practical_marks', $exam->components->first()->practical_marks ?? 0) }}">
            </div>

            {{-- Actions --}}
            <div class="col-12">
                <div class="form-actions">
                    <button type="submit" class="btn-action btn-action-primary">
                        <i class="fas fa-{{ $exam->exists ? 'save' : 'plus' }}"></i>
                        {{ $exam->exists ? 'Update Exam' : 'Create Exam' }}
                    </button>
                    <a href="{{ route('exams.index') }}" class="btn-action btn-action-outline">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection