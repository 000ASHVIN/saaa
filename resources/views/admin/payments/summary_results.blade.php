@extends('admin.layouts.master')

@section('title', 'Payments')
@section('description', 'View Payments that was allocated on each day')

@section('content')
    <div class="container-fluid container-fullw bg-white">
        {!! Form::open(['Method' => 'post', 'route' => 'admin.payments.payments_per_day.post_summary']) !!}

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! form::label('from', 'Please select a from date') !!}
                    {!! Form::input('text', 'from', ($from ? : null), ['class' => 'form-control daterange']) !!}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {!! form::label('to', 'Please select a to date') !!}
                    {!! Form::input('text', 'to', ($to ? : null), ['class' => 'form-control daterange']) !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::submit('View Results', ['class' => 'btn btn-default']) !!}
        </div>

        {!! Form::close() !!}
    </div>

    <div class="container-fluid container-fullw">
        <div class="alert alert-warning">
            <p class="text-center">You have selected from <strong>{{ date_format($from, 'd F Y') }} to {{ date_format($to, 'd F Y') }}</strong></p>
        </div>
        <table class="table table-striped">
            <thead>
                <th></th>
                <th>Subscription</th>
                <th>Store</th>
                <th>Event</th>
                <th>Total</th>
            </thead>
            <tbody>
            @foreach($transactions->groupBy('method') as $key => $value)
                @if($key)
                    <tr>
                        <td>{{ strtoupper(str_replace('_', ' ', $key)) }}</td>
                        <td>{{ (count($value->where('category', 'subscription')) ? 'R'.number_format($value->where('category', 'subscription')->sum('amount'), 2): " - ") }}</td>
                        <td>{{ (count($value->where('category', 'store')) ? 'R'.number_format($value->where('category', 'store')->sum('amount'), 2): " - ") }}</td>
                        <td>{{ (count($value->where('category', 'event')) ? 'R'.number_format($value->where('category', 'event')->sum('amount'), 2) : " - ") }}</td>
                        <td><strong>{{ number_format($value->sum('amount'), 2) }}</strong></td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td><strong>Total</strong></td>
                <td>{{ (count($transactions->where('category', 'subscription')) ? 'R'.number_format($transactions->where('category', 'subscription')->sum('amount'), 2): " - ") }}</td>
                <td>{{ (count($transactions->where('category', 'store')) ? 'R'.number_format($transactions->where('category', 'store')->sum('amount'), 2): " - ") }}</td>
                <td>{{ (count($transactions->where('category', 'event')) ? 'R'.number_format($transactions->where('category', 'event')->sum('amount'), 2): " - ") }}</td>
                <td><strong>R{{ number_format($transactions->sum('amount'), 2) }}</strong></td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="container-fluid container-fullw bg-white">
        <div class="padding-top-30"></div>
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