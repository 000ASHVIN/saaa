@extends('app')

@section('title', 'Store checkout')

@section('breadcrumbs')
    {!! Breadcrumbs::render('store.checkout') !!}
@stop

@section('content')
    @include('dashboard.edit.includes.new_address')
    <section style="background-image: url('/assets/frontend/images/demo/wall2.jpg');">
        <app-store-checkout :cart-items="{{ $cartItems }}"
                            :cart-has-physical-product="{{ $cartHasPhysicalProduct }}"
                            :cart-total-quantity="{{ $cartTotalQuantity }}"
                            :cart-total-discounted-price="{{ $cartTotalDiscountedPrice }}"
                            :shipping-methods="{{ $shippingMethods }}"
                            :addresses="{{ $addresses }}"
                            :countries="{{ json_encode(App\Country::countriesByCode) }}"
                            :provinces="{{ json_encode(App\Province::provincesByCode) }}"
                            :user="{{ auth()->user()->load('cards') }}"
                            :donations="{{ env('DONATIONS_AMOUNT') }}"
                            inline-template>
            <div id="app-register-screen" class="container app-screen">

                <div class="col-md-8 col-md-offset-2">

                    <div class="row">
                        @include('includes.donation', ['vif' => "donations", 'vmodel' => "forms.checkout.donations"])
                    </div>

                    {{-- Summary --}}
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="pull-left">
                                    Cart Summary
                                </div>
                                <div class="pull-right">
                                    <a href="{{ route('store.cart') }}" class="btn btn-default btn-xs">Edit</a>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="panel-body">
                                <div>
                                    <div class="col-md-12">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Qty</th>
                                                <th class="text-right">Price</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="cartItem in cartItems | orderBy 'discountPercentage'">
                                                <td>
                                                    @{{ cartItem.detailedTitle }}
                                                </td>
                                                <td>@{{ cartItem.qty }}</td>
                                                <td class="text-right">
                                                    <span v-if="cartItem.hasDiscount"
                                                          style="font-size: 0.7em; color: #ccc; text-decoration: line-through;">@{{ cartItem.price | currency 'R ' }}</span>
                                                    @{{ cartItem.discountedPrice | currency 'R ' }}
                                                </td>
                                            </tr>
                                            <tr v-if="forms.checkout.donations && donations">
                                                <td>
                                                    Donation
                                                </td>
                                                <td>1</td>
                                                <td class="text-right">
                                                    R {{ env('DONATIONS_AMOUNT') }}
                                                </td>
                                            </tr>
                                            <tr v-if="cartHasPhysicalProduct">
                                                <td style="padding: 0;">
                                                    <label style="margin-bottom: 0;">
                                                        <select v-model="forms.checkout.shippingMethod"
                                                                style="font-size: 14px; border-radius: 0; border: none; background-color: transparent; padding: 0 0 0 4px; height: 37px;">
                                                            <option v-for="shippingMethod in shippingMethods"
                                                                    value="@{{ shippingMethod }}"
                                                                    style="font-size: 14px;">
                                                                @{{ shippingMethod.title }}
                                                                - @{{ shippingMethod.description }}
                                                            </option>
                                                        </select>
                                                    </label>
                                                </td>
                                                <td>1</td>
                                                <td class="text-right">
                                                    @{{ forms.checkout.shippingMethod.price | currency 'R ' }}
                                                </td>
                                            </tr>
                                            </tbody>
                                            <tfoot>
                                            <tr>
                                                <td><strong>Total</strong></td>
                                                <td><strong>@{{ totalQty }}</strong></td>
                                                <td class="text-right">
                                                    <strong>@{{ total | currency 'R ' }}</strong>
                                                    <br>
                                                    <small></small>
                                                </td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{--Address Selection--}}
                    <div class="row" v-if="cartHasPhysicalProduct && forms.checkout.shippingMethod.id != 2">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <div class="pull-left">
                                    Delivery Address
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="panel-body">
                                <div class="col-md-12">
                                    <div class="row" v-if="addresses.length > 0">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <h5>Select the address you would like physical products delivered
                                                    to:</h5>
                                                <label>
                                                    <select v-model="forms.checkout.deliveryAddress"
                                                            class="form-control">
                                                        <option v-for="address in addresses" value="@{{ address }}">
                                                            @{{ address.selectOptionTitle }}
                                                        </option>
                                                    </select>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" v-else>
                                        <h5>You don't currently have any addresses. Please add one to proceed.</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <button class="btn btn-primary" data-target="#new_address"
                                                    data-toggle="modal">Add new address
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Billing Options --}}
                    <div v-if="total > 0">
                        <div class="row" v-if="forms.checkout.deliveryAddress || !cartHasPhysicalProduct">
                            @include('store.checkout.billing.billing_options')
                        </div>

                        {{-- Billing Information --}}
                        <div class="row">
                            @include('store.checkout.billing.billing')
                        </div>
                    </div>

                    <div v-else>
                        <div class="row">
                            @include('store.checkout.billing.free')
                        </div>
                    </div>



                </div>

            </div>
        </app-store-checkout>
    </section>
@stop

@section('scripts')
    <script src="/assets/frontend/plugins/form.masked/jquery.maskedinput.js"></script>
@stop