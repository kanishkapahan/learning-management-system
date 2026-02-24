@php
$fields = [
    ['name'=>'employee_no','label'=>'Employee No'],
    ['name'=>'name','label'=>'Name'],
    ['name'=>'email','label'=>'Email','type'=>'email'],
    ['name'=>'phone','label'=>'Phone'],
    ['name'=>'department','label'=>'Department'],
    ['name'=>'specialization','label'=>'Specialization'],
    ['name'=>'status','label'=>'Status','type'=>'select','options'=>['active'=>'Active','inactive'=>'Inactive']],
];
@endphp
@extends('layouts.app')
@section('title', $lecturer->exists ? 'Edit Lecturer' : 'Add Lecturer')
@section('breadcrumb', 'Academic / Lecturers / Form')
@section('content')
@include('partials.simple-form', ['model'=>$lecturer,'storeRoute'=>'lecturers.store','updateRoute'=>'lecturers.update','fields'=>$fields])
@endsection
