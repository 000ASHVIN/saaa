@extends('admin.layouts.master')

@section('title', 'Payments Summary')
@section('description', 'View Payments Summary that was allocated on each day')

@section('content')
    <div class="container-fluid container-fullw bg-white">
        {!! Form::open(['Method' => 'post', 'route' => 'admin.payments.payments_per_day.post_summary']) !!}

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! form::label('from', 'Please select a from date') !!}
                    {!! Form::input('text', 'from', null, ['class' => 'form-control daterange']) !!}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {!! form::label('to', 'Please select a to date') !!}
                    {!! Form::input('text', 'to', null, ['class' => 'form-control daterange']) !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::submit('View Results', ['class' => 'btn btn-default']) !!}
        </div>

        {!! Form::close() !!}
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="/assets/admin/vendor/moment/moment.min.js"></script>
    <script type="text/javascript" src="/assets/admin/vendor/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
    <script>
        $(function() {
            $('.daterange').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD'
                },
            });
        });
    </script>
@endsection