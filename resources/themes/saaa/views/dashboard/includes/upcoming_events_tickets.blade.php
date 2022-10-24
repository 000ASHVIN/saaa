<br>
<div class="row">
    <div class="col-md-12">
        {!! Form::open(['method' => 'get', 'route' => 'dashboard.events', 'id' => 'frm_upcoming_search']) !!}
        <div class="search-form">
            {!! Form::hidden('type', 'upcoming_events', ['id'=>'search_upcoming_event']) !!}
            {!! Form::hidden('upcoming_filter', $request->upcoming_filter?$request->upcoming_filter:'included', ['id'=>'hdn_upcoming_filter']) !!}
            <div class="row">
                <div class="col-md-3 col-lg-3">
                    <div class="form-group">
                        <label for="title"><strong>Event Name</strong></label>
                        {!! Form::input('text', 'title', $activeTab=='upcoming_events'?$request->title:null, ['class' => 'form-control event-title-filter', 'placeholder' => 'Event Name']) !!}
                    </div>
                </div>

                <div class="col-md-3 col-lg-3">
                    <div class="form-group">
                        <label for="subscription"><strong>Included in Subscription</strong></label>
                        {!! Form::select('subscription', ['yes' => 'Yes', 'no' => 'No'], $activeTab=='upcoming_events'?$request->subscription:null, ['class' => 'form-control', 'placeholder' => 'Included in Subscription']) !!}
                    </div>
                </div>

                <div class="col-md-3 col-lg-3">
                    <div class="form-group">
                        <label for="categories"><strong>Event Category</strong></label>
                        {!! Form::select('categories', $categories, $activeTab=='upcoming_events'?$request->categories:null, ['class' => 'form-control','placeholder' => 'Event Category']) !!}
                        @if ($errors->has('categories')) <p class="help-block">{{ $errors->first('tags') }}</p> @endif
                    </div>
                </div>

                <div class="col-md-3 col-lg-3">
                    <div class="form-group">
                        <button class="btn btn-primary search-button" onclick="search_spin(this)"><i class="fa fa-search"></i> Seach For Events</button>
                        @if(Request::is('filter_events'))
                            <a href="{{ route('dashboard.events.index') }}" class="btn btn-warning"><i class="fa fa-close"></i> Clear Search</a>
                        @endif
                    </div>
                </div>
            </div>                                                          
        </div>
        {!! Form::close() !!}

        <div class="">
            <span>Filter: </span>
            {!! Form::select('upcoming_filter', [
                'included'=>'Included',
                'paid'=>'Paid',
                'free'=>'Free'
            ], $request->upcoming_filter?$request->upcoming_filter:'included', ['id' => 'upcoming_filter']) !!}
        </div>

    </div>
</div>

<hr>
<div class="row">
    <div class="col-md-12">
        @if(count($upcomingEvents))
            @foreach($upcomingEvents as $event)
                <div class="new-events-view event-container-box clearfix">
                    <div class="event-container-inner">
                        <div class="row">
                            <div class="col-md-7 col-lg-8">
                                <h4>{{ $event->name }}</h4>
                                <h5>
                                    @if (count($event->presenters))
                                        <i class="fa fa-user"></i>
                                        {{ implode(', ',$event->presenters->pluck('name')->toArray()).' | ' }}
                                    @endif
                                    @if (count($event->categories))
                                        <i class="fa fa-plus"></i>
                                        {{ implode(', ',$event->categories->pluck('title')->toArray()).' | ' }}
                                    @endif
                                    @if (count($event->cpd_hours))
                                        <i class="fa fa-clock-o"></i>
                                        {{ $event->cpd_hours }} Hours | 
                                    @endif

                                    @if (count($event->upcomingDate()))
                                        <i class="fa fa-calendar-o"></i>
                                        {{ date_format($event->upcomingDate(), 'd F Y') }}
                                    @endif
                                    
                                </h5>
                                @if($currentPlanEvents->contains($event->id))
                                <h5 style="text-transform: none;"><i>Included in your Subscription</i></h5>
                                @else
                                <h5 style="text-transform: none;"><i>Not included in your Subscription</i></h5>
                                @endif
                            </div>
                            <div class="col-md-5 col-lg-4">
                                    <a href="{{ route('events.show', $event->slug) }}" class="btn btn-default upcoming-btn-container"></i>Read More</a>
                                    @if (in_array($event->id, $purchased_event_ids))
                                        <a href="#" class="btn btn-warning disabled upcoming-btn-container">Registered</a>
                                    @else 
                                        <a href="{{ route('events.register', $event->slug) }}" class="btn btn-primary upcoming-btn-container">Register Now</a>   
                                    @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        <div class="row text-center">
            {!! $upcomingEvents->render() !!}
        </div>


    @else

        <div class="alert alert-info" style="margin-bottom: 0px">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">X</span><span
                        class="sr-only">Close</span></button>
            <strong>Heads up!</strong> You have not registered for any events yet, to register for an event
            <a href="{!! route('events.index') !!}">click here</a>
        </div>
    @endif
    </div>
</div>


