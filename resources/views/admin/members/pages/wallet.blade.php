@extends('admin.layouts.master')
@section('title', $member->first_name . ' ' . $member->last_name)
@section('description', 'User Profile')

@section('css')
    <link href="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <style>
        .daterangepicker{
            z-index:99999!important;
        }
    </style>
@stop

@section('content')

    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            @include('admin.members.includes.nav')
            <div class="col-md-9 col-sm-9 nopadding">
                <div class="col-sm-12 col-md-12">
                    {!! Form::open(['method' => 'post', 'route' => ['dashboard.wallet.store', $member->id]]) !!}
                    <fieldset>
                        <legend>Load Money to wallet</legend>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group @if ($errors->has('date')) has-error @endif">
                                    {!! Form::label('date', 'Payment Date') !!}
                                    {!! Form::input('text', 'date', null, ['class' => 'form-control is-date']) !!}
                                    @if ($errors->has('date')) <p class="help-block">{{ $errors->first('date') }}</p> @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @if ($errors->has('amount')) has-error @endif">
                                    {!! Form::label('amount', 'Amount') !!}
                                    {!! Form::input('text', 'amount', null, ['class' => 'form-control', 'placeholder' => 'R500.00']) !!}
                                    @if ($errors->has('amount')) <p class="help-block">{{ $errors->first('amount') }}</p> @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group @if ($errors->has('method')) has-error @endif">
                                    {!! Form::label('method', 'Deposit Type') !!}
                                    {!! Form::select('method', [
                                        'eft' => 'Offline EFT Payment',
                                        'instant_eft' => 'Instant EFT',
                                        'debit' => 'Debit Order',
                                        'cc' => 'Credit Card',
                                        'cash' => 'Cash Received',
                                        'snap_scan' => 'SnapScan',
                                        'pebble' => 'Pebble',
                                        'pebble' => 'Pebble',
                                        'wallet_credit' => 'Wallet Credit',
                                    ],null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('method')) <p class="help-block">{{ $errors->first('method') }}</p> @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-o btn-primary" onclick="spin(this)";>
                                    Deposit Funds
                                </button>
                            </div>
                        </div>
                    </fieldset>
                    {!! Form::close() !!}

                    <fieldset>
                        <legend>Wallet Withdrawal</legend>
                        {!! Form::open(['method' => 'POST', 'route' => ['dashboard.wallet.withdrawal', $member->id]]) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group @if ($errors->has('refund_amount')) has-error @endif">
                                    {!! Form::label('refund_amount', 'Amount') !!}
                                    {!! Form::input('text', 'refund_amount', null, ['class' => 'form-control', 'placeholder' => 'R100.00']) !!}
                                    @if ($errors->has('refund_amount')) <p class="help-block">{{ $errors->first('refund_amount') }}</p> @endif
                                </div>

                                <div class="form-group @if ($errors->has('reason')) has-error @endif">
                                    {!! Form::label('reason', 'Withdrawal Reason') !!}
                                    <textarea name="reason" id="reason" cols="10" class="form-control" placeholder="User requested a refund"></textarea>
                                    @if ($errors->has('reason')) <p class="help-block">{{ $errors->first('reason') }}</p> @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-o btn-warning" onclick="spin(this)";>
                                    Withdrawal Funds
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </fieldset>

                    <fieldset>
                        <legend>Latest 10 Transactions</legend>
                        <table class="table table-striped">
                            <thead>
                            <th>Date</th>
                            <th>#INV</th>
                            <th>#PO</th>
                            <th>Type</th>
                            <th>Method</th>
                            <th>Category</th>
                            <th>Amount</th>
                            </thead>
                            <tbody>
                            @if(count($member->wallet->transactions))
                                @foreach($member->wallet->transactions()->orderBy('id', 'desc')->get()->take(9) as $transaction)
                                    <tr class="{{ $transaction->type == 'credit'? "success" : "danger" }}">
                                        <td>{{ date_format($transaction->created_at, 'd F Y') }}</td>
                                        <td>{{ $transaction->invoice_reference }}</td>
                                        <td>{{ $transaction->invoice_order_refference }}</td>
                                        <td>{{ ucfirst($transaction->type) }}</td>
                                        <td>{{ ucwords(str_replace('_', ' ', $transaction->method)) }}</td>
                                        <td>{{ ucfirst($transaction->category) }}</td>
                                        <td>R {{ number_format($transaction->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7">Your U-Wallet does not have any transactions available.</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </fieldset>

                    <p style="text-align: center">
                        <a class="btn btn-default btn-block" href="{{ route('dashboard.wallet.export', $member->id) }}"><i class="fa fa-file-excel-o"></i> Export All Transactions</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script src="/assets/admin/assets/js/profile.js"></script>
    <script src="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.js"></script>
    <script src="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            Profile.init();
        });
    </script>
    <script src="/assets/admin/assets/js/bootstrap-confirm-delete.js"></script>
    @include('admin.members.includes.spin')
@stop