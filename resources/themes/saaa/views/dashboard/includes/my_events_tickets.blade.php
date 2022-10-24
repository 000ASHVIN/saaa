<br>
<div class="row">
    <div class="col-md-12">
        {!! Form::open(['method' => 'get', 'route' => 'dashboard.events', 'id' => 'frm_my_tickets']) !!}
        <div class="search-form">
            {!! Form::hidden('type', 'my_events', ['id'=>'search_my_event']) !!}
            {!! Form::hidden('event', '', ['id'=>'past_upcoming']) !!}
            {!! Form::hidden('completed_filter', $request->completed_filter, ['id'=>'hdn_completed_filter']) !!}
            
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="title"><strong>Event Name</strong></label>
                        {!! Form::input('text', 'title', $activeTab=='my_events'?$request->title:null, ['class' => 'form-control event-title-filter', 'placeholder' => 'Event Name']) !!}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="presenter"><strong>Event Presenter</strong></label>
                        {!! Form::select('presenter', $presenters, $activeTab=='my_events'?$request->presenter:null, ['class' => 'form-control', 'placeholder' => 'Event Presenter']) !!}
                        @if ($errors->has('presenter')) <p class="help-block">{{ $errors->first('presenter') }}</p> @endif
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tags"><strong>Event Category</strong></label>
                        {!! Form::select('categories', $categories, $activeTab=='my_events'?$request->categories:null, ['class' => 'form-control','placeholder' => 'Event Category']) !!}
                        @if ($errors->has('categories')) <p class="help-block">{{ $errors->first('tags') }}</p> @endif
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="event_year"><strong>Event Year</strong></label>
                        {!! Form::select('event_year', $event_years, empty($request->event_year)?$current_year:$request->event_year, ['class' => 'form-control','placeholder' => 'Event Year', 'id'=>'event_year']) !!}
                        @if ($errors->has('event_year')) <p class="help-block">{{ $errors->first('event_year') }}</p> @endif
                    </div>
                </div>
                <div class="row past_event_cpd">
                    <div class="col-md-12">
                        <div class="button-filters">
                            <label class="switch switch switch-round">
                                <span style="vertical-align:top;">Completed CPD hours: </span>
                                <input type="checkbox" name="completed_filter" id="completed_filter" {{ $request->completed_filter?'checked':'' }} value="1">
                                <span class="switch-label" data-on="Yes"
                                    data-off="No"></span>

                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <button class="btn btn-primary" onclick="search_spin(this)"><i class="fa fa-search"></i> Seach For Events</button>
                    @if(Request::is('filter_events'))
                        <a href="{{ route('dashboard.events.index') }}" class="btn btn-warning"><i class="fa fa-close"></i> Clear Search</a>
                    @endif
                </div>
            </div>   
        </div>
        {!! Form::close() !!}
    </div>
</div>

    <hr>
<div class="col-lg-12 col-md-12 col-sm-12" id="myEventsTab">
    <ul class="events-tab">
        <li class="tablist {{ $eventsActiveTab=='my_upcoming_events'?'active':'' }}"><a data-id="my_upcoming_events" href="#my_upcoming_event" data-toggle="tab" class="btn btn-default">Upcoming Events</a></li>
        <li class = "tablist {{ $eventsActiveTab=='past_events'?'active':'' }}"><a data-id="past_events" href="#past_event" data-toggle="tab" class="btn btn-default">Past Events</a></li>
    </ul>

    <div class="tab-content">

        <div class="tab-pane fade in {{ $eventsActiveTab=='my_upcoming_events'?'active':'' }}" id="my_upcoming_event">
            <?php
                $myTickets = $my_upcoming_events;
            ?>
            @include('dashboard.includes.past_upcoming_events')
        </div>

        <div class="tab-pane fade in {{ $eventsActiveTab=='past_events'?'active':'' }}" id="past_event">
            <?php
                $myTickets = $past_events;
                $is_past_event = true;
            ?>
            @include('dashboard.includes.past_upcoming_events')
        </div>
    </div>
</div>

