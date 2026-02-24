@php
$fields = [
    ['name'=>'course_id','label'=>'Course','type'=>'select','options'=>$courses->pluck('title','id')->toArray()],
    ['name'=>'batch_code','label'=>'Batch Code'],
    ['name'=>'year','label'=>'Year','type'=>'number'],
    ['name'=>'intake','label'=>'Intake','type'=>'select','options'=>['Jan'=>'Jan','May'=>'May','Sep'=>'Sep']],
    ['name'=>'start_date','label'=>'Start Date','type'=>'date'],
    ['name'=>'end_date','label'=>'End Date','type'=>'date'],
    ['name'=>'status','label'=>'Status','type'=>'select','options'=>['active'=>'Active','completed'=>'Completed','planned'=>'Planned']],
];
@endphp
@extends('layouts.app')
@section('title', $batch->exists ? 'Edit Batch' : 'Add Batch')
@section('breadcrumb', 'Academic / Batches / Form')
@section('content')
@include('partials.simple-form', ['model'=>$batch,'storeRoute'=>'batches.store','updateRoute'=>'batches.update','fields'=>$fields])
@endsection
