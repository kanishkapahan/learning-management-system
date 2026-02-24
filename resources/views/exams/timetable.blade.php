@extends('layouts.app')
@section('title','Exam Timetable')
@section('breadcrumb','Exams / Timetable')
@section('content')

<div class="page-card animate-in">
    <div class="page-card-header">
        <h6 class="page-card-header-title">
            <span class="header-icon info"><i class="fas fa-calendar-alt"></i></span>
            Exam Timetable
        </h6>
        <a href="{{ route('exams.index') }}" class="btn-action btn-action-outline"
            style="font-size:.75rem;padding:.35rem .75rem">
            <i class="fas fa-arrow-left"></i> Back to Exams
        </a>
    </div>
    <div class="page-card-body p-0">
        @if(count($rows))
        <div class="table-responsive">
            <table class="enhanced-table timetable-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Exam</th>
                        <th>Course</th>
                        <th>Batch</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $exam)
                    <tr>
                        <td style="font-weight:600;font-size:.8125rem">{{ $exam->exam_date?->format('Y-m-d') }}</td>
                        <td><span class="time-cell">{{ $exam->start_time }}</span></td>
                        <td style="font-weight:500;font-size:.8125rem">{{ $exam->exam_title }}</td>
                        <td style="font-size:.8125rem">{{ $exam->course->title ?? '-' }}</td>
                        <td>
                            <span
                                style="font-family:'SF Mono','Fira Code',monospace;font-size:.75rem;color:var(--accent-primary)">
                                {{ $exam->batch->batch_code ?? '-' }}
                            </span>
                        </td>
                        <td><span class="duration-badge">{{ $exam->duration_minutes }} min</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-calendar-times"></i>
            <p>No scheduled exams found.</p>
        </div>
        @endif
    </div>
</div>
@endsection