@extends('admin.layouts.master')

@section('title', 'Rewards Export')
@section('description', 'All Rewards Reports')

@section('content')

<div class="container-fluid container-fullw padding-bottom-10 bg-white">
    <div class="row">
        <p><strong>Export Summary</strong></p>
        <hr>
        <p>This report will provide the following: </p>
        <ul>
            <li>All Rewards Reports</li>
            <li>On submit this will provide extract with data.</li>
        </ul>
    </div>
</div>

<div class="container-fluid container-fullw padding-bottom-5"></div>

<div class="container-fluid container-fullw padding-bottom-10 bg-white">
    {!! Form::open(['method' => 'post', 'route' => 'admin.reports.reward_export']) !!}
    <div class="row">
        <div class="col-md-4">
            <div class="form-group @if ($errors->has('from')) has-error @endif">
                {!! Form::label('product', 'Product interested in:') !!}
                {!! Form::select('product', $product, null, ['class' => 'form-control']) !!}
                @if ($errors->has('product')) <p class="help-block">{{ $errors->first('product') }}</p> @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group @if ($errors->has('from')) has-error @endif">
                {!! Form::label('from', 'Select From Date') !!}
                {!! Form::input('text', 'from', null, ['class' => 'form-control is-date']) !!}
                @if ($errors->has('from')) <p class="help-block">{{ $errors->first('from') }}</p> @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group @if ($errors->has('to')) has-error @endif">
                {!! Form::label('to', 'Select To Date') !!}
                {!! Form::input('text', 'to', null, ['class' => 'form-control is-date']) !!}
                @if ($errors->has('to')) <p class="help-block">{{ $errors->first('to') }}</p> @endif
            </div>
        </div>

        <div class="col-md-12">
            {!! Form::submit('Extract Report', ['class' => 'btn btn-success']) !!}
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
            $('.is-date').datepicker;
        });
</script>
@stop