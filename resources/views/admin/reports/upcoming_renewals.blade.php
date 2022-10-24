@extends('admin.layouts.master')

@if ($type == 'past')
    @section('title', 'Past Renewals')
    @section('description', 'A list of past renewal data for a specific time frame or annual')
@else
    @section('title', 'Upcomming Renewals')
    @section('description', 'A list of upcoming renewal data for a specific time frame or annual')
@endif


@section('css')
    <meta name="csrf-token" content="{{csrf_token()}}">
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
 
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <p><strong>Summary</strong></p>
            <hr>
            <p>This report will provide the following: </p>
            <ul>
                @if ($type == 'past')
                    <li>All past renewals.</li>
                @else
                    <li>All upcoming renewal from todays till one month.</li>
                @endif
                {{-- <li>On submit this will provide extract with data.</li> --}}
            </ul>
        </div>
    </div>

    <div class="container-fluid container-fullw padding-bottom-5"></div>

    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        {{-- <div class="row">
            {!! Form::open(['method' => 'post', 'route' => 'admin.reports.upcoming_renewal']) !!}
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
                <button class="btn btn-success">Extract Report</button>
            </div>

            {!! Form::close() !!}
        </div> --}}

        <div class="row padding-bottom-20">
            <div class="col-md-9">
                <div class="row">
                    {!! Form::open(['method' => 'get']) !!}
                        <div class="col-md-3">
                            {!! Form::label('from', 'Select From Date') !!}
                            {!! Form::input('text', 'from', $from, ['class' => 'form-control is-date']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! Form::label('to', 'Select To Date') !!}
                            {!! Form::input('text', 'to', $to, ['class' => 'form-control is-date']) !!}
                        </div>
                        <div class="col-md-3">
                            {!! Form::label('Submit', 'Submit', ['style' => 'opacity:0;']) !!}<br/>
                            {!! Form::submit('Search', ['class' => 'btn btn-success']) !!}
                            @if ($from && $to)
                                @if ($type == 'past')
                                    <a href="{{ route('admin.reports.upcoming_renewal', 'past') }}" class="btn btn-warning">Clear Search</a>
                                @else
                                    <a href="{{ route('admin.reports.upcoming_renewal') }}" class="btn btn-warning">Clear Search</a>
                                @endif
                            @endif
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="col-md-3">
                @if ($type == 'past')
                    <a href="{{ route('admin.reports.upcoming_renewal') }}" class="btn btn-primary">Upcoming Renewals</a>
                    <a href="{{ route('admin.reports.upcoming_renewal.export', 'past') }}?from={{ $from }}&to={{ $to }}" class="btn btn-default">Export</a>
                @else
                    <a href="{{ route('admin.reports.upcoming_renewal', 'past') }}" class="btn btn-primary">Past Renewals</a>
                    <a href="{{ route('admin.reports.upcoming_renewal.export') }}?from={{ $from }}&to={{ $to }}" class="btn btn-default">Export</a>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped upcoming_renewal_table">
                    <thead>
                        <th>
                            First&nbsp;Name
                        </th>
                        <th>
                            Last&nbsp;Name
                        </th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Plan&nbsp;Name</th>
                        <th>Plan Price</th>
                        <th>Subscription Type</th>
                        <th>Subscription Start Date</th>
                        <th>Subscription End Date</th>
                        <th>Agent Name</th>
                        <th>Agent Email</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/js/app.js"></script>
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            $('.is-date').datepicker;
        });
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        });
        $('.upcoming_renewal_table').DataTable({
            serverSide: true,
            'processing': true,
            'lengthChange': false,
            'info': false,
            'scrollX': true,
            'searching': true,
            "ajax": {
                "url": '/admin/reports/datatable_upcoming_renewal{{ $type ? "/".$type : '' }}?from={{ $from }}&to={{ $to }}',
                "dataSrc": function(json) {
                    let data = json.data;
                    var filtered = data.filter(function(value, index, arr){
                        return value.active_plan == null;
                    });
                    return filtered;
                }
            },
            columns: [
                {data: 'first_name', searchable:false},
                {data: 'last_name', searchable:false},
                {data: 'email', searchable:false},
                {data: 'cell', searchable:false},
                {data: 'plan_name', searchable:false},
                {data: 'plan_price', searchable:false},
                {data: 'subscription_type', searchable:false},
                {data: 'starts_at', searchable:false},
                {data: 'ends_at', searchable:false},
                {data: 'agent_name', searchable:false},
                {data: 'agent_email', searchable:false}
            ],
            columnDefs: [
                {orderable: false, targets: -1, "searchable": false},
                {orderable: false, targets: -2, "seachable": false},
                {
                    "render": function ( data, type, row ) {
                        data = data.charAt(0).toUpperCase() + data.substr(1);
                        return data+'ly';
                    },
                    "targets": 6
                }
            ]
        });
    </script>
@stop