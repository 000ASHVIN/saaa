@extends('admin.layouts.master')

@section('title', 'Payment Method'.' '.str_replace('_', ' ', $users->first()->payment_method))
@section('description', 'This is all the debit order details that we currently have on record')


@section('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <section>
        <br>
        <div class="container">
            <div class="panel-default" id="accordion">
                {{--<p class="pull-right">--}}
                    {{--<span class="pull-right">--}}
                        {{--{!! Form::open(['method' => 'post', 'route' => ['admin.stats.payment_methods.export', $subscriptions->first()->payment_method]]) !!}--}}
                            {{--<div class="form-group pull-right">--}}
                                {{--{!! Form::submit('Export', ['class' => 'text-right btn btn-success']) !!}--}}
                            {{--</div>--}}
                        {{--{!! Form::close() !!}--}}
                    {{--</span>--}}
                {{--</p>--}}
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <th>Full Name</th>
                    <th>Email Address</th>
                    <th>Cell Phone</th>
                    <th>Method</th>
                    @if($users->first()->payment_method == 'debit_order')
                        <th>Billable Date</th>
                    @endif
                    <th>Plan</th>
                    <th>Interval</th>
                    <th>Price</th>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Full Name</th>
                        <th>Email Address</th>
                        <th>Cell Phone</th>
                        <th>Method</th>
                        @if($users->first()->payment_method == 'debit_order')
                            <th>Billable Date</th>
                        @endif
                        <th>Plan</th>
                        <th>Interval</th>
                        <th>Price</th>
                    </tr>
                    </tfoot>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td><a href="{{ route('admin.members.show', $user->id) }}">{{ $user->first_name.' '.$user->last_name }}</a></td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ($user->cell ? : "No Cellphone") }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $user->payment_method)) }}</td>
                                @if($user->payment_method == 'debit_order')
                                    @if($user->debit)
                                        <td>{{ $user->debit->billable_date }}</td>
                                    @else
                                        <td>Null</td>
                                    @endif
                                @endif
                                <td>{{ ($user->subscription('cpd')->plan ? $user->subscription('cpd')->plan->name : "Plan has been removed") }}</td>
                                <td>{{ ($user->subscription('cpd')->plan ? $user->subscription('cpd')->plan->interval : "Plan has been removed") }}</td>
                                <td>{{ ($user->subscription('cpd')->plan ? $user->subscription('cpd')->plan->price : "Plan has been removed") }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


    </section>
@stop

@section('scripts')

    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });

        $(document).ready(function() {
            $('#example').DataTable();
        } );
    </script>


@stop