@extends('admin.layouts.master')

@section('title', 'Custom Debt Export')
@section('description', 'Export a custom debt report within a specified timeframe')

@section('content')

    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <p><strong>Report Summary</strong></p>
            <hr>
            <p>This report extract will provide the following: </p>
            <ul>
                <li>The total amount of credits from selected date.</li>
                <li>The total amount of credits to selected date.</li>
                <li>The oustanding balance.</li>
            </ul>
        </div>
    </div>

    <div class="container-fluid container-fullw padding-bottom-5"></div>

    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            {!! Form::open(['method' => 'post', 'route' => 'admin.reports.payments.custom-transactions-export']) !!}
                <div class="col-md-6">
                    <div class="form-group @if ($errors->has('from')) has-error @endif">
                        {!! Form::label('from', 'Select From Date') !!}
                        {!! Form::input('text', 'from', null, ['class' => 'form-control is-date']) !!}
                        @if ($errors->has('from')) <p class="help-block">{{ $errors->first('from') }}</p> @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group @if ($errors->has('to')) has-error @endif">
                        {!! Form::label('to', 'Select To Date') !!}
                        {!! Form::input('text', 'to', null, ['class' => 'form-control is-date']) !!}
                        @if ($errors->has('to')) <p class="help-block">{{ $errors->first('to') }}</p> @endif
                    </div>
                </div>

            <div class="col-md-12">
                {!! Form::submit('Extract Report', ['class' => 'btn btn-success']) !!}
            </div>

            {!! Form::close() !!}
        </div>
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