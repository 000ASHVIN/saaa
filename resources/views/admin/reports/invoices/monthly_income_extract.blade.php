@extends('admin.layouts.master')

@section('title', 'Monthly Income')
@section('description', 'Extract a list of invoices for a specific year')

@section('content')

    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <p><strong>Export Summary</strong></p>
            <hr>
            <p>This report will provide the following: </p>
            <ul>
                <li>All Invoices from the selected year.</li>
                <li>On submit this will provide extract with data.</li>
            </ul>
        </div>
    </div>

    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            {!! Form::open(['method' => 'post', 'route' => 'admin.reports.payments.post_monthly_income_report']) !!}
            <div class="col-md-6">
                <div class="form-group @if ($errors->has('year')) has-error @endif">
                    {!! Form::label('year', 'Select Year') !!}
                    {!! Form::select('year', [
                        2016 => 2016,
                        2017 => 2017,
                        2018 => 2018,
                        2019 => 2019,
                        2020 => 2020,
                        2021 => 2021,
                        2022 => 2022
                    ],2022, ['class' => 'form-control']) !!}
                    @if ($errors->has('year')) <p class="help-block">{{ $errors->first('year') }}</p> @endif

                </div>
            </div>

            <div class="col-md-12">
                <button type="submit" name="submit" value="export_report" class="btn btn-success">Extract Report</button>
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
        });
    </script>
@stop