@extends('layouts.app')
@section('title','Bulk Result Upload')
@section('breadcrumb','Results / Import CSV')
@section('content')

<div class="page-card animate-in">
    <div class="page-card-header">
        <h6 class="page-card-header-title">
            <span class="header-icon purple"><i class="fas fa-file-csv"></i></span>
            Bulk Result Upload
        </h6>
        <a href="{{ route('results.index') }}" class="btn-action btn-action-outline"
            style="font-size:.75rem;padding:.35rem .75rem">
            <i class="fas fa-arrow-left"></i> Back to Results
        </a>
    </div>
    <div class="page-card-body">
        <form method="POST" enctype="multipart/form-data" action="{{ route('results.import') }}"
            class="js-loading-form js-confirm-action"
            data-confirm="Upload and process results from the selected CSV file?" data-confirm-title="Import Results">
            @csrf
            <div class="import-zone mb-3">
                <i class="fas fa-cloud-arrow-up"></i>
                <p>Select a CSV file containing student results</p>
                <input class="form-control mx-auto" style="max-width:400px" type="file" name="csv_file"
                    accept=".csv,.txt" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn-action btn-action-primary">
                    <i class="fas fa-upload"></i> Process CSV
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