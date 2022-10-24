@extends('admin.layouts.master')

@section('title', 'Create Event')
@section('description', 'New Event')

@section('styles')
    <link href="/assets/admin/assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="/assets/admin/assets/plugins/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid container-fullw bg-white ng-scope">
        {!! Form::open(['method' => 'post', 'route' => 'admin.event.store']) !!}
            <div class="row">
                @include('admin.event.includes.event-information', ['submit' => 'Create Event'])
            </div>
        {!! Form::close() !!}
    </div>
@endsection

@section('scripts')
    <script src="/assets/admin/assets/plugins/clockpicker/dist/jquery-clockpicker.min.js"></script>
    <script src="/assets/admin/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>

    <script type="text/javascript">
        $('.select2').select2();
        $('.is-date').datepicker;
        $('.timepicker').clockpicker();
    </script>
@endsection