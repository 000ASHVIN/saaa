@extends('app')

@section('title', 'Settle Order #'.$order->reference)

@section('content')
    <section style="background-image: url('/assets/frontend/images/demo/wall2.jpg');">
        <app-order-pay :order="{{ $order->toJson() }}" :user="{{ auth()->user()->load('cards') }}" inline-template>
            <div id="app-register-screen" class="container app-screen">

                <div class="col-md-8 col-md-offset-2">

                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="pull-left">
                                    Details
                                </div>

                                <div class="clearfix"></div>
                            </div>

                            <div class="panel-body">
                                <div class="col-sm-12 col-md-6">
                                    <p class="text-center"><strong>Order status:</strong> {{ ucfirst($order->status) }}</p>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <p class="text-center"><strong>Order
                                            total:</strong> R {{ number_format($order->total, 2) }}</p>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <p class="text-center"><strong>Payments
                                            total:</strong> {{ money_format('%.2n', $paymentsTotal) }}</p>
                                </div>
                                <div class="col-sm-12 col-md-6">
                                    <p class="text-center"><strong>Balance
                                            due:</strong> R {{ number_format($order->total - $order->discount, 2) }}</p>
                                </div>
                                <div class="col-sm-12">
                                    <iframe src="/assets/frontend/plugins/pdfjs/web/viewer.html?file={{ route('order.show', $order->id) }}" frameborder="0"></iframe>
                                    <hr>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Billing Options --}}
                    <div class="row">
                        @include('invoices.billing.billing_options')
                    </div>

                    {{-- Billing Information --}}
                    <div class="row">
                        @include('invoices.billing.billing')
                    </div>

                    {{-- Terms & Conditions --}}
                    <div class="row">
                        @include('invoices.billing.terms')
                    </div>

                </div>

            </div>
        </app-order-pay>
    </section>
@stop

@section('scripts')
    <script src="/assets/frontend/plugins/form.masked/jquery.maskedinput.js"></script>
@stop