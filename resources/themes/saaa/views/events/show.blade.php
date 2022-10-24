@extends('app')

@section('meta_tags')
    <title>{{ $event->checkMetaTitle() }}</title> 
    <meta name="description" content="{{ $event->meta_description }}">
  
@endsection   

@section('breadcrumbs')
    {!! Breadcrumbs::render('events.show', $event->name) !!}
@stop

@section('title', $event->name)
@section('intro', str_limit($event->description, '80'))

@section('content')
    <section style="background-image: url('{!! $event->background_url !!}')">
        <div class="overlay dark-5"><!-- dark overlay [1 to 9 opacity] --></div>
        <div class="container g-z-index-1 g-py-100 event-inner-banner" style="background-color: rgba(255, 255, 255, 0.8784313725490196)!important;">
            <h2 class="g-font-weight-300 g-letter-spacing-1 g-mb-15 text-center">{{ $event->name }}</h2>
            <hr>
            <div class="row">
                <div class="col-md-3 col-md-offset-2">
                    <p style="font-size: 16px"><strong>Date: </strong> {{ $event->start_date->toFormattedDateString() }} - {{ $event->end_date->toFormattedDateString() }}</p>
                    <p style="font-size: 16px"><strong>CPD hours: </strong> {{ $event->cpd_hours }}  Hours</p>
                    <p style="font-size: 16px"><strong>Time: </strong> {{ $event->start_time }} - {{ $event->end_time }}</p>
                    <p style="font-size: 16px"><strong>Event Type: </strong> {{ ucwords($event->type) }}</p>
                </div>

                <div class="hidden-lg hidden-md">
                    <hr>
                </div>

                <div class="col-md-6">
                    @if($event->presenters->count())
                        @if(count($event->presenters) > 2)
                            @foreach($event->presenters as $presenter)
                                <h5>Presenter: <a href="{{ route('presenters.show', $presenter->id) }}" target="_blank">{{ $presenter->name }}</a> <small>{{ $presenter->title }}</small></h5>
                            @endforeach
                        @else
                            @foreach($event->presenters as $presenter)
                                <h5>Presenter: <a href="{{ route('presenters.show', $presenter->id) }}" target="_blank">{{ $presenter->name }}</a> <br> <small>{{ $presenter->title }}</small></h5>
                            @endforeach
                            <p>{!! strip_tags(str_limit($event->short_description, 400)) !!}</p>
                        @endif
                    @else
                        <h5>Presenter: TBA</h5>
                        <p>{!! strip_tags(str_limit($event->short_description, 400)) !!}</p>
                    @endif
                </div>
            </div>

            <div class="row">
                <hr>
                <div class="col-md-12 text-center">
                    <div class="form-group">
                        <a class="btn btn-default" href="{{ route('events.index') }}">
                            <i class="fa fa-arrow-left"></i> Browse More Events
                        </a>


                        @if($event->is_active && $event->minPrice() != null)
                            @if($event->is_redirect)
                                <a href="{{ $event->redirect_url }}">
                                    <button type="button" class="btn btn-primary">
                                        @if($event->isPast())
                                            <i class="fa fa-ticket"></i> Order the recording
                                        @else
                                            <i class="fa fa-ticket"></i> Book your tickets now
                                        @endif
                                    </button>
                                </a>
                            @else
                                <a href="/events/{{ $event->slug }}/register">
                                    <button class="btn btn-primary">
                                        @if($event->isPast())
                                            <i class="fa fa-ticket"></i> Order the recording
                                        @else
                                            @if (auth()->guest())
                                                <i class="fa fa-unlock"></i> Register
                                            @else
                                            <i class="fa fa-ticket"></i> Book your tickets now
                                            @endif
                                        @endif
                                    </button>
                                </a>
                            @endif
                        @else
                            <button class="btn btn-primary">
                                <i class="fa fa-ticket"></i> Registration closed
                            </button>
                        @endif

                        @if (auth()->guest())
                            <a href="#" data-toggle="modal" data-target="#login" class="btn btn-default" style="text-transform: uppercase; font-size: 14px">
                                <i class="fa fa-lock"></i> Login
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    {!! $event->description !!}

                    @if($event->video_url)
                        <div class="heading-title heading-border" style="margin-bottom: 20px">
                            <h4>Previous <span>Recording</span></h4>
                            <p class="font-lato size-17">{{ $event->video_title }}</p>
                        </div>

                        <video width="100%" controls="" style="margin-bottom: 15px">
                            <source src="{!! $event->video_url !!}" type="video/mp4">
                            <source src="{!! $event->video_url !!}" type="video/ogg">
                            Your browser does not support HTML5 video.
                        </video>
                    @endif

                    <div class="hidden-lg hidden-md">
                        <div style="height: 100px"></div>
                    </div>
                </div>

                <div class="col-md-4 ">
                    <div class="custom-md-4 text-center" style="padding: 15px">
                        <p class="font-lato size-17">Save this event to your <strong>Google</strong> or <strong>Outlook</strong> calendar.</p>
                        <a target="_blank" href="{{ $link->google() }}" class="social-icon social-icon-border social-gplus" data-toggle="tooltip" data-placement="top" title="" data-original-title="Google Calendar">
                            <i class="icon-gplus"></i>
                            <i class="icon-gplus"></i>
                        </a>

                        <a target="_blank" href="{{ $link->ics() }}" class="social-icon social-icon-border social-email3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Outlook Calendar">
                            <i class="icon-email3"></i>
                            <i class="icon-email3"></i>
                        </a>
                    </div>
                    <div class="margin-bottom-20"></div>
                    <div class="custom-md-4" style="padding: 15px">
                        @if($event->is_active && $event->minPrice() != null)
                            @if($event->is_redirect)
                                <a href="{{ $event->redirect_url }}">
                                    <button type="button" class="btn btn-3d btn-xlg btn-primary btn-block">
                                        @if($event->isPast())
                                            <i class="fa fa-ticket"></i> Order the recording
                                        @else
                                            <i class="fa fa-ticket"></i> Book your tickets now
                                        @endif
                                    </button>
                                </a>
                            @else
                                <a href="/events/{{ $event->slug }}/register">
                                    <button class="btn btn-3d btn-xlg btn-primary btn-block">
                                        @if($event->isPast())
                                            <i class="fa fa-ticket"></i> Order the recording
                                        @else
                                            <i class="fa fa-ticket"></i> Book your tickets now
                                        @endif
                                    </button>
                                </a>
                            @endif
                        @else
                            <button class="btn btn-3d btn-xlg btn-primary btn-block">
                                <i class="fa fa-ticket"></i> Registration closed
                            </button>
                        @endif

                        <hr>

                        <div class="heading-title heading-border" style="margin-bottom: 20px">
                            <h4 style="background-color: #f8f8f8;">Venues <span>& Dates</span></h4>
                            <p class="font-lato size-17">The following venues and dates are available for this event.</p>
                        </div>


                        @foreach((count($event->scopeActiveVenues) > 1 ? $event->scopeActiveVenues->chunk(2) : $event->scopeActiveVenues->chunk(1)) as $chunk)
                             <div class="row">
                                @foreach($chunk as $venue)

                                    <a href="{{ route('events.register', $event->slug) }}" class="venue-hover">
                                        <div class="{{ count($event->venues) > 1 ? 'col-md-6' : 'col-md-12' }} ">
                                            <div class="venue-holder">
                                                <span><strong>{!! ($venue->city ? : "Online Webinar") !!}</strong></span>
                                                @if($venue->is_active)
                                                    @foreach($venue->dates->where('is_active',1) as $date)
                                                        <div class="date" style="border-top: 1px solid #e3e3e3;; padding-top: 10px; margin-top: 10px">
                                                            <i class="fa fa-calendar"></i> {!! $date->date !!}
                                                        </div>
                                                        @if($venue->start_time!= "" && $venue->end_time != "")
                                                        <div class="date" style="border-top: 1px solid #e3e3e3;; padding-top: 10px; margin-top: 10px">
                                                            <i class="et-alarmclock"></i> {!! date("H:i A",strtotime($venue->start_time)) !!} - {!! date("H:i A",strtotime($venue->end_time)) !!}
                                                        </div>
                                                        @endif
                                                    @endforeach

                                                @elseif($venue->max_attendees)
                                                    <div class="date" style="border-top: 1px solid #e3e3e3; padding-top: 10px; margin-top: 10px">
                                                        <i class="fa fa-info"></i> SOLD OUT
                                                    </div>
                                                @else
                                                    @if(count($venue->dates->where('is_active',1)) && \Carbon\Carbon::parse($venue->dates->where('is_active',1)->first()->date) <= \Carbon\Carbon::today())
                                                        <div class="date" style="border-top: 1px solid #e3e3e3; padding-top: 10px; margin-top: 10px">
                                                            Venue Closed
                                                        </div>
                                                    @elseif($venue->is_active == false)
                                                        <div class="date" style="border-top: 1px solid #e3e3e3; padding-top: 10px; margin-top: 10px">
                                                            Venue Closed
                                                        </div>
                                                    @else
                                                        <div class="date" style="border-top: 1px solid #e3e3e3; padding-top: 10px; margin-top: 10px">
                                                            Coming Soon
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="venue-selected"></div>
                                        </div>
                                    </a>

                                @endforeach
                            </div>
                        @endforeach

                        @if(count($event->promoCodes) > 0)
                            <hr>
                            <div class="heading-title heading-border" style="margin-bottom: 20px">
                                <h4 style="background-color: #f8f8f8;">Discounts <span>available</span></h4>
                                <p class="font-lato size-17">The following discounts are available for this event</p>
                            </div>

                            @foreach($event->promoCodes as $promoCode)
                                <div class="panel panel-body">
                                    <strong>{{ $promoCode->title }}</strong>: {{ $promoCode->description }}
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="margin-bottom-20"></div>
                </div>
            </div>
        </div>
    </div>
    </section>
    <section class="alternate">
        <div class="container">
            <div class="row">
                <div class="heading-title heading-dotted text-center">
                    <h3>I'm ready to join, <span>please contact me</span></h3>
                </div>

                {!! Form::open(['method' => 'post', 'route' => ['information.request', $event->slug]]) !!}
                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                            {!! Form::label('first_name', 'First Name') !!}
                            {!! Form::input('text', 'first_name', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('last_name')) has-error @endif">
                            {!! Form::label('last_name', 'Last Name') !!}
                            {!! Form::input('text', 'last_name', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('email_address')) has-error @endif">
                            {!! Form::label('email_address', 'Email Address') !!}
                            {!! Form::input('text', 'email_address', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('email_address')) <p class="help-block">{{ $errors->first('email_address') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('cell')) has-error @endif">
                            {!! Form::label('cell', 'Cellphone Number') !!}
                            {!! Form::input('text', 'cell', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('cell')) <p class="help-block">{{ $errors->first('cell') }}</p> @endif
                        </div>
                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary"><i class="fa fa-phone"></i> Request Call Back</button>
                    </div>

                {!! Form::close() !!}
            </div>
        </div>
    </section>
@stop