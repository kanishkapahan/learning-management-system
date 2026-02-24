@extends('layouts.app')
@section('title','System Settings')
@section('breadcrumb','System / Settings')
@section('content')

<div class="page-card animate-in">
    <div class="page-card-header">
        <h6 class="page-card-header-title">
            <span class="header-icon primary"><i class="fas fa-cog"></i></span>
            System Settings
        </h6>
    </div>
    <div class="page-card-body">
        <form method="POST" enctype="multipart/form-data" action="{{ route('settings.update') }}"
            class="row g-3 js-confirm-action" data-confirm="Save all system settings changes?"
            data-confirm-title="Save Settings">
            @csrf @method('PUT')

            {{-- General --}}
            <div class="col-12">
                <div class="settings-section">
                    <div class="settings-section-title">
                        <i class="fas fa-university"></i> General Settings
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label" for="academic_year">Academic Year</label>
                            <input name="academic_year" id="academic_year" class="form-control"
                                value="{{ old('academic_year', $settings['academic_year'] ?? now()->year.'/'.(now()->year+1)) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="enable_self_registration">Self Registration</label>
                            <select name="enable_self_registration" id="enable_self_registration" class="form-select">
                                <option value="1" @selected(($settings['enable_self_registration'] ?? '0' )==='1' )>
                                    Enabled</option>
                                <option value="0" @selected(($settings['enable_self_registration'] ?? '0' )==='0' )>
                                    Disabled</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="system_logo">System Logo</label>
                            <input type="file" name="system_logo" id="system_logo" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grade Thresholds --}}
            <div class="col-12">
                <div class="settings-section">
                    <div class="settings-section-title">
                        <i class="fas fa-ranking-star"></i> Grade Thresholds
                    </div>
                    <div class="row g-2">
                        @foreach($gradeThresholds as $i => $row)
                        <div class="col-md-6">
                            <div class="grade-row">
                                <div class="grade-letter">{{ $row['grade'] ?? '?' }}</div>
                                <input name="grade_thresholds[{{ $i }}][grade]" class="form-control form-control-sm"
                                    value="{{ $row['grade'] ?? '' }}" placeholder="Grade" style="max-width:80px">
                                <input name="grade_thresholds[{{ $i }}][min]" type="number" step="0.01"
                                    class="form-control form-control-sm" value="{{ $row['min'] ?? '' }}"
                                    placeholder="Min %">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="col-12">
                <div class="form-actions">
                    <button type="submit" class="btn-action btn-action-primary">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection