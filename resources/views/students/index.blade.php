@extends('layouts.app')
@section('title','Students')
@section('breadcrumb','Academic / Students')
@section('content')

{{-- Page Toolbar --}}
<div class="page-toolbar animate-in">
    <div></div>
    <div class="page-toolbar-actions">
        <a href="{{ route('students.import.form') }}" class="btn-action btn-action-outline">
            <i class="fas fa-file-csv"></i> Import CSV
        </a>
        <a href="{{ route('students.create') }}" class="btn-action btn-action-primary">
            <i class="fas fa-plus"></i> New Student
        </a>
    </div>
</div>

{{-- Students Card --}}
<div class="page-card animate-in">
    <div class="page-card-header">
        <h6 class="page-card-header-title">
            <span class="header-icon primary"><i class="fas fa-user-graduate"></i></span>
            All Students
        </h6>
        <span class="text-muted" style="font-size:.75rem">{{ $students->total() }} total</span>
    </div>
    <div class="page-card-body p-0">
        @if($students->count())
        <div class="table-responsive">
            <table class="enhanced-table">
                <thead>
                    <tr>
                        <th>Student No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th class="text-end" style="width:100px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                    <tr>
                        <td>
                            <span
                                style="font-family:'SF Mono','Fira Code',monospace;font-size:.8rem;color:var(--accent-primary)">
                                {{ $student->student_no }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="attendance-table">
                                    <span class="student-avatar">{{
                                        strtoupper(substr($student->first_name,0,1).substr($student->last_name,0,1))
                                        }}</span>
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:.8125rem">{{ $student->first_name }} {{
                                        $student->last_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="font-size:.8125rem">{{ $student->email }}</td>
                        <td><span class="status-badge {{ $student->status }}">{{ $student->status }}</span></td>
                        <td>
                            <div class="action-group justify-content-end">
                                <a href="{{ route('students.edit', $student) }}" class="action-btn edit" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <form method="POST" action="{{ route('students.destroy', $student) }}"
                                    class="js-confirm-action" data-confirm="Delete this student?">
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
        @if($students->hasPages())
        <div class="px-3 pb-3 pt-2 d-flex justify-content-end">
            {{ $students->links() }}
        </div>
        @endif
        @else
        <div class="empty-state">
            <i class="fas fa-user-graduate"></i>
            <p>No students found.</p>
        </div>
        @endif
    </div>
</div>
@endsection