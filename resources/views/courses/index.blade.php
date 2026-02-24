@extends('layouts.app')
@section('title','Courses')
@section('breadcrumb','Academic / Courses')
@section('content')
@include('partials.simple-index', ['title'=>'Courses','createRoute'=>route('courses.create'),'rows'=>$courses,'columns'=>['course_code'=>'Code','title'=>'Title','credits'=>'Credits','semester'=>'Semester','status'=>'Status'],'editRouteName'=>'courses.edit','deleteRouteName'=>'courses.destroy'])
@endsection
