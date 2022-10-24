@extends('admin.layouts.master')

@section('title', 'Order #' . $order->reference)
@section('description', 'Payment Allocation')

@section('css')
    <link href="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
@stop

@section('content')

<?php  
$profession=New \App\Profession\Profession(); 
$staff = collect();
$company = collect();
    if($order->user->company_admin()){
       $company[] =  $order->user->company;
       $staff =  $order->user->company->staff;
    }

$plan = New App\Subscriptions\Models\Plan();
if($order->items->count()>0 && $order->type=='subscription'){

$plan = App\Subscriptions\Models\Plan::where('id',$order->items[0]->item_id)->first();
if($plan){
    $plan->pricingGroup = $plan->pricingGroup;
}
} 
 
?>
   <app-admin-order-pay  :staff="{{ $staff }}" :companys="{{ $company }}" :selectedplan="{{ $plan }}" :order="{{ $order->toJson() }}" :user="{{ auth()->user()->load('cards') }}" inline-template>

    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            <div class="col-md-12">
                <div class="row margin-top-30">
                    <div class="col-lg-8 col-lg-offset-2 col-md-12">

                        <div class="panel">
                            <div class="panel-body">

                                <fieldset>
                                    <legend>Order Items</legend>
                                    <table class="table margin-top-10 table-hover">
                                        <thead>
                                        <th>Line Item</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        </thead>
                                        <tbody>
                                        @foreach($order->fresh()->items as $item)
                                            <tr>
                                                <td>
                                                    {{ $item->name }} <br>
                                                    <small>{{ $item->description }}
                                                        @if($order->status == 'paid')
                                                            <span class="label label-success">Paid</span>
                                                        @else
                                                            <span class="label label-danger">{{ ucwords($order->status) }}</span>
                                                        @endif
                                                    </small>
                                                </td>
                                                <td>
                                                    {{ $item->quantity }}
                                                </td>
                                                <td>
                                                    {{ money_format('%.2n', $item->price) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </fieldset>
                                @if(count($order->payments))
                                    <fieldset>
                                        <legend>
                                            Order Payment Allocations
                                        </legend>
                                        <table class="table margin-top-10 table-hover">
                                            <thead>
                                            <th>Reference</th>
                                            <th>Description</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            </thead>
                                            <tbody>
                                            @foreach($order->payments as $payment)
                                                <tr>
                                                    <td>
                                                        <a href="#"
                                                           data-toggle="popover"
                                                           data-placement="top"
                                                           data-title="Payment Notes"
                                                           data-content=
                                                           "{{ ($payment->notes) ?: 'None' }}"
                                                           data-trigger="hover"
                                                           data-original-title
                                                        >
                                                            #{{ $payment->invoice_order->reference }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{ (ucfirst($payment->description)) ? : "None" }}
                                                    </td>
                                                    <td>
                                                        {{ date_format($payment->date_of_payment, 'd F Y') }}
                                                    </td>
                                                    <td>
                                                        R{{ $payment->amount }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </fieldset>
                                @endif

                                @if($order->status != 'cancelled' && $order->status != 'paid')
                                    {!! Form::open(['method' => 'post', 'route' => ['post_allocate_order_payment', $order->id]]) !!}
                                    <fieldset>
                                        <legend>
                                            Payment Details - Total Due: {{ money_format('%.2n', $order->balance) }}
                                        </legend>

                           
                                        <input type="hidden" value="{{ $order->id }}" name="invoice_id">
                                        {!! csrf_field() !!}
                                        <div class="form-group">
                                            <label for="date_of_payment">
                                                Date Received <span class="symbol required"></span>
                                            </label>
                                            <input type="text" class="form-control" name="date_of_payment" id="date_of_payment" placeholder="Date payment received">
                                        </div>

                                        <div class="form-group">
                                            <label for="amount">
                                                Payment Amount <span class="symbol required"></span>
                                            </label>
                                            <input type="integer" class="form-control" name="amount" v-model="amount" id="amount" value="{{ $order->balance }}" placeholder="Amount of payment received">
                                        </div>

                                        <div class="form-group">
                                            <label for="description">
                                                Payment Reference <span class="symbol required"></span>
                                            </label>
                                            <input type="text" class="form-control" name="description" id="description" placeholder="Payment Reference">
                                        </div>

                                        <div class="form-group">
                                            <label for="method">
                                                Payment Method <span class="symbol required"></span>
                                            </label>

                                            <select name="method" id="method" class="form-control">
                                                <option value="">Please select...</option>
                                                <option value="cash">Cash</option>
                                                <option value="eft">EFT</option>
                                                <option value="instant_eft">Instant EFT</option>
                                                <option value="cc">Credit Card</option>
                                                <option value="debit">Debit Order -  Peach Payments</option>
                                                <option value="debit_stratcol">Debit Order - Stratcol </option>
                                                <option value="snap_scan">Snap Scan</option>
                                                <option value="pebble">Payment Pebble</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="notes">
                                                Notes
                                            </label>
                                            <textarea name="notes" id="notes" rows="5" class="form-control"></textarea>
                                        </div>
                                    </fieldset>

                                    <div class="form-group center">
                                        <button type="submit" class="btn btn-o btn-primary" onclick="spin(this)";>
                                            Allocate Payment
                                        </button>

                                        <a href="/admin/members/{{ $order->user->id }}" class="btn btn-wide  btn-o btn-danger">Cancel</a>
                                    </div>
                                    {!! Form::close() !!}

                                @elseif($order->status == 'cancelled')
                                    <div class="text-center">
                                        <p><i>Order Cancelled, payment allocations not possible.</i></p>
                                        <a href="/admin/members/{{ $order->user->id }}" class="btn btn-wide  btn-o btn-success"><i class="fa fa-arrow-left"></i> Cancel</a>
                                    </div>

                                @elseif($order->status == 'paid')
                                    <div class="text-center">
                                        <p><i>Order has been paid in full, payment allocations not possible.</i></p>
                                        <a href="/admin/members/{{ $order->user->id }}" class="btn btn-wide  btn-o btn-success"><i class="fa fa-arrow-left"></i> Back</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </app-admin-order-pay>
@stop

@section('scripts')
    <script src="/assets/themes/saaa/js/app.js"></script>
    <script src="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/assets/admin/assets/js/allocate.js"></script>
    <script>
        jQuery(document).ready(function() {
            Main.init();
            Allocate.init();
        });
    </script>
    <script>
        $('.delete-me').on('click', function(){
            $(this).closest('form').submit();
        });
        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
    </script>
@stop