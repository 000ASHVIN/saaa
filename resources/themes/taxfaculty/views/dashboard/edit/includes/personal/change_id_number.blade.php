<div id="change_id_number" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::model($user, ['method' => 'POST', 'route' => ['dashboard.id_number_update', $user->id]]) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Change ID Number</h4>
            </div>
           
            <div class="modal-body">
                <div class="alert alert-info">
                    <p>
                        Kindly note that upon submission our system will log an email on your behalf requesting that your
                        ID number should be updated to your new one.
                    </p>
                </div>

                <div class="form-group @if ($errors->has('id_number')) has-error @endif">
                    {!! Form::label('id_number', 'Identity Number') !!}
                    {!! Form::input('text', 'id_number', null, ['class' => 'form-control', 'disabled' => true]) !!}
                    @if ($errors->has('id_number')) <p class="help-block">{{ $errors->first('id_number') }}</p> @endif
                </div>

                <div class="form-group @if ($errors->has('new_id_number')) has-error @endif">
                    <span class="pull-left">{!! Form::label('new_id_number', 'South African ID Number') !!}</span>
                    {!! Form::input('text', 'new_id_number', null, ['class' => 'form-control', 'id' => 'new_id_number', 'min' => '13']) !!}
                    @if ($errors->has('new_id_number'))<p class="help-block">{{ $errors->first('new_id_number') }}</p> @endif
                </div>

                <span id="id_results"></span>
                <input type="hidden" name="verified" id="valid_id_number">
                <input type="hidden" id="date">
                <input type="hidden" id="age">
                <input type="hidden" id="gender">
                <input type="hidden" id="citizen">

                <div class="form-group @if ($errors->has('reason')) has-error @endif">
                    {!! Form::label('reason', 'Reason:') !!}
                    {!! Form::textarea('reason', null, ['class' => 'form-control', 'rows' => '2']) !!}
                    @if ($errors->has('reason')) <p class="help-block">{{ $errors->first('reason') }}</p> @endif
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" onclick="spin(this)"><i class="fa fa-envelope-o"></i> Send Request</button>
            </div>
            
            {!! Form::close() !!}
        </div>
    </div>
</div>