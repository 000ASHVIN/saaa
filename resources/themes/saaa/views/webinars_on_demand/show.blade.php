@extends('app')

@section('meta_tags')
    <title>{{ $video->checkMetaTitle() }}</title>
    <meta name="description" content="{{ $video->meta_description }}">
    <meta name="keyword" content="{{ $video->keyword }}">
    {{-- <meta name="Author" content="{{ $video->title }}"/> --}}
@endsection   

@section('content')

@section('title')
    Webinars On-Demand
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('webinars_on_demand_index') !!}
@stop

@section('styles')
    <style>
        /* Read More Button CSS */
        .read-more-state {
            display: none;
        }
        .read-more-target {
            opacity: 0;
            max-height: 0;
            font-size: 0;
            transition: .25s ease;
        }
        .read-more-state:checked ~ .read-more-wrap .read-more-target {
            opacity: 1;
            font-size: inherit;
            max-height: 999em;
        }
        .read-more-state ~ .read-more-trigger:before {
            content: 'Read More';
        }
        .read-more-state:checked ~ .read-more-trigger:before {
            content: 'Read Less';
        }
        .read-more-trigger {
            cursor: pointer;
            display: inline-block;
            padding: 0 .5em;
            color: #8cc03c;
            font-size: .9em;
            line-height: 2;
            font-weight: bold;
            font-size: 15px;
        }
        /* End Read More Button CSS */

        @media only screen and (max-width:768px){ 
            .price-wrapper {
                margin-bottom: 0px !important;
            }
            section div.row>div{margin-bottom: 5px;}
        }
 </style>
@stop
<?php
$webinarContainer = collect();  
if(auth()->user())
{
 $webinarContainer = auth()->user()->webinarsPending();
}
?>
<section>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="border-box"
                     style="background-color: white; margin-bottom: 15px; padding: 0px; min-height: 240px; border-color: #ffffff; -webkit-box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); -moz-box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75);">
                    <div class="ribbon" style="right: 13px;">
                        @if($video->categories) <div class="ribbon-inner" style="font-size: 10px">{{ ucfirst($video->categories->title) }}</div>@endif
                    </div>
                    <img style="width: 100%!important; margin-bottom: 5px" src="/assets/frontend/images/webinar_on_demand_image.jpg" alt="...">
                    <div class="padding" style="padding: 15px; text-align: center">
                        <div class="w_title" style="min-height: 20px; font-weight: bold">
                            <p class="text-left">{{ $video->title }}</p>
                            <p class="text-left">CPD Hours: {{ ($video->hours ? $video->hours : "0") }}</p>
                            <p class="text-left">Price: R{{ number_format($video->amount, 2, ".", "") }}</p> 
                        </div>
                        
                        @if(count($video->webinar_series))
                        <hr>
                            <p class="text-center" style="min-height: 20px; font-weight: bold">This is part of a series</p>
                            <a href="{{ route('webinars_on_demand.show', $video->webinar_series[0]->slug) }}" class="btn btn-block btn-primary">Read more about series</a>
                        @endif
                        <hr>
                        <div class="form-group form-inline">
                            @if(auth()->user() && $webinarContainer->contains($video->id))

                                @if($video->view_resource)
                                    <a href="{{ route('dashboard.video.links-and-resources', $video->slug) }}" class="btn btn-primary btn-block">Resources</a>
                                @elseif($video->view_link)
                                    <a href="{!! $video->view_link !!}" data-plugin-options="{&quot;type&quot;:&quot;iframe&quot;}" class="btn btn-primary btn-block lightbox wod_video_play"><i class="fa fa-play"></i>Watch</a>
                                @endif

                                <a href="#" class="btn btn-warning btn-block disabled"><i class="fa fa-shopping-cart"></i> Already Owned</a>
                            @else
                                <a href="{{ route('webinars_on_demand.checkout', $video->slug) }}" class="btn btn-primary btn-block"><i class="fa fa-shopping-cart"></i> 
                                @if($video->type == 'series')
                                    Buy Series
                                @else
                                    Buy Now
                                @endif
                                </a>
                                @if($video->videoInCart())
                                    <a href="#" class="btn btn-info btn-block"><i class="fa fa-shopping-cart"></i> Already In cart</a>
                                @else
                                    <a href="{{ route('webinars_on_demand.add-to-cart', $video->slug) }}" class="btn btn-primary btn-block"><i class="fa fa-shopping-cart"></i>
                                    @if($video->type == 'series')
                                        Add Series to Cart
                                    @else
                                        Add To Cart
                                    @endif
                                    </a>
                                @endif
                            @endif
                            
                        </div>
                    </div>
                </div>

                <a href="{{ route('webinars_on_demand.home') }}" class="btn btn-primary btn-block"><i class="fa fa-arrow-left"></i> Back</a>

                <hr>

                <div class="panel panel-default">
                    <div class="panel-heading"><i class="fa fa-search"></i> Title / Topic</div>
                    <div class="panel-body">
                        {!! Form::open(['method' => 'Post', 'route' => 'webinars_on_demand.search']) !!}
                        @include('webinars_on_demand.includes.search_wod')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="col-md-9">
            <h2 style="color: #173175; margin-bottom:20px;">{!! $video->title !!}</h2>
            @if(count($video->presenters))
                <div>
                    <h4>
                        Presenters :
                        <?php 
                            $count = count($video->presenters);
                            ?>
                            @foreach($video->presenters as $presenters)
                            <a href="{{ route('presenters.show', $presenters->id) }}" target="_blank">{{ $presenters->name }}</a>
                                    @if($count > 1)
                                    ,
                                    @endif
                                <?php
                                    $count--?>
                            @endforeach
                        </h4>
                </div>
                @endif
                
                @if(count($video->webinar_series))
                    <h4>This webinar on demand is part of a series of webinars. <a href="{{ route('webinars_on_demand.show', $video->webinar_series[0]->slug) }}">Click here</a> to read more</h4>
                @endif
                <hr>
                {!! $video->description !!}

                <div class="padding-bottom-30"></div>

                @if($video->type == 'series')
                    <div class="heading-title heading-dotted" style="margin-bottom: 20px;">
                        <h4><span>What's Included:</span></h4>
                    </div>
                
                    @foreach($video->webinars as $video)
                        <div class="search-category">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="event-container-box clearfix">
                                        <h4>{!! $video->title !!}</h4>
                                        <!-- Read More Button -->
                                        <div>
                                            <input type="checkbox" class="read-more-state" id="{{ $video->id }}" />
                                            <p class="read-more-wrap"> {{ substr(strip_tags($video->description), 0, 250) }} <span class="read-more-target">
                                            {{ substr(strip_tags($video->description),250) }}</span></p>
                                            
                                            @if (strlen(strip_tags($video->description)) > 250)
                                            <label for="{{ $video->id }}" class="read-more-trigger" style="display:none"></label>
                                            @endif
                                        </div> 
                                        <!-- End Read More Button -->
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-4 price-wrapper">
                                                <h5 style="margin-bottom:0px;">
                                                    <i class="fa fa-plus"></i> {{ ($video->hours ? : 0) }} Hours |
                                                    R{{ number_format($video->amount, 2, ".", "") }} 
                                                </h5>
                                                <?php 
                                                    $presenter =[];
                                                ?>
                                                @if(count($video->presenters))
                                                    @foreach($video->presenters as $presenters)
                                                        <?php
                                                        $presenter[] = $presenters->name; ?>
                                                    @endforeach
                                                    {{ implode(', ', $presenter)}}
                                                @endif
                                            </div>
                                            <div class="col-md-1 price-wrapper"></div>
                                            <div class="col-md-7">
                                                <div class="form-inline pull-right">
                                                    <a href="{{ route('webinars_on_demand.show', $video->slug) }}" class="btn btn-default">Read More</a>
                                                    @if(auth()->user() && $webinarContainer->contains($video->id))

                                                        @if($video->view_resource)
                                                            <a href="{{ route('dashboard.video.links-and-resources', $video->slug) }}" class="btn btn-default my-webinars-btn-container">Resources</a>
                                                        @endif

                                                        @if($video->view_link)
                                                            <a href="{!! $video->view_link !!}" data-plugin-options="{&quot;type&quot;:&quot;iframe&quot;}" class="btn btn-primary my-webinars-btn-container lightbox"> <i class="fa fa-play"></i>Play</a> 
                                                        @endif

                                                        <a href="#" class="btn btn-warning disabled">Already Owned</a>
                                                    @else
                                                        <a href="{{ route('webinars_on_demand.checkout', $video->slug) }}" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Buy Now</a>
                                                        @if($video->videoInCart())
                                                            <a href="#" class="btn btn-info"><i class="fa fa-shopping-cart"></i> Already In cart</a>
                                                        @else
                                                            <a href="{{ route('webinars_on_demand.add-to-cart', $video->slug) }}" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Add To Cart</a>    
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="padding-bottom-30"></div>
                @endif


                @if(count($related) >= 3)
                    <div class="heading-title heading-dotted" style="margin-bottom: 20px;">
                        <h4><span>Related Webinars</span></h4>
                    </div>

                    <div class="row">
                        @foreach($related->random(3) as $video)
                            <div class="col-md-4">
                                <div class="border-box"
                                     style="background-color: white; margin-bottom: 15px; padding: 0px; min-height: 240px; border-color: #ffffff; -webkit-box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); -moz-box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75);">
                                     @if($video->category()->first())
                                    <div class="ribbon" style="right: 13px;">
                                        <div class="ribbon-inner" style="font-size: 10px">{{ ucfirst($video->category()->first()->title) }}</div>
                                    </div>
                                    @endif
                                    <img style="width: 100%!important; margin-bottom: 5px" src="/assets/frontend/images/webinar_on_demand_image.jpg" alt="...">
                                    <div class="padding" style="padding: 15px; text-align: center">
                                        <div class="w_title" style="min-height: 60px; font-weight: bold">
                                            <p class="text-left">{{ $video->title }}</p>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6 webinar_on_demand_cpd"><i class="fa fa-plus"></i> {{ ($video->hours ? : 0) }} Hours</div>
                                            <div class="col-md-6 webinar_on_demand_price">R{{ number_format($video->amount, 2, ".", "") }}</div>
                                        </div>
                                        <hr>
                                        <div class="form-group form-inline">
                                            <a href="{{ route('webinars_on_demand.show', $video->slug) }}" class="btn btn-primary form-control">Read More</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection 