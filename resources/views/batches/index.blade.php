@extends('layouts.app')
@section('title','Batches')
@section('breadcrumb','Academic / Batches')
@section('content')
@include('partials.simple-index', ['title'=>'Batches','createRoute'=>route('batches.create'),'rows'=>$batches,'columns'=>['batch_code'=>'Batch','year'=>'Year','intake'=>'Intake','start_date'=>'Start','status'=>'Status'],'editRouteName'=>'batches.edit','deleteRouteName'=>'batches.destroy'])
@endsection
