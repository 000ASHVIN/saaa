<div id="event_tickets_{{ $id }}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="event_tickets" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title text-center">Your Tickets</h4>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    {!! var_dump($tickets) !!}
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Delegate</th>
                            <th>Venue</th>
                            {{-- <th>Date</th> --}}
                            <th>Ticket price</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($tickets as $ticket)

                            <tr>
                                <td>{{ $ticket->first_name . ' ' . $ticket->last_name }}</td>
                                <td>{{ $ticket->venue->name }}</td>
                                {{-- <td>{{ $ticket->venue->dates->toFormattedDateString() }}</td> --}}
                                <td>R{{ $ticket->cost }}.00</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
</div>
