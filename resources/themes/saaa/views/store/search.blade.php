@extends('store')

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
                    @if(count($resultsListings) > 0)
                        <div class="alert alert-default">
                            {{ count($resultsListings) }} result(s) found @if($search && trim($search) != "")for&nbsp;
                            "{{ $search }}"@endif @if($category)in&nbsp;"{{ $category->title }}"@endif
                            <a href="{{ route('store.index') }}" style="margin: 0;"
                               class=" btn btn-xs btn-primary pull-right">Clear search</a>
                        </div>
                        <ul class="shop-item-list row list-inline nomargin">
                            @foreach($resultsListings as $listing)
                                <li class="col-lg-3 col-sm-12">
                                    <div class="shop-item">
                                        <div class="thumbnail">
                                            <a class="shop-item-image"
                                               @if($listing->foundProduct)
                                               href="{{ route('store.show',[$listing->id,'product' => $listing->foundProductId]) }}">
                                                @else
                                                    href="{{ route('store.show',$listing->id) }}">
                                                @endif
                                                    <img class="img-responsive" src="/assets/frontend/images/shop/products/background.png" alt="shop first image"/>
                                                <span class="image-title">
                                                <p>{{ $listing->title }}</p>
                                            </span>
                                            </a>

                                            <div class="shop-item-info">
                                                @if($listing->category)
                                                    <span class="label label-success">{{ str_singular($listing->category->title) }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="product-info clearfix">
                                            <div class="col-md-12 text-center">
                                                <div class="row"
                                                     style="border: 1px dotted rgba(227, 227, 227, 0.51);border-bottom: 0px;padding-bottom: 5px;padding-top: 5px;">

                                                    {{--@if($listing->products && sizeof($listing->products) > 1)--}}
                                                        {{--<span style="font-weight: bold;">From: </span>R--}}
                                                        {{--&nbsp;{{ $listing->from_price }}--}}
                                                    {{--@else--}}
                                                        {{--<span style="font-weight: bold;">From:</span>R--}}
                                                        {{--&nbsp;{{ $listing->products[0]->price }}--}}
                                                    {{--@endif--}}

                                                </div>
                                                <div class="row">
                                                    <a class="btn btn-default btn-block"
                                                       @if($listing->foundProduct)
                                                       href="{{ route('store.show',[$listing->id,'product' => $listing->foundProductId]) }}">
                                                        @else
                                                            href="{{ route('store.show',$listing->id) }}">
                                                        @endif
                                                        View Now
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="alert alert-default">
                            No result(s) found @if($search && trim($search) != "")for&nbsp;"{{ $search }}
                            "@endif @if($category)in&nbsp;"{{ $category->title }}"@endif
                            <a href="{{ route('store.index') }}" style="margin: 0;"
                               class=" btn btn-xs btn-primary pull-right">Clear search</a>
                        </div>
                    @endif

                    <hr/>
                    {{--@include('store.includes.pagination',['paginator' => $resultsListings])--}}
                </div>

                @include('store.includes.sidebar')
            </div>
        </div>
    </section>
@stop