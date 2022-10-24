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
                <fieldset>
                    <legend>Subscription payment method</legend>
                    {!! Form::open(['method' => 'post', 'route' => ['admin.update_payment_method', $member->id]]) !!}
                    <div class="form-group">
                        {!! Form::label('payment_method', 'Payment Method') !!}
                        <small class="pull-right">Payment method to charge for cpd subscription</small>
                        {!! Form::select('payment_method', [
                            null => 'Please Select',
                            'eft' => 'Electronic Funds Transfer (EFT)',
                            'debit_order' => 'Debit Order',
                            'credit_card' => 'Credit Card',
                            'other' => 'Other',
                        ],$member->payment_method, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::submit('Update', ['class' => 'btn btn-success']) !!}
                    </div>
                    {!! Form::close() !!}
                </fieldset>
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