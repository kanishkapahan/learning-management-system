@extends('layouts.app')
@section('title', $student->exists ? 'Edit Student' : 'Add Student')
@section('breadcrumb','Academic / Students / Form')
@section('content')

<div class="page-card animate-in">
    <div class="page-card-header">
        <h6 class="page-card-header-title">
            <span class="header-icon primary"><i
                    class="fas fa-{{ $student->exists ? 'pen-to-square' : 'user-plus' }}"></i></span>
            {{ $student->exists ? 'Edit Student' : 'New Student' }}
        </h6>
    </div>
    <div class="page-card-body">
        <form method="POST" enctype="multipart/form-data"
            action="{{ $student->exists ? route('students.update', $student) : route('students.store') }}"
            class="row g-3">
            @csrf
            @if($student->exists) @method('PUT') @endif

            {{-- Personal Info --}}
            <div class="col-12">
                <div class="form-section-title">Personal Information</div>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="first_name">First Name</label>
                <input name="first_name" id="first_name" class="form-control"
                    value="{{ old('first_name', $student->first_name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="last_name">Last Name</label>
                <input name="last_name" id="last_name" class="form-control"
                    value="{{ old('last_name', $student->last_name) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="dob">Date of Birth</label>
                <input name="dob" id="dob" type="date" class="form-control"
                    value="{{ old('dob', optional($student->dob)->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="gender">Gender</label>
                <select name="gender" id="gender" class="form-select">
                    <option value="">Select</option>
                    @foreach(['male','female','other'] as $g)
                    <option value="{{ $g }}" @selected(old('gender', $student->gender) === $g)>{{ ucfirst($g) }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label" for="status">Status</label>
                <select name="status" id="status" class="form-select" required>
                    @foreach(['active','inactive','suspended'] as $s)
                    <option value="{{ $s }}" @selected(old('status', $student->status ?? 'active') === $s)>{{
                        ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Contact Info --}}
            <div class="col-12 mt-4">
                <div class="form-section-title">Contact Details</div>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="email">Email Address</label>
                <input name="email" id="email" type="email" class="form-control"
                    value="{{ old('email', $student->email) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="phone">Phone Number</label>
                <input name="phone" id="phone" class="form-control" value="{{ old('phone', $student->phone) }}">
            </div>
            <div class="col-12">
                <label class="form-label" for="address">Address</label>
                <textarea name="address" id="address" class="form-control"
                    rows="2">{{ old('address', $student->address) }}</textarea>
            </div>

            {{-- Profile Picture --}}
            <div class="col-12 mt-4">
                <div class="form-section-title">Profile Picture</div>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="profile_picture">Upload Image</label>
                <input name="profile_picture" id="profile_picture" type="file" class="form-control" accept="image/*">
            </div>

            {{-- Actions --}}
            <div class="col-12">
                <div class="form-actions">
                    <button type="submit" class="btn-action btn-action-primary">
                        <i class="fas fa-{{ $student->exists ? 'save' : 'plus' }}"></i>
                        {{ $student->exists ? 'Update Student' : 'Create Student' }}
                    </button>
                    <a href="{{ route('students.index') }}" class="btn-action btn-action-outline">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection