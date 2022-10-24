@extends('admin.layouts.master')

@section('title', 'Create a new Live Webinar')
@section('description', 'Create a new webinar')

@section('styles')
    <link href="/assets/admin/assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="/assets/admin/assets/plugins/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="row">
        <div class="panel-white col-md-12">
            <br>
            {!! Form::open(['method' => 'post', 'route' => 'admin.live_webinars.store']) !!}
                @include('admin.live_webinars.partials.form', ['button' => 'Create Webinar'])
            {!! Form::close() !!}
            <br>
        </div>
    </div>
@stop
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