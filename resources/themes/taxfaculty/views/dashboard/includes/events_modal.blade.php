<div class="modal fade bs-example-modal-lg"  id="event_modal-{{$ticket->id}}" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Attendees <span>Booked</span></h4>
                <h6 style="margin-bottom: 0px">Event: {{ $ticket->event->name }}</h6>
            </div>

            <div class="modal-body">

                 {{--<div class="heading-title heading-border-bottom" style="margin-bottom: 10px;">--}}
                    {{--<h4>Event <span>Resources</span></h4>--}}
                {{--</div>--}}
                 {{--<div class="row">--}}
                    {{--<div class="col-md-4"><span class="et-video"></span><a href="#"> View Event Recording</a></div>--}}
                    {{--<div class="col-md-4"><span class="et-document"></span> <a href="#"> Resource Name</a></div>--}}
                    {{--<div class="col-md-4"><span class="et-clock"></span> <a href="#"> Start Event Quiz</a></div>--}}
                {{--</div>--}}

                 {{--<hr> --}}

                <div id="" style="overflow:scroll; height:200px; overflow-x: auto; overflow-y: auto">
                    <table class="table table-hover table-vertical-middle">
                        <thead>
                        <th>Full Name</th>
                        <th>Ticket Number</th>
                        <th>Venue</th>
                        <th>Start Date</th>
                        <th>Ticket Description</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $ticket->first_name }} {{ $ticket->last_name }}</td>
                            <td>{{ $ticket->code }}</td>
                            <td>{{ $ticket->venue->name }}</td>
                            <td>@if($ticket->dates()->first()) {{ $ticket->dates()->first()->date }} @endif</td>
                            <td>{{ $ticket->description }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
