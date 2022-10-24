<div id="merge_profile" class="modal fade text-left" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method' => 'post', 'route' => ['admin.members.merge_profile', $member->id], 'id' => 'merge_profile_form']) !!}

            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('merge_user', 'Please select the profile you would like to merge into this profile (This profile you are on will remain the main profile).') !!}
                    <div>
                        {!! Form::select('merge_user', [], null, ['placeholder' => 'Search here....', 'id' => 'merge_profile_email','class' => 'form-control', 'style' => 'width:100%']) !!}
                    </div>
                </div>

                <div class="text-right">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info" id="merge_profile_submit">Confirm Merge</button>
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>