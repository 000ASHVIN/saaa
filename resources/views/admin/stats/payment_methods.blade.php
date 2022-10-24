@extends('admin.layouts.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="/assets/admin/vendor/bootstrap-daterangepicker/daterangepicker.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
@endsection

@section('title', 'Payment Methods')
@section('description', 'This is all the payments methods for active subscriptions')

@section('content')
    <div class="container-fluid container-fullw bg-white ng-scope">
        <div class="row">
            <div class="col-sm-4">
                <div class="panel panel-white no-radius text-center">
                    <div class="panel-body">
                        <span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-smile-o fa-stack-1x fa-inverse"></i> </span>
                        <h2 class="StepTitle">Debit Orders</h2>
                        <p class="text-small">
                            <strong>Total: {{ $debit_orders->count() }}</strong>
                        </p>
                        <p class="links cl-effect-1">
                            <a href="{{ route('admin.stats.payment_methods.show', $debit_orders->first()->payment_method) }}">
                                View Subscribers
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-white no-radius text-center">
                    <div class="panel-body">
                        <span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-paperclip fa-stack-1x fa-inverse"></i> </span>
                        <h2 class="StepTitle">Credit Cards</h2>
                        <p class="text-small">
                            <strong>Total: {{ $credit_cards->count() }}</strong>
                        </p>
                        <p class="cl-effect-1">
                            <a href="{{ route('admin.stats.payment_methods.show', $credit_cards->first()->payment_method) }}">
                                View Subscribers
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-white no-radius text-center">
                    <div class="panel-body">
                        <span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i class="fa fa-terminal fa-stack-1x fa-inverse"></i> </span>
                        <h2 class="StepTitle">Electronic Funds Transfer</h2>
                        <p class="text-small">
                            {{ $eft->count() }}
                        </p>
                        <p class="links cl-effect-1">
                            <a href="{{ route('admin.stats.payment_methods.show', $eft->first()->payment_method) }}">
                                View Subscribers
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid container-fullw padding-bottom-10 ng-scope">
        <div class="row"></div>
    </div>

    <div class="container-fluid container-fullw bg-white ng-scope">
        <div class="row">
            <h4 class="text-center">
                Download subscription payment method outstanding list.
                <hr>
                {!! Form::open(['method' => 'post', 'route' => 'admin.stats.payment_methods.plan.export']) !!}
                <div class="form-inline">
                    <div class="form-group @if ($errors->has('plan')) has-error @endif">
                        {!! Form::select('plan', $plans->pluck('custom_name', 'id'), ['class' => 'form-control', 'style' => '']) !!}
                        @if ($errors->has('plan')) <p class="help-block">{{ $errors->first('plan') }}</p> @endif
                    </div>
                    {!! Form::submit('Download List', ['class' => 'btn btn-info']) !!}
                </div>
                {!! Form::close() !!}
            </h4>
            <hr>

            <table class="table table-bordered table-hover table-striped" id="payment_methods">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Email Address</th>
                        <th>Cellphone</th>
                        <th>Plan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subscriptions as $subscription)
                        <tr>
                            <td>{{ $subscription->first_name.' '. $subscription->last_name }}</td>
                            <td><a href="#">{{ $subscription->email }}</a></td>
                            <td>{{ $subscription->cell }}</td>
                            <td>{{ $subscription->subscriptions->first()->plan->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td>Full Name</td>
                    <td>Email Address</td>
                    <td>Cellphone</td>
                    <td>Plan</td>
                </tr>
                </tfoot>
            </table>

        </div>
    </div>
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            $('#payment_methods').DataTable();
        });
    </script>
@endsection 