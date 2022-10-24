@extends('admin.layouts.master')

@section('title', 'Webinar On Demand Export')
@section('description', 'All Webinar On Demand Reports')

@section('content')

<div class="container-fluid container-fullw padding-bottom-10 bg-white">
    <div class="row">
        <p><strong>Export Summary</strong></p>
        <hr>
        <p>This report will provide the following: </p>
        <ul>
            <li>All Webinar On Demand report</li>
            <li>On submit this will provide extract with data.</li>
        </ul>
    </div>
</div>

<div class="container-fluid container-fullw padding-bottom-5"></div>

<div class="container-fluid container-fullw padding-bottom-10 bg-white">
    {!! Form::open(['method' => 'post', 'route' => 'admin.reports.post_wod_export']) !!}
    <div class="row">

        <div class="col-md-12">
            {!! Form::submit('Extract Webinar On Demand Report', ['class' => 'btn btn-success']) !!}
        </div>

    </div>
    {!! Form::close() !!}
</div>

@endsection

@section('scripts')
<script src="/js/app.js"></script>
<script>
    jQuery(document).ready(function () {
            Main.init();
        });
</script>
@stop