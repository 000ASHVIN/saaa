@extends('store')

@section('meta_tags')
    <title>{{ config('app.name') }} | CPD Provider</title>
    <meta name="description" content="Purchase any of our online store products and receive discounts up to 50% off..">
    <meta name="Author" content="{{ config('app.name') }}"/>
@endsection

@section('title')
    Welcome to our Online Shop
@stop

@section('intro')
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('store') !!}
@stop

@section('content')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-sm-9 col-lg-push-3 col-md-push-3 col-sm-push-3">
                    {{--<div class="owl-carousel buttons-autohide controlls-over margin-bottom-30 radius-4"--}}
                         {{--data-plugin-options='{"singleItem": true, "autoPlay": 6000, "navigation": true, "pagination": true, "transitionStyle":"fade"}'>--}}

                        {{--<div>--}}
                            {{--<img class="img-responsive radius-4" src="/assets/frontend/images/shop/discount.png"--}}
                                 {{--width="851" height="335" alt="">--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    @foreach($listings->chunk(4) as $chunk)
                        <div class="row">
                            @foreach($chunk as $listing)
                                <div class="col-md-3">
                                    <div class="shop-item">
                                        <div class="thumbnail" style="padding: 0px">
                                            <a class="shop-item-image" href="{{ route('store.show',$listing->id) }}">
                                                {{--@if($listing->image_url)--}}
                                                    {{--<img class="img-responsive" src="{{ $listing->image_url }}" alt="{{ $listing->title }}"/>--}}
                                                {{--@else--}}
                                                    {{----}}
                                                {{--@endif--}}
                                                <img class="img-responsive" src="/assets/themes/taxfaculty/img/shop_item.png" alt="shop first image"/>
                                                <span class="image-title"><p>{{ $listing->title }}</p></span>
                                            </a>

                                            <div class="shop-item-info">
                                                <span class="label label-success">{{ str_singular(str_limit($listing->category->title, 20)) }}</span>
                                            </div>
                                        </div>

                                        <div class="product-info clearfix">
                                            <div class="col-md-12 text-center">
                                                <div class="row" style="border: 1px dotted rgba(227, 227, 227, 0.51);border-bottom: 0px;padding-bottom: 5px;padding-top: 5px;">
                                                    @if(count($listing->products) >= 1 )
                                                        @if($listing->products && sizeof($listing->products) > 1)
                                                            <span style="font-weight: bold;">From: </span>R{{ $listing->from_price }}
                                                        @else
                                                            <span style="font-weight: bold;">From: </span>R{{ $listing->products[0]->price }}
                                                        @endif

                                                    @else
                                                        <span style="font-weight: bold;">Out of Stock</span>
                                                    @endif
                                                </div>
                                                <div class="row">
                                                    <a href="{{ route('store.show',$listing->id) }}" class="btn btn-default btn-block">View Now</a>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                    <hr/>
                    <div class="text-center">
                        {!! $listings->render() !!}
                    </div>
                </div>

                @include('store.includes.sidebar')
            </div>
        </div>
    </section>
@stop