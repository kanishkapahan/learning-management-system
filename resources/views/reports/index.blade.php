@extends('layouts.app')
@section('title','Reports & Analytics')
@section('breadcrumb','Reports / Analytics')
@section('content')

{{-- Filter Bar --}}
<div class="filter-bar animate-in">
    <form class="d-flex align-items-end gap-3 flex-wrap w-100">
        <div class="filter-group">
            <label>Course</label>
            <select name="course_id" class="form-select form-select-sm">
                <option value="">All Courses</option>
                @foreach($courses as $course)
                <option value="{{ $course->id }}" @selected(request('course_id')==$course->id)>{{ $course->title }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Batch</label>
            <select name="batch_id" class="form-select form-select-sm">
                <option value="">All Batches</option>
                @foreach($batches as $batch)
                <option value="{{ $batch->id }}" @selected(request('batch_id')==$batch->id)>{{ $batch->batch_code }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Exam</label>
            <select name="exam_id" class="form-select form-select-sm">
                <option value="">All Exams</option>
                @foreach($exams as $exam)
                <option value="{{ $exam->id }}" @selected(request('exam_id')==$exam->id)>{{ $exam->exam_title }}
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <button class="btn-action btn-action-primary btn-filter">
                <i class="fas fa-filter"></i> Apply
            </button>
        </div>
    </form>
</div>

<div class="row g-3">
    {{-- Student List --}}
    <div class="col-12">
        <div class="page-card animate-in">
            <div class="page-card-header">
                <h6 class="page-card-header-title">
                    <span class="header-icon primary"><i class="fas fa-user-graduate"></i></span>
                    Student List by Batch / Course
                </h6>
                <a href="{{ route('reports.export.students', request()->all()) }}" class="btn-action btn-action-outline"
                    style="font-size:.75rem;padding:.35rem .75rem">
                    <i class="fas fa-download"></i> Export CSV
                </a>
            </div>
            <div class="page-card-body p-0">
                @if(count($studentList))
                <div class="table-responsive">
                    <table class="enhanced-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Batch</th>
                                <th>Course</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($studentList as $r)
                            <tr>
                                <td><span
                                        style="font-family:'SF Mono','Fira Code',monospace;font-size:.8rem;color:var(--accent-primary)">{{
                                        $r->student_no }}</span></td>
                                <td style="font-weight:500;font-size:.8125rem">{{ $r->first_name }} {{ $r->last_name }}
                                </td>
                                <td style="font-size:.8125rem">{{ $r->email }}</td>
                                <td><span style="font-family:'SF Mono','Fira Code',monospace;font-size:.75rem">{{
                                        $r->batch_code }}</span></td>
                                <td style="font-size:.8125rem">{{ $r->course_title }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state"><i class="fas fa-user-graduate"></i>
                    <p>No student data for current filters.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Pass Rate --}}
    <div class="col-lg-6">
        <div class="page-card animate-in">
            <div class="page-card-header">
                <h6 class="page-card-header-title">
                    <span class="header-icon success"><i class="fas fa-chart-pie"></i></span>
                    Pass Rate Report
                </h6>
                <a href="{{ route('reports.export.pass-rates', request()->all()) }}"
                    class="btn-action btn-action-outline" style="font-size:.75rem;padding:.35rem .75rem">
                    <i class="fas fa-download"></i> CSV
                </a>
            </div>
            <div class="page-card-body p-0">
                @if(count($passRates))
                <div class="table-responsive">
                    <table class="enhanced-table">
                        <thead>
                            <tr>
                                <th>Exam</th>
                                <th>Batch</th>
                                <th>Pass %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($passRates as $r)
                            <tr>
                                <td style="font-weight:500;font-size:.8125rem">{{ $r->exam_title }}</td>
                                <td><span style="font-family:'SF Mono','Fira Code',monospace;font-size:.75rem">{{
                                        $r->batch_code }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="pct-bar"><span
                                                class="pct-bar-fill {{ $r->pass_rate >= 70 ? 'good' : ($r->pass_rate >= 50 ? 'warn' : 'bad') }}"
                                                style="width:{{ min($r->pass_rate, 100) }}%"></span></span>
                                        <span style="font-weight:700;font-size:.8125rem">{{ $r->pass_rate }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state"><i class="fas fa-chart-pie"></i>
                    <p>No pass rate data.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Top Performers --}}
    <div class="col-lg-6">
        <div class="page-card animate-in">
            <div class="page-card-header">
                <h6 class="page-card-header-title">
                    <span class="header-icon warning"><i class="fas fa-trophy"></i></span>
                    Top Performers
                </h6>
            </div>
            <div class="page-card-body p-0">
                @if(count($topPerformers))
                <div class="table-responsive">
                    <table class="enhanced-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Exam</th>
                                <th>Marks</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topPerformers as $r)
                            <tr>
                                <td><span
                                        style="font-family:'SF Mono','Fira Code',monospace;font-size:.8rem;color:var(--accent-primary)">{{
                                        $r->student->student_no ?? '' }}</span></td>
                                <td style="font-weight:500;font-size:.8125rem">{{ $r->exam->exam_title ?? '' }}</td>
                                <td style="font-weight:700;font-size:.875rem">{{ $r->marks }}</td>
                                <td><span style="font-weight:700;color:var(--accent-primary)">{{ $r->grade }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state"><i class="fas fa-trophy"></i>
                    <p>No performer data.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Low Attendance --}}
    <div class="col-12">
        <div class="page-card animate-in">
            <div class="page-card-header">
                <h6 class="page-card-header-title">
                    <span class="header-icon danger"><i class="fas fa-user-clock"></i></span>
                    Low Attendance List
                </h6>
            </div>
            <div class="page-card-body p-0">
                @if(count($lowAttendance))
                <div class="table-responsive">
                    <table class="enhanced-table">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Batch</th>
                                <th>Attendance %</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowAttendance as $r)
                            <tr>
                                <td style="font-weight:500;font-size:.8125rem">{{ $r->student_name }}</td>
                                <td><span style="font-family:'SF Mono','Fira Code',monospace;font-size:.75rem">{{
                                        $r->batch_code }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="pct-bar"><span class="pct-bar-fill bad"
                                                style="width:{{ min($r->attendance_percentage, 100) }}%"></span></span>
                                        <span style="font-weight:700;font-size:.8125rem;color:var(--accent-danger)">{{
                                            $r->attendance_percentage }}%</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state"><i class="fas fa-user-clock"></i>
                    <p>No low attendance records.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection