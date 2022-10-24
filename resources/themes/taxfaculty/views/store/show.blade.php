@extends('store')

@section('title')
    {{ $listing->title }}
@stop

@section('intro')
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('store.show',$listing->title) !!}
@stop

@section('styles')
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.4/css/bootstrap-select.min.css">
    <style>
        .dropdown-menu .inner {
            position: static !important;
            width: inherit !important;
            height: inherit !important;
            margin: 0 !important;
        }
    </style>
@endsection

@section('content')
    <section>
        <div class="container">
            <div class="row">
                <app-store-listing-product-selection :listing="{{ $listing }}"
                                                     :discounts="{{ $discounts }}"
                                                     :cart-items="{{ $cartItems }}"
                                                     :selected-product-id="{{ $selectedProductId }}"
                                                     inline-template>
                    <div class="col-lg-9 col-md-9 col-sm-9 col-lg-push-3 col-md-push-3 col-sm-push-3">
                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                                <div class="thumbnail relative margin-bottom-3">
                                    <figure data-mode="mouseover" style="position: relative; overflow: hidden;">

                                        @if($listing->image_url)
                                            <img style="width: 102%" src="{{ $listing->image_url }}" alt="{{ $listing->title }}"/>
                                        @else
                                            <img style="width: 100%" src="/assets/themes/taxfaculty/img/shop_item_single.png" alt="{{ $listing->title }}"/>
                                            <span class="image-title-shop">
                                            <p>
                                                {{ $listing->title }}
                                                <small v-if="listing.discount > 0">[-<span
                                                            v-if="listing.discount_type == 'amount'">R</span>@{{ listing.discount }}
                                                    <span v-if="listing.discount_type == 'percentage'">%</span>]
                                                </small>
                                            </p>
                                        </span>
                                        @endif

                                    </figure>
                                </div>
                            </div>

                            @if(($listing->discount) > 0)
                                <div class="col-sm-12">
                                    <div class="alert alert-info text-center"
                                        style="background-color: #173175;color: white; border-style: dashed; margin-bottom: 10px; margin-top: 10px; padding: 5px;">
                                        {!!floatval($listing->discount)!!}% discount is applied on the following products
                                    </div>
                                </div>
                            @endif

                            {{--@if($globalDiscounts->sum('value') > 0 || count($listing->discounts) > 0)--}}
                                {{--<div class="col-sm-12">--}}
                                    {{--@foreach($globalDiscounts as $globalDiscount)--}}
                                        {{--<div class="alert alert-info text-center"--}}
                                             {{--style="background-color: #173175;color: white; border-style: dashed; margin-bottom: 10px; margin-top: 10px; padding: 5px;">--}}
                                            {{--{!! $globalDiscount->description !!}--}}
                                        {{--</div>--}}
                                    {{--@endforeach--}}
                                    {{--@foreach($listing->discounts as $discount)--}}
                                        {{--<div class="alert alert-info text-center"--}}
                                             {{--style="background-color: #173175;color: white; border-style: dashed; margin-bottom: 10px; margin-top: 10px; padding: 5px;">--}}
                                            {{--{!! $discount->description !!}--}}
                                        {{--</div>--}}
                                    {{--@endforeach--}}
                                {{--</div>--}}
                            {{--@endif--}}

                            <div id="product-listing-selection" class="col-lg-12 col-sm-12">
                                <hr>
                                <form id="add-to-cart-form" role="form" class="clearfix form-horizontal nomargin"
                                      method="POST"
                                      action="{{ route('store.add-to-cart',$listing->id) }}">
                                    {{ csrf_field() }}
                                    <div class="row">
                                        <div class="col-md-12">
                                            <select data-container="body" data-width="100%" data-size="10"
                                                    v-model="selectedProductListing"
                                                    class="selectpicker"
                                                    name="product_listing_selected_option">
                                                <option v-if="listing.product_listings.length > 1"
                                                        value="0"
                                                        disabled="disabled" selected="selected">Select a product
                                                </option>
                                                <option v-bind:selected="listing.product_listings.length == 1"
                                                        v-for="productListing in listing.product_listings | orderBy 'product.topic'"
                                                        v-bind:value="productListing"
                                                        data-content="<div style='font-size: 16px; padding-top: 5px; padding-bottom: 5px;'>@{{ productListing.optionTitle }}</div>">
                                                    @{{ productListing.optionTitle }}
                                                </option>
                                                {{--<option v-if="listing.product_listings.length > 1"--}}
                                                {{--value="all">All products--}}
                                                {{--- @{{ totals.price | currency 'R' }}</option>--}}
                                            </select>

                                            <input type="hidden" name="product_listing_id"
                                                   value="@{{ selectedProductListing.id }}">

                                        </div>
                                    </div>
                                    @if(count($cartItems) > 0)
                                        <p v-if="selectedProductListing == 0">
                                            Select a product to see more information or checkout to complete your order.
                                        </p>
                                    @else
                                        <p v-if="selectedProductListing == 0">
                                            Select a product to see more information.
                                        </p>
                                    @endif
                                </form>
                                <div>
                                    <div v-if="selectedProductListing == 'all'">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="border-right">
                                                    <div class="col-md-6 bg-light">
                                                        <h4> @{{ totals.cpd_hours }} Hour(s) CPD </h4>
                                                    </div>
                                                    <div class="col-md-6 bg-light">
                                                        <h4>Price @{{ totals.discounted_price | currency 'R '}}</h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12" style="margin-left: 15px;">

                                                The following @{{ listing.product_listings.length }} products will
                                                be added to your cart:<br><br>
                                                <ul style="margin-bottom: 0;">
                                                    <li v-for="productListing in listing.product_listings">
                                                        <h5 style="margin-bottom: 0;">
                                                            <span>@{{ productListing.detailedTitle }}</span></h5>
                                                    </li>
                                                </ul>

                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-12"
                                                 v-for="tag in totals.tags">
                                                <div class="col-md-9">
                                                    <p>
                                                        <i class="@{{ tag.icon_classes }}"></i>
                                                        <span class="center">
                                                            <strong>@{{ tag.title }}</strong> <span>&ndash;</span> @{{ tag.description }}
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-sm-12" v-if="listing.hasPhysicalProduct">
                                                <div class="col-md-12">
                                                    <p>
                                                        <i class="fa fa-truck"></i>
                                                        <strong>Delivered</strong> <span>&ndash;</span>
                                                        This is a physical product. You will be able to choose your
                                                        delivery method on checkout.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div v-if="selectedProductListing != 0 && selectedProductListing != 'all'">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="border-right">
                                                    <div class="col-md-6 bg-light">
                                                        <h4> @{{ selectedProductListing.product.cpd_hours }} Hour(s)
                                                            CPD </h4>
                                                    </div>
                                                    <div class="col-md-6 bg-light">
                                                        <h4>
                                                            @{{ selectedProductListing.discountedPrice | currency 'R ' }}
                                                            <span v-if="selectedProductListing.discountedPrice < selectedProductListing.product.price"
                                                                  style="font-size: 0.7em; color: #ccc; text-decoration: line-through;">@{{ selectedProductListing.product.price | currency 'R ' }}</span>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-12"
                                                 v-for="tag in selectedProductListing.product.tags">
                                                <div class="col-md-12">
                                                    <p>
                                                        <i class="@{{ tag.icon_classes }}"></i>
                                                        <span class="center"><strong>@{{ tag.title }}</strong> <span>&ndash;</span> @{{ tag.description }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-sm-12" v-if="selectedProductListing.product.is_physical">
                                                <div class="col-md-12">
                                                    <p>
                                                        <i class="fa fa-truck"></i>
                                                        <strong>Delivered</strong> <span>&ndash;</span>
                                                        This is a physical product. You will be able to choose your
                                                        delivery method on checkout.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" style="margin-bottom: 5px;">
                                    @if(count($cartItems) > 0)
                                        <div class="col-sm-12 text-right">
                                            <a href="{{ route('store.checkout') }}"
                                               class="btn btn-default noradius pull-right no-margin-top">CHECKOUT
                                                ({{ $totalCartQty }} items in cart)</a>
                                            <div style="float: right;" v-if="selectedProductListing != 0">
                                                <button v-on:click="submitForm()"
                                                        class="btn btn-primary product-add-cart noradius">ADD TO CART
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-sm-6 col-sm-offset-6 text-right"
                                             v-if="selectedProductListing != 0">
                                            <button v-on:click="submitForm()"
                                                    class="btn btn-primary product-add-cart noradius">ADD TO CART
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr>

                        @if($listing->description != "" || count($listing->presenters) > 0)
                            <ul id="myTab" class="nav nav-tabs nav-top-border margin-top-20" role="tablist">
                                @if($listing->description != "")
                                    <li role="presentation" class="active">
                                        <a href="#description" role="tab" data-toggle="tab">Description</a>
                                    </li>
                                @endif
                                @if(count($listing->presenters) > 0)
                                    <li role="presentation">
                                        <a href="#reviews" role="tab" data-toggle="tab">Presenters</a>
                                    </li>
                                @endif
                            </ul>
                        @endif

                        <div class="tab-content padding-top-20">
                            @if($listing->description && $listing->description != "")
                                <div role="tabpanel" class="tab-pane fade in active" id="description">
                                    {!! $listing->description !!}
                                </div>
                            @endif

                            @if($listing->presenters && count($listing->presenters) > 0)
                                <div role="tabpanel" class="tab-pane fade" id="reviews">
                                    <div class="heading-title heading-dotted"
                                         style="margin-bottom: 10px; margin-top: 20px">
                                        <h4>PRESENTER/S:</h4>
                                    </div>
                                    @foreach($listing->presenters as $presenter)
                                        <strong>{{ $presenter->name }}</strong>
                                        <br>
                                        {!! $presenter->bio !!}
                                        <br>
                                        <hr>
                                        <br>
                                    @endforeach
                                </div>
                                <hr class="margin-top-20 margin-bottom-80"/>
                            @endif
                        </div>
                        @if($listing->relatedListings && count($listing->relatedListings) > 0)
                            <div class="col-md-12">
                                <h2 class="owl-featured"><strong>Related</strong> products:</h2>

                                <div class="owl-carousel featured nomargin owl-padding-10"
                                     data-plugin-options='{"singleItem": false, "items": "4", "stopOnHover":false, "autoPlay":4500, "autoHeight": false, "navigation": true, "pagination": false}'>

                                    @foreach($listing->relatedListings as $relatedListing)
                                        <div class="shop-item no-margin">
                                            <div class="thumbnail">
                                                <a class="shop-item-image" href="{{ route('store.show',$relatedListing->id) }}">
                                                    <img class="img-responsive" src="/assets/frontend/images/shop/products/background.png" alt="{{ $relatedListing->title }}"/>

                                            <span class="image-title-footer">
                                                <p>{{ $relatedListing->title }}</p>
                                            </span>
                                                </a>
                                                <div class="shop-item-info">
                                                    <span class="label label-success">{{ $relatedListing->category->title }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        @include('store.includes.sidebar')
                    </div>
                </app-store-listing-product-selection>
            </div>
        </div>
    </section>

@stop
@section('scripts')
    <script type="text/javascript"
            src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.4/js/bootstrap-select.min.js"></script>
@endsection