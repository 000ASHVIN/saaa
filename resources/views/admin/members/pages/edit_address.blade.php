@extends('admin.layouts.master')
@section('title', $member->first_name . ' ' . $member->last_name)
@section('description', 'User Profile')

@section('css')
    <link href="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <style>
        .daterangepicker{
            z-index:99999!important;
        }
    </style>
@stop

@section('content')

    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            @include('admin.members.includes.nav')
            <div class="col-md-9 col-sm-9 nopadding">

                {!! Form::model($addresses, ['method' => 'post', 'route' => ['member.addresses.update',$addresses->id]]) !!}
             @include("admin.members.pages.includes.form",['button'=>'Update Address']);
            {!! Form::close() !!}
       
                
            </div>
        </div>
    </div>

    {{--@include('admin.members.includes.statement.confirm')--}}
@stop

@section('scripts')
    <script src="/admin/assets/js/index.js"></script>
    <script src="/js/app.js"></script>
    <script src="/assets/admin/assets/js/profile.js"></script>
    <script src="/assets/admin/assets/js/bootstrap-confirm-delete.js"></script>
    @include('admin.members.includes.spin')
@stop