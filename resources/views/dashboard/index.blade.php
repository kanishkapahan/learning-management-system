@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard / Overview')

@section('content')
@php
$cardMeta = [
'students' => ['icon' => 'fa-users', 'color' => 'primary', 'sparkColor' => '#6366f1'],
'lecturers' => ['icon' => 'fa-chalkboard-teacher', 'color' => 'info', 'sparkColor' => '#06b6d4'],
'courses' => ['icon' => 'fa-book', 'color' => 'purple', 'sparkColor' => '#a855f7'],
'batches' => ['icon' => 'fa-layer-group', 'color' => 'warning', 'sparkColor' => '#f59e0b'],
'enrollments' => ['icon' => 'fa-clipboard-list', 'color' => 'success', 'sparkColor' => '#22c55e'],
'exams' => ['icon' => 'fa-file-alt', 'color' => 'indigo', 'sparkColor' => '#4f46e5'],
'published_results' => ['icon' => 'fa-square-poll-vertical','color' => 'danger', 'sparkColor' => '#ef4444'],
];
@endphp

{{-- ===== Stat Cards ===== --}}
<div class="row g-3 mb-4">
    @foreach($summary as $label => $value)
    @php $meta = $cardMeta[$label] ?? ['icon' => 'fa-chart-bar', 'color' => 'primary', 'sparkColor' => '#6366f1'];
    @endphp
    <div class="col-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-card-body">
                <div class="stat-icon {{ $meta['color'] }}">
                    <i class="fas {{ $meta['icon'] }}"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-label">{{ str_replace('_', ' ', $label) }}</div>
                    <div class="stat-value">
                        <span class="count-up" data-target="{{ $value }}">0</span>
                    </div>
                </div>
                <div class="stat-sparkline">
                    <canvas class="sparkline-canvas"
                        data-values="[{{ rand(2,8) }},{{ rand(4,12) }},{{ rand(6,15) }},{{ rand(3,10) }},{{ rand(8,20) }},{{ rand(5,14) }},{{ $value > 0 ? rand(7,18) : 0 }}]"
                        data-color="{{ $meta['sparkColor'] }}" height="32"></canvas>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ===== Quick Actions Row ===== --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('students.create') }}" class="quick-action-btn">
                <i class="fas fa-user-plus qa-icon-primary"></i> Add Student
            </a>
            <a href="{{ route('exams.create') }}" class="quick-action-btn">
                <i class="fas fa-plus-circle qa-icon-success"></i> Create Exam
            </a>
            <a href="{{ route('results.create') }}" class="quick-action-btn">
                <i class="fas fa-upload qa-icon-warning"></i> Enter Results
            </a>
            <a href="{{ route('reports.index') }}" class="quick-action-btn">
                <i class="fas fa-download qa-icon-info"></i> Export Reports
            </a>
        </div>
    </div>
</div>

{{-- ===== Charts Row 1 – Monthly Enrollments (full width) ===== --}}
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="chart-card">
            <div class="chart-card-header">
                <h6 class="chart-card-title"><i class="fas fa-chart-line me-2 text-primary"></i>Monthly Enrollments</h6>
            </div>
            <div class="chart-card-body" style="height:300px">
                <canvas id="chartMonthlyEnrollments"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ===== Charts Row 2 – Bar / Doughnut / Pie ===== --}}
<div class="row g-3 mb-4">
    <div class="col-lg-5">
        <div class="chart-card h-100">
            <div class="chart-card-header">
                <h6 class="chart-card-title"><i class="fas fa-chart-bar me-2" style="color:#06b6d4"></i>Average Marks
                    per Course</h6>
            </div>
            <div class="chart-card-body" style="height:280px">
                <canvas id="chartAvgMarks"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="chart-card h-100">
            <div class="chart-card-header">
                <h6 class="chart-card-title"><i class="fas fa-chart-pie me-2" style="color:#a855f7"></i>Pass Rate /
                    Batch</h6>
            </div>
            <div class="chart-card-body" style="height:280px">
                <canvas id="chartPassRate"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="chart-card h-100">
            <div class="chart-card-header">
                <h6 class="chart-card-title"><i class="fas fa-circle-half-stroke me-2" style="color:#f59e0b"></i>Result
                    Status Breakdown</h6>
            </div>
            <div class="chart-card-body" style="height:280px">
                <canvas id="chartResultStatus"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ===== Bottom Row – Activity / Top Performers / System ===== --}}
<div class="row g-3">
    {{-- Recent Activity --}}
    <div class="col-lg-4">
        <div class="stat-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-semibold" style="font-size:.875rem"><i
                        class="fas fa-history me-2 text-primary"></i>Recent Activity</span>
            </div>
            <div class="card-body p-3">
                @forelse($activities as $activity)
                @php
                $event = strtolower($activity->event);
                $iconMap = [
                'login' => ['icon' => 'fa-sign-in-alt', 'class' => 'login'],
                'logout' => ['icon' => 'fa-sign-out-alt','class' => 'logout'],
                'created' => ['icon' => 'fa-plus', 'class' => 'created'],
                'updated' => ['icon' => 'fa-pen', 'class' => 'updated'],
                'published' => ['icon' => 'fa-check', 'class' => 'published'],
                'deleted' => ['icon' => 'fa-trash', 'class' => 'deleted'],
                ];
                $matched = null;
                foreach ($iconMap as $key => $val) {
                if (str_contains($event, $key)) { $matched = $val; break; }
                }
                $matched = $matched ?? ['icon' => 'fa-circle', 'class' => 'default'];
                @endphp
                <div class="activity-item">
                    <div class="activity-icon {{ $matched['class'] }}">
                        <i class="fas {{ $matched['icon'] }}"></i>
                    </div>
                    <div>
                        <div class="activity-desc">{{ $activity->description }}</div>
                        <div class="activity-time">
                            <span
                                class="badge badge-soft-{{ $matched['class'] === 'login' ? 'success' : ($matched['class'] === 'deleted' ? 'danger' : 'primary') }}"
                                style="font-size:.65rem">{{ $activity->event }}</span>
                            &middot; {{ $activity->created_at?->diffForHumans() }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2 d-block" style="opacity:.3"></i>
                    No recent activity
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Top Performers --}}
    <div class="col-lg-5">
        <div class="stat-card h-100">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-semibold" style="font-size:.875rem"><i class="fas fa-trophy me-2"
                        style="color:#f59e0b"></i>Top Performers</span>
            </div>
            <div class="card-body p-3">
                @if($topPerformers->count())
                <table class="performers-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>ID</th>
                            <th>Avg Marks</th>
                            <th>Exams</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topPerformers as $i => $student)
                        <tr>
                            <td>
                                <span class="rank-badge {{ $i < 3 ? 'rank-'.($i+1) : 'rank-default' }}">{{ $i + 1
                                    }}</span>
                            </td>
                            <td class="fw-semibold">{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td><code style="font-size:.75rem">{{ $student->student_no }}</code></td>
                            <td class="fw-bold">{{ $student->avg_marks }}</td>
                            <td><span class="badge badge-soft-info">{{ $student->exam_count }}</span></td>
                            <td style="width:80px">
                                <div class="marks-bar">
                                    <div class="marks-bar-fill" style="width:{{ min($student->avg_marks, 100) }}%">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-medal fa-2x mb-2 d-block" style="opacity:.3"></i>
                    No published results yet
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- System Health --}}
    <div class="col-lg-3">
        <div class="stat-card mb-3">
            <div class="card-header">
                <span class="fw-semibold" style="font-size:.875rem"><i class="fas fa-server me-2"
                        style="color:#22c55e"></i>System Health</span>
            </div>
            <div class="card-body p-3">
                <div class="system-stat">
                    <span class="system-stat-label"><i class="fas fa-users me-2"></i>Active Users</span>
                    <span class="system-stat-value">{{ number_format($systemStats['active_users']) }}</span>
                </div>
                <div class="system-stat">
                    <span class="system-stat-label"><i class="fas fa-user-group me-2"></i>Total Users</span>
                    <span class="system-stat-value">{{ number_format($systemStats['total_users']) }}</span>
                </div>
                <div class="system-stat">
                    <span class="system-stat-label"><i class="fas fa-hourglass-half me-2"></i>Pending Results</span>
                    <span class="system-stat-value">{{ number_format($systemStats['pending_results']) }}</span>
                </div>
                <div class="system-stat">
                    <span class="system-stat-label"><i class="fas fa-calendar me-2"></i>Upcoming Exams</span>
                    <span class="system-stat-value">{{ number_format($systemStats['upcoming_exams']) }}</span>
                </div>
            </div>
        </div>

        {{-- Announcements --}}
        <div class="stat-card">
            <div class="card-header">
                <span class="fw-semibold" style="font-size:.875rem"><i class="fas fa-bullhorn me-2"
                        style="color:#6366f1"></i>Announcements</span>
            </div>
            <div class="card-body p-3">
                @forelse($announcements as $ann)
                <div class="mb-2 pb-2" style="border-bottom:1px solid var(--border-color)">
                    <div class="fw-semibold" style="font-size:.8125rem">{{ $ann->title }}</div>
                    <div class="text-muted" style="font-size:.7rem">{{ $ann->created_at?->diffForHumans() }}</div>
                </div>
                @empty
                <div class="text-center text-muted py-3">
                    <i class="fas fa-bell-slash d-block mb-1" style="opacity:.3"></i>
                    <small>No announcements</small>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.lmsDashboard = @json($charts);
</script>
<script src="{{ asset('js/dashboard.js') }}"></script>
@endpush