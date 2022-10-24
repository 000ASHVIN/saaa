<div id="cancel_profile" class="modal fade text-left" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'post', 'route' => ['admin.members.cancel_profile', $member->id]]) !!}

            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('deleted_at_description', 'Please stipulate your reason for cancellation') !!}
                    {!! Form::textarea('deleted_at_description', null, ['class' => 'form-control', 'rows' => '3']) !!}
                </div>

                <div class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Confirm Cancellation</button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>