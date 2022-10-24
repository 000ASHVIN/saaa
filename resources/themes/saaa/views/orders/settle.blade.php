@extends('app')

@section('title', 'Settle Order #'.$order->reference)

@section('content')
 
<?php $profession=New \App\Profession\Profession(); 
$staff = collect();
$company = collect();
if(auth()->check()){ 
    if(auth()->user()->company_admin()){
       $company[] =  auth()->user()->company;
       $staff =  auth()->user()->company->staff;
    }
}

$plan = New App\Subscriptions\Models\Plan();
if($order->items->count()>0 && $order->type=='subscription'){

$plan = App\Subscriptions\Models\Plan::where('id',$order->items[0]->item_id)->first();
if($plan){
$plan->pricingGroup = $plan->pricingGroup;
}
}
?>
 

    <section style="background-image: url('/assets/frontend/images/demo/wall2.jpg');">
        <app-order-pay  :staff="{{ $staff }}" :companys="{{ $company }}" :selectedplan="{{ $plan }}" :plans="{{ $business }}" :profession="{{ $profession }}" :order="{{ $order->toJson() }}" :user="{{ auth()->user()->load('cards') }}" inline-template>

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

                    {{-- Selected Plan --}}
                    <div class="row">    
                        @include('invoices.billing.selected')
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
                        @include('invoices.billing.orderterms')
                    </div>
 
                </div>

            </div>
        </app-order-pay>
    </section>
@stop

@section('scripts')
    <script src="/assets/frontend/plugins/form.masked/jquery.maskedinput.js"></script>
@stop