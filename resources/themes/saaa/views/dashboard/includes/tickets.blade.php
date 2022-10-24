@if(count($tickets))

    @if(isset($year))
        <a href="{{ route('dashboard.events') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back to Events</a>
        <hr>
        <table class="table table-hover">
            <thead>
                <th class="text-left">Upcoming Event</th>
                <th class="text-left">Start Time</th>
                <th class="text-left">Date</th>
                <th>Status</th>
                <th>CPD</th>
                <th class="text-center">CPD Catchup</th>
            </thead>
            <tbody>

            @foreach($tickets as $ticket)
                <tr>
                    <td class="text-left">
                        <a href="" data-target="#event_modal-{{$ticket->id}}" data-toggle="tooltip" title="{{ $ticket->event->name }}" data-placement="top">
                            {{ str_limit($ticket->event->name, $limit = 35) }}
                        </a>
                        @include('dashboard.includes.events_modal', $ticket)
                    </td>

                    <td valign="top" class="text-left">
                        <i class="fa fa-clock-o"></i> {{ ($ticket->venue->start_time!="" && $ticket->venue->start_time!="1970-01-01 00:00:00")?Date("H:i:s",strtotime($ticket->venue->start_time)):Date("H:i:s",strtotime($ticket->event->start_time))  }} <br>
                    </td>

                    <td valign="top" class="text-left">
                        @foreach($ticket->dates as $date)
                            {{ $date->date }} <br>
                        @endforeach
                    </td>

                    <td valign="top" class="text-left">
                        @if($ticket->invoice_order)
                            @if($ticket->invoice_order->status == 'paid')
                                <a href="{{ route('order.show',$ticket->invoice_order->id) }}"
                                   class="label label-success" data-toggle="tooltip" title="Paid" data-placement="right"><i
                                            class="fa fa-check"></i></a>
                            @else
                                @if($ticket->invoice_order->status == 'unpaid')
                                    <a href="{{ route('order.show',$ticket->invoice_order->id) }}"
                                       class="label label-warning" data-toggle="tooltip" title="Unpaid"
                                       data-placement="right"> <i class="fa fa-times"></i>
                                    </a>
                                @else
                                    <a href="{{ route('order.show',$ticket->invoice_order->id) }}"
                                       class="label label-danger" data-toggle="tooltip" title="Cancelled"
                                       data-placement="right"> <i class="fa fa-ban"></i>
                                    </a>
                                @endif
                            @endif
                        @else
                            None
                        @endif
                    </td>

                    <td>
                        @if($ticket->pricing && $ticket->pricing->cpd_hours > 0)
                            @if(\App\Users\Cpd::where('user_id', $ticket->user->id)->where('ticket_id', $ticket->id)->first())
                                <span href="#" data-toggle="tooltip" title="" data-placement="top" data-original-title="Claimed">
                                    <i class="fa fa-check"></i>
                                </span>
                            @else
                                <span href="#" data-toggle="tooltip" title="" data-placement="top" data-original-title="Pending">
                                    <i class="fa fa-close"></i>
                                </span>
                            @endif
                        @endif
                    </td>

                    <td valign="top" class="text-right">
                        @if(($ticket->invoice_order && $ticket->invoice_order->status == 'paid'))
                            <span class="label label-primary" style="background-color: #173175">
                                <a href="{{ route('dashboard.tickets.links-and-resources',$ticket->id) }}" style="color: white"><i class="fa fa-link"></i> Links &amp; resources</a>
                            </span>
                        @elseif(! $ticket->invoice_order)
                            <span class="label label-primary" style="background-color: #173175">
                            <a href="{{ route('dashboard.tickets.links-and-resources',$ticket->id) }}"
                               style="color: white"><i class="fa fa-link"></i> Links &amp; resources</a>
                        </span>
                        @else
                            <span class="label" style="background-color: #666; color: #ccc;"><i class="fa fa-link"></i> Links &amp; resources </span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @include('dashboard.includes.event-popup',['ticket' => $ticket])
    @else
        <div class="row">

            @if ($showCurrentYear)
                <div class="col-md-6" style="margin-bottom: 30px">
                    <div class="border-box text-center">
                        <h4><strong>{{ $currentYear }}</strong> Events</h4>
                        <p>View all my purchased events for {{ $currentYear }} with webinar <br> recordings, files and assessments</p>
                        <a href="{{ route('dashboard.events', $currentYear) }}" class="btn btn-primary"><i class="fa fa-ticket"></i> View Events</a>
                    </div>
                </div>
            @endif


            @foreach($tickets->groupBy('years') as $key => $value)
                <div class="col-md-6" style="margin-bottom: 30px">
                    <div class="border-box text-center">
                        <h4><strong>{{ $key }}</strong> Events</h4>
                        <p>View all my purchased events for {{ $key }} with webinar <br> recordings, files and assessments</p>
                        <a href="{{ route('dashboard.events', $key) }}" class="btn btn-primary"><i class="fa fa-ticket"></i> View Events</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif



@else

    <div class="alert alert-info" style="margin-bottom: 0px">
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">X</span><span
                    class="sr-only">Close</span></button>
        <strong>Heads up!</strong> You have not registered for any events yet, to register for an event
        <a href="{!! route('events.index') !!}">click here</a>
    </div>
@endif


