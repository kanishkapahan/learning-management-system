@extends('layouts.app')
@section('title','Resources')
@section('breadcrumb','Resources / Files')
@section('content')

<div class="row g-3">
    {{-- Upload Form --}}
    <div class="col-lg-5">
        <div class="page-card animate-in">
            <div class="page-card-header">
                <h6 class="page-card-header-title">
                    <span class="header-icon success"><i class="fas fa-cloud-arrow-up"></i></span>
                    Upload Resource
                </h6>
            </div>
            <div class="page-card-body">
                <form method="POST" enctype="multipart/form-data" action="{{ route('resources.store') }}"
                    class="row g-3 js-loading-form js-confirm-action" data-confirm="Upload this resource file?"
                    data-confirm-title="Upload Resource">
                    @csrf
                    <div class="col-12">
                        <label class="form-label" for="res_title">Title</label>
                        <input name="title" id="res_title" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="course_id">Course</label>
                        <select name="course_id" id="course_id" class="form-select">
                            @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="batch_id">Batch</label>
                        <select name="batch_id" id="batch_id" class="form-select">
                            <option value="">All Batches</option>
                            @foreach($batches as $batch)
                            <option value="{{ $batch->id }}">{{ $batch->batch_code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="import-zone">
                            <i class="fas fa-file-arrow-up"></i>
                            <p>Select a file to upload</p>
                            <input type="file" name="file" class="form-control mx-auto" style="max-width:300px"
                                required>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn-action btn-action-primary w-100">
                            <i class="fas fa-upload"></i> Upload Resource
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Resources List --}}
    <div class="col-lg-7">
        <div class="page-card animate-in">
            <div class="page-card-header">
                <h6 class="page-card-header-title">
                    <span class="header-icon purple"><i class="fas fa-folder-open"></i></span>
                    Available Resources
                </h6>
                <span class="text-muted" style="font-size:.75rem">{{ $resources->total() }} files</span>
            </div>
            <div class="page-card-body p-0">
                @if($resources->count())
                <div class="table-responsive">
                    <table class="enhanced-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Course</th>
                                <th>Batch</th>
                                <th class="text-end" style="width:80px">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resources as $resource)
                            <tr>
                                <td style="font-weight:600;font-size:.8125rem">{{ $resource->title }}</td>
                                <td style="font-size:.8125rem">{{ $resource->course->title ?? '-' }}</td>
                                <td>
                                    <span
                                        style="font-family:'SF Mono','Fira Code',monospace;font-size:.75rem;color:var(--accent-primary)">
                                        {{ $resource->batch->batch_code ?? 'All' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('resources.download', $resource) }}" class="action-btn download"
                                        title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($resources->hasPages())
                <div class="px-3 pb-3 pt-2 d-flex justify-content-end">
                    {{ $resources->links() }}
                </div>
                @endif
                @else
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>No resources uploaded yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection