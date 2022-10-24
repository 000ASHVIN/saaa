@extends('app')

@section('title')

@stop

@section('content')
    <section style="background:url('/assets/frontend/images/demo/wall2.jpg')">
        <div class="display-table">
            <div class="display-table-cell vertical-align-middle">
                <div class="container">
                    <div class="row">
                        <div class="col-md-7 col-md-offset-2">

                            <div class="box-static box-border-top padding-30 text-center">
                                <div class="box-title margin-bottom-30">
                                    <h2 class="size-20">Full Payment</h2>
                                </div>

                                <span class="text-center">
                                    <p>
                                        Kindly choose one of the following payment options below in order
                                        <br> to settle your account.
                                    </p>

                                    <a class="btn btn-info" href="{{ route('dashboard.invoices') }}"><i
                                                class="fa fa-credit-card"></i> Pay via credit card</a>

                                    <a class="btn btn-info" href="{{ route('dashboard.settle_using_eft_payment_option', $user) }}"><i
                                                class="fa fa-bank"></i> Pay via EFT</a>
                                </span>

                                <hr>
                                <a class="btn btn-primary" href="{{ URL::previous() }}"><i class="fa fa-arrow-left"></i>
                                    Change payment option</a>

                                <a class="btn btn-warning" target="_blank" href="{{ route('dashboard.invoices') }}">View
                                    View My Invoices</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop