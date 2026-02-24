@php
$fields = [
    ['name'=>'course_code','label'=>'Course Code'],
    ['name'=>'title','label'=>'Title'],
    ['name'=>'description','label'=>'Description','type'=>'textarea','col'=>'col-12'],
    ['name'=>'credits','label'=>'Credits','type'=>'number'],
    ['name'=>'level','label'=>'Level','type'=>'number'],
    ['name'=>'semester','label'=>'Semester','type'=>'number'],
    ['name'=>'lecturer_id','label'=>'Lecturer','type'=>'select','options'=>[''=>'Select'] + $lecturers->pluck('name','id')->toArray()],
    ['name'=>'status','label'=>'Status','type'=>'select','options'=>['active'=>'Active','inactive'=>'Inactive']],
];
@endphp
@extends('layouts.app')
@section('title', $course->exists ? 'Edit Course' : 'Add Course')
@section('breadcrumb', 'Academic / Courses / Form')
@section('content')
@include('partials.simple-form', ['model'=>$course,'storeRoute'=>'courses.store','updateRoute'=>'courses.update','fields'=>$fields])
@endsection
