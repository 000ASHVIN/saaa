<div class="row">
    <div class="col-md-12">
        {!! Form::open(['method' => 'post', 'route' => 'frontend.events.filter_events']) !!}
        <div class="search-form">
            <div class="row">
                @if (isset($select) && $select=='past' )
                    <div class="col-md-12">
                        <h1 class="size-20 mb-20">Past Events & Recordings</h1>
                    </div>
                @endif
                <div class="col-md-3">
                    <div class="form-group @if ($errors->has('title')) has-error @endif">
                        <label for="title"><strong>Event Name</strong></label>
                        {!! Form::input('text', 'title', null, ['class' => 'form-control event-title-filter', 'placeholder' => 'Compliance...']) !!}
                        @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group @if ($errors->has('date_filter')) has-error @endif">
                        <label for="date_filter"><strong>Past / Upcoming</strong></label>
                        {!! Form::select('date_filter', [
                            null => 'Please Select',
                            'past' => 'Past Events',
                            'upcoming' => 'Upcoming Events'
                        ], (isset($select))? $select : null, ['class' => 'form-control']) !!}
                        @if ($errors->has('date_filter')) <p class="help-block">{{ $errors->first('date_filter') }}</p> @endif
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group @if ($errors->has('type')) has-error @endif">
                        <label for="type"><strong>Event Type</strong></label>
                        {!! Form::select('type', [
                            null => 'Please Select',
                            'webinar' => 'Webinar',
                            'seminar' => 'Seminar',
                            'conference' => 'Conference'
                        ],null, ['class' => 'form-control event-type-filter']) !!}
                        @if ($errors->has('type')) <p class="help-block">{{ $errors->first('type') }}</p> @endif
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group @if ($errors->has('start_date')) has-error @endif">
                        <label for="start_date"><strong>Start Date</strong></label>
                        {!! Form::input('text', 'start_date', null, ['class' => 'form-control datepicker box', 'data-format' => 'yyyy-mm-dd']) !!}
                        @if ($errors->has('start_date')) <p class="help-block">{{ $errors->first('start_date') }}</p> @endif
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group @if ($errors->has('venue')) has-error @endif">
                        <label for="venue"><strong>Select Venue</strong></label>
                        {!! Form::select('venue', [
                            '' => 'Please Select',
                            'Bloemfontein' => 'Bloemfontein',
                            'George' => 'George',
                            'Port Elizabeth' => 'Port Elizabeth',
                            'Pretoria' => 'Pretoria',
                            'Sandton' => 'Sandton',
                            'Durban' => 'Durban',
                            'Polokwane' => 'Polokwane',
                            'bellville' => 'Bellville',
                            'Webinar' => 'Online Webinar',
                        ],null, ['class' => 'form-control event-venue-filter']) !!}
                        @if ($errors->has('venue')) <p class="help-block">{{ $errors->first('venue') }}</p> @endif
                    </div>
                </div>
            </div>

            <button class="btn btn-primary" onclick="search_spin(this)"><i class="fa fa-search"></i> Seach For Events</button>
            @if(Request::is('filter_events'))
                <a href="{{ route('events.index') }}" class="btn btn-warning"><i class="fa fa-close"></i> Clear Search</a>
            @endif
            {!! Form::close() !!}
        </div>
    </div>
</div>