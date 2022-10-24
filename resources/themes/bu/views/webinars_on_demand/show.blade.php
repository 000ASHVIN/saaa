@extends('app')

@section('content')

@section('title')
    Webinars On-Demand
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('webinars_on_demand_index') !!}
@stop

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="border-box"
                     style="background-color: white; margin-bottom: 15px; padding: 0px; min-height: 240px; border-color: #ffffff; -webkit-box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); -moz-box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75);">
                    <div class="ribbon" style="right: 13px;">
                        <div class="ribbon-inner" style="font-size: 10px">{{ ucfirst($video->category) }}</div>
                    </div>
                    <img style="width: 100%!important; margin-bottom: 5px" src="/assets/frontend/images/webinar_on_demand_image.jpg" alt="...">
                    <div class="padding" style="padding: 15px; text-align: center">
                        <div class="w_title" style="min-height: 20px; font-weight: bold">
                            <p class="text-left">{{ $video->title }}</p>
                            <p class="text-left">CPD Hours: {{ ($video->hours ? $video->hours : "0") }}</p>
                            <p class="text-left">Tag: <span class="label label-default-blue">{{ ucfirst($video->tag) }} Recording</span></p>
                        </div>
                        <hr>
                        <div class="form-group form-inline">
                            @if(auth()->user() && auth()->user()->webinars->contains($video->id))
                                <a href="#" class="btn btn-warning btn-block disabled"><i class="fa fa-shopping-cart"></i> Already Owned</a>
                            @else
                                <a href="{{ route('webinars_on_demand.checkout', $video->slug) }}" class="btn btn-primary btn-block"><i class="fa fa-shopping-cart"></i> Buy Now</a>
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
                        @include('webinars_on_demand.includes.search')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                {!! $video->description !!}

                <div class="padding-bottom-30"></div>

                @if(count($related) >= 3)
                    <div class="heading-title heading-dotted" style="margin-bottom: 20px;">
                        <h4><span>Related Webinars</span></h4>
                    </div>

                    <div class="row">
                        @foreach($related->random(3) as $video)
                            <div class="col-md-4">
                                <div class="border-box"
                                     style="background-color: white; margin-bottom: 15px; padding: 0px; min-height: 240px; border-color: #ffffff; -webkit-box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); -moz-box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75);">
                                    <div class="ribbon" style="right: 13px;">
                                        <div class="ribbon-inner" style="font-size: 10px">{{ ucfirst($video->category) }}</div>
                                    </div>
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