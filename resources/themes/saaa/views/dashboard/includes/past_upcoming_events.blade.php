
<div class="row">
    <div class="col-md-12">
    @if(count($myTickets))
        @foreach($myTickets as $ticket)
                <div class="new-events-view event-container-box clearfix">
                    <div class="event-container-inner">
                        <div class="row">
                            <div class="col-md-9">
                                <h4>{{ $ticket->event->name }}</h4>
                                <h5>
                                    @if (count($ticket->event->presenters))
                                        <i class="fa fa-user"></i>
                                        {{ implode(', ',$ticket->event->presenters->pluck('name')->toArray()).' | ' }}
                                    @endif
                                    @if (count($ticket->event->categories))
                                        <i class="fa fa-plus"></i>
                                        {{ implode(', ',$ticket->event->categories->pluck('title')->toArray()).' | ' }}
                                    @endif

                                    @if (count($ticket->event->cpd_hours))
                                        <i class="fa fa-plus"></i>
                                        {{ $ticket->event->cpd_hours }} Hours | 
                                    @endif

                                    @if ($ticket->venue->activeDates && $ticket->venue->activeDates->count())
                                    <i class="fa fa-calendar-o"></i>
                                    {{ ($ticket->venue->activeDates->first())? date( 'd F Y',strtotime($ticket->venue->activeDates->first()->date)):"" }}
                                @endif
                                    
                                    @if (count($ticket->event->start_time))
                                        <i class="fa fa-clock-o"></i>
                                        {{ $ticket->event->start_time }}
                                    @endif
                                </h5>
                            </div>
                            <div class="col-md-3">
                                @if($ticket->id)
                                <a href="{{ route('dashboard.tickets.links-and-resources',$ticket->id).'?recordings=1' }}" class="btn btn-default events-btn-container"><i class="fa fa-eye"></i>View Content</a> 
                                @else
                                <a href="{{ route('events.register', $ticket->event->slug) }}" class="btn btn-primary events-btn-container">Book Now</a>  
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        @endforeach
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
<div class="row text-center">
    {!! $myTickets->render() !!}
</div>