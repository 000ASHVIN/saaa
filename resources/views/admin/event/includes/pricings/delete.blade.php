<div id="pricing_delete_{{$pricing->id}}" class="modal fade modal-aside horizontal right" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Delete - {{ $pricing->name }}</h4>
            </div>
            <div class="modal-body">
                <p><i><strong>By removing this pricing option, you will effect {{ count($pricing->tickets) }} Client
                            Tickets and cannot be reversed!</strong></i></p>
                <hr>
                {!! Form::open(['method' => 'POST', 'route' => ['admin.event.pricings.destroy', $pricing->id]]) !!}
                <div class="panel panel-default">
                    <div class="panel-heading"><h4 class="panel-title"><strong>Tickets</strong></h4></div>
                    <div class="panel-body" style="max-height: 200px; overflow-y: overlay;">
                        @if (count($pricing->tickets))
                            @foreach($pricing->tickets as $ticket)
                                <p><strong>{{ $ticket->code }}</strong> - {{ $ticket->user->first_name.' '.$ticket->user->last_name }}</p>
                                <hr>
                            @endforeach
                        @else
                            <p>There are no tickets for this pricing.</p>
                            <hr>
                        @endif

                    </div>
                </div>
                <button type="submit" class="btn btn-danger"><i class="fa fa-times"></i> Confirm</button>
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>