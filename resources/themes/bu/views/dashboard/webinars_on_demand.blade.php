@extends('app')

@section('title')
    My Webinars
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('dashboard.my_webinars') !!}
@stop

@section('styles')
    <style type="text/css">
        .bootstrap-dialog-message form, .bootstrap-dialog-message .form-group {
            margin-bottom: 0;
        }
    </style>
@endsection

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')
            <div class="col-lg-9 col-md-9 col-sm-8">
                <div class="alert alert-bordered-dotted margin-bottom-30 text-center">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                    <h4><strong><i class="fa fa-volume-off"></i> Experiencing sound problems? </strong></h4>

                    <p>Please note that some of our recordings have longer intro's than other videos, therefore you will hear no sound in the beginning of some recordings, Please feel free to skip a few minutes into the video.</p>

                    <p>When watching the below recordings we recommend using Google Chrome / Firefox . If older Internet browsers are used when watching the recordings it is highly possible that your streaming will fail and that you will have to restart.</p>
                    <br>
                    <a href="https://goo.gl/Fn2dCf" target="_blank"><img  style="width:5%" src="http://imageshack.com/a/img923/8992/W1tHZQ.jpg" alt="Google Chrome"></a>
                    <span style="padding-right: 20px"></span>
                    <a href="https://goo.gl/7enrPw" target="_blank"><img  style="width:5%" src="http://imageshack.com/a/img922/6181/2OVJ8Q.jpg" alt="Firefox"></a>
                </div>

                @if(! Request::is('dashboard/webinars_on_demand/*'))
                    <div class="row">
                        <div class="col-md-6">
                            <div class="border-box text-center">
                                <h4>Studio Recordings</h4>
                                <p>View studio recordings that was recorded in studio for your past purchased events.</p>
                                <a href="{{ route('dashboard.webinars_on_demand.index', 'studio') }}" class="btn btn-primary"><i class="fa fa-filter"></i> Studio Recordings</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-box text-center">
                                <h4>Webinar Recordings</h4>
                                <p>View webinar recordings that was recorded during live webinars for your past purchased events.</p>
                                <a href="{{ route('dashboard.webinars_on_demand.index', 'webinar') }}" class="btn btn-primary"><i class="fa fa-filter"></i> Webinar Recordings</a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="form-group">
                        <a href="{{ route('dashboard.webinars_on_demand.index') }}" class="btn btn-default btn-block"><i class="fa fa-arrow-left"></i> Back to categories</a>
                    </div>
                @endif

                <br>

                @if(Request::is('dashboard/webinars_on_demand/*'))
                    @if(count($webinars))
                        @foreach($webinars->chunk(3) as $chunk)
                            <div class="row margin-bottom-20">
                                @foreach($chunk as $webinar)
                                    <div class="col-md-4">
                                        <div class="border-box"
                                             style="margin-bottom: 15px; padding: 0px; min-height: 240px; border-color: #ffffff; -webkit-box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); -moz-box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75);">
                                            <a style="width: 110%!important;" class="lightbox"
                                               href="{!! $webinar->view_link !!}"
                                               data-plugin-options="{&quot;type&quot;:&quot;iframe&quot;}">
                                                <img style="width: 100%!important; margin-bottom: 5px"
                                                     src="/assets/frontend/images/webinar_on_demand_image.jpg" alt="...">
                                            </a>
                                            <div class="padding" style="padding: 15px; text-align: center">
                                                {{--<p>--}}
                                                    {{--<small><i class="fa fa-tag"></i> {!! ucfirst($webinar->category) !!}--}}
                                                    {{--</small>--}}
                                                {{--</p>--}}
                                                <p>{!! $webinar->title !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @else
                        <hr>
                        <p>
                            <strong>No webinars listed here? </strong> You do not have any purchased webinars yet, Once you have registered for an event and the
                            invoice was settled in full you will be able to view the webinar recording here once made to <strong>watch</strong> / <strong>download</strong>.
                        </p>

                        <br>
                        <div class="form-group">
                            <a href="/events" class="btn btn-primary"><i class="fa fa-ticket"></i> Register for Events</a>
                        </div>
                    @endif

                    <div class="text-center">
                        {!! $webinars->render() !!}
                    </div>
                @endif

            </div>
        </div>
    </section>
@stop