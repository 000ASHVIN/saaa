<div id="assignOwnership" class="modal fade" role="dialog">
    <div class="modal-dialog">
        {!! Form::open(['method' => 'post', 'route' => ['admin.reps.assign', $member->id]]) !!}
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h4 class="heaing">Assign Account Ownership</h4>
            </div>

            <div class="modal-body">
                <div class="form-group @if ($errors->has('agent')) has-error @endif">
                    {!! Form::label('agent', 'Choose your agent') !!}
                    {!! Form::select('agent', $reps, null, ['class' => 'form-control']) !!}
                    @if ($errors->has('agent')) <p class="help-block">{{ $errors->first('agent') }}</p> @endif
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Assign</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>