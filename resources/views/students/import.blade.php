@extends('layouts.app')
@section('title','Import Students')
@section('breadcrumb','Academic / Students / Import')
@section('content')

<div class="page-card animate-in">
    <div class="page-card-header">
        <h6 class="page-card-header-title">
            <span class="header-icon purple"><i class="fas fa-file-csv"></i></span>
            Import Students from CSV
        </h6>
        <a href="{{ route('students.index') }}" class="btn-action btn-action-outline"
            style="font-size:.75rem;padding:.35rem .75rem">
            <i class="fas fa-arrow-left"></i> Back to Students
        </a>
    </div>
    <div class="page-card-body">
        <form method="POST" enctype="multipart/form-data" action="{{ route('students.import') }}"
            class="js-loading-form js-confirm-action" data-confirm="Import students from the selected CSV file?"
            data-confirm-title="Import Students">
            @csrf
            <div class="import-zone mb-3">
                <i class="fas fa-cloud-arrow-up"></i>
                <p>Select a CSV file containing student data</p>
                <input type="file" name="csv_file" class="form-control mx-auto" style="max-width:400px"
                    accept=".csv,.txt" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn-action btn-action-primary">
                    <i class="fas fa-upload"></i> Upload CSV
                </button>
            </div>
        </form>

        @if(session('import_report'))
        <div class="mt-4">
            <div class="form-section-title">Import Report</div>
            <div class="import-report">{{ json_encode(session('import_report'), JSON_PRETTY_PRINT) }}</div>
        </div>
        @endif
    </div>
</div>
@endsection