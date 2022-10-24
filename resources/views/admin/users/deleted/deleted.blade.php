<div id="user{{$member->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Account Deleted</h4>
            </div>
            <div class="modal-body">
                <p>Full Name: {{ $member->first_name }} {{ $member->last_name }}</p>
                <p>Deleted at {{ date_format($member->deleted_at, 'F d, Y') }}</p>
                <hr>
                <h5><strong>Reason for delete</strong></h5>
                <hr>
                {!! $member->deleted_at_description !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>