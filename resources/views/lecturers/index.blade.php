@extends('layouts.app')
@section('title','Lecturers')
@section('breadcrumb','Academic / Lecturers')
@section('content')
@include('partials.simple-index', ['title'=>'Lecturers','createRoute'=>route('lecturers.create'),'rows'=>$lecturers,'columns'=>['employee_no'=>'Employee No','name'=>'Name','email'=>'Email','department'=>'Department','status'=>'Status'],'editRouteName'=>'lecturers.edit','deleteRouteName'=>'lecturers.destroy'])
@endsection
