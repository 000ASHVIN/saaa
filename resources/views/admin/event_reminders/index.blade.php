@extends('admin.layouts.master')

@section('title', 'Webianr Reminder Report')
@section('description', 'Webinars')

@section('styles')
    <style>
        .dataTables_filter{
            float: right;
        }

        input.form-control.input-sm {
            width: 500px;
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('content')
    <section>
        <br>
        <div class="container">
            <div class="panel-group" id="webinar_report">
                @foreach($reports as $key => $value)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#webinar_report" href="#{{str_replace(' ','', $key)}}">{{ $key }}</a>
                                {!! Form::open(['method' => 'post', 'route' => ['clear_reminders', $key]]) !!}
                                <div class="pull-right" style="margin-top: -25px;">
                                    <a class="btn btn-success" data-toggle="collapse" href="#{{str_replace(' ','', $key)}}">Total: {{ count($value) }}</a>
                                    {!! Form::submit('Clear Records', ['class' => 'btn btn-info']) !!}
                                {!! Form::close() !!}
                                </div>
                            </h4>
                        </div>
                        <div id="{{str_replace(' ','', $key)}}" class="panel-collapse collapse">
                            <div class="panel-body panel-white">
                                <table class="table">
                                    <thead>
                                        <th>Name</th>
                                        <th>Email Address</th>
                                        <th>Subscription</th>
                                        <th>Mobile</th>
                                        <th>Subject</th>
                                        <th>Event Name</th>
                                        <th>Sent at</th>
                                    </thead>
                                    <tbody>
                                        @foreach($value as $member)
                                            <tr>
                                                <td>{{ $member->full_name }}</td>
                                                <td>{{ $member->email_address }}</td>
                                                <td>{{ $member->subscription }}</td>
                                                <td>{{ $member->mobile }}</td>
                                                <td>{{ $member->subject_line }}</td>
                                                <td>{{ $member->event_name }}</td>
                                                <td>{{ $member->created_at }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@stop

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
        $(document).ready(function() {
            $('.mytable').DataTable( {
                "paging":   false,
                "ordering": true,
                "info":     true,
                "order": [[ 1, 'asc' ]]
            } );
        } );

    </script>

    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
@endsection