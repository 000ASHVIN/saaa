@extends('app')

@section('title', 'My Products')

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li class="active">My Products</li>
                    </ol>
                </li>
            </ol>
        </li>
    </ol>
@stop

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-8">
                <div class="heading-title heading-dotted text-center">
                    <h4>My <span>Products</span></h4>
                </div>

                @if($hasPendingOrder)
                    <div class="alert alert-bordered-dotted margin-bottom-30">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">x</span><span
                                    class="sr-only">Close</span></button>
                        <h4><strong>Pending orders</strong></h4>

                        <p>Your products will be made available and shipped (where applicable) as soon as we received
                            payment or proof of payment.</p>
                    </div>
                @endif
                @if(count($orders) > 0)
                    @include('dashboard.includes.orders',['orders' => $orders])
                @else
                    <div class="alert alert-info margin-bottom-30">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">X</span><span
                                    class="sr-only">Close</span></button>
                        <strong>Heads up!</strong> You have not purchased any products from our store. To see what we
                        have available,
                        <a href="{!! route('store.index') !!}">click here</a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@stop