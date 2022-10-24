@extends('store')

@section('title')
    Shopping Cart
@stop

@section('intro')
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('store.cart') !!}
@stop

@section('content')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-sm-8">
                    <form class="cartContent clearfix" method="post" action="#">
                        <div id="cartContent">
                            <div class="item head clearfix">
                                <span class="cart_img"></span>
                                <span class="product_name size-13 bold">PRODUCT NAME</span>
                                <span class="remove_item size-13 bold"></span>
                                <span class="total_price size-13 bold">TOTAL</span>
                                <span class="qty size-13 bold">QUANTITY</span>
                            </div>

                            @foreach($cartItems as $k => $cartItem)
                                <div class="item">
                                    <a style="padding-top: 30px;" href="{{ $cartItem->listingUrl }}"
                                       class="product_name">
                                        {{ $cartItem->detailedTitle }}
                                    </a>
                                    <a style="margin-top: 30px;"
                                       href="{{ route('store.cart.remove',[$k,$cartItem->qty,$cartItem->model]) }}"
                                       class="remove_item"><i class="fa fa-times"></i></a>
                                    <div style="padding-top: 28px;" class="total_price">
                                        <span>{{ currency($cartItem->discountedPrice * $cartItem->qty) }}</span>
                                    </div>
                                    <div class="qty">
                                        <input type="number" value="{{ $cartItem->qty }}"
                                               name="qty" maxlength="3" max="999"
                                               min="1"
                                               disabled="disabled"
                                        />
                                        &nbsp;&times;&nbsp;
                                        <div style="display: inline-block;">
                                            @if($cartItem->hasDiscount)
                                                <span style="font-size: 0.7em; color: #ccc; text-decoration: line-through;">{{ currency($cartItem->price) }}</span>
                                                <br>
                                            @else
                                                <br>
                                            @endif
                                            {{ currency($cartItem->discountedPrice) }}
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            @endforeach
                            <a href="{{ route('store.cart.clear') }}"
                               class="btn btn-danger margin-top-20 margin-right-10 pull-right"><i
                                        class="glyphicon glyphicon-remove"></i> CLEAR CART
                            </a>
                            <div class="clearfix"></div>
                        </div>
                    </form>
                </div>

                <div class="col-lg-3 col-sm-4">
                    <div class="toggle-transparent toggle-bordered-full clearfix">
                        <div style="margin: 0;" class="toggle active">
                            <div class="toggle-content">
                                <span class="clearfix">
                                    <span class="pull-right">{{ currency($cartTotalPrice) }}</span>
                                    <strong class="pull-left">Subtotal:</strong>
                                </span>
                                <span class="clearfix">
                                    <span class="pull-right">{{ currency($cartTotalDiscount) }}</span>
                                    <span class="pull-left">Discount:</span>
                                </span>

                                <hr/>

                                <span class="clearfix">
                                    <span class="pull-right size-20">{{ currency($cartTotalDiscountedPrice) }}</span>
                                    <strong class="pull-left">TOTAL:</strong>
                                </span>

                                <hr/>

                                <a href="{{ route('store.checkout') }}"
                                   class="btn btn-primary btn-lg btn-block size-15"><i
                                            class="fa fa-mail-forward"></i> Proceed to Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop