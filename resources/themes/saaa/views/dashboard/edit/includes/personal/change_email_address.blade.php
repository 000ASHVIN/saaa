<div id="change_email_address" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::model($user, ['method' => 'post', 'route' => ['dashboard.email_address_update', $user]]) !!}

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Change Email Address</h4>
            </div>

            <div class="modal-body">
                <div class="alert alert-info">
                    <p>
                        Kindly note that upon submission our system will log an email on your behalf requesting that your
                        email address should be updated to your new one.
                    </p>
                </div>

                    <div class="form-group @if ($errors->has('email')) has-error @endif">
                        {!! Form::label('email', 'Email Address') !!}
                        {!! Form::input('email', 'email', null, ['class' => 'form-control', 'disabled' => true]) !!}
                        @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                    </div>

                    <div class="form-group @if ($errors->has('new_email_address')) has-error @endif">
                        {!! Form::label('new_email_address', 'New Email Address') !!}
                        {!! Form::input('email', 'new_email_address', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('new_email_address'))
                            <p class="help-block">{{ $errors->first('new_email_address') }}</p> @endif
                    </div>

                    <div class="form-group @if ($errors->has('reason')) has-error @endif">
                        {!! Form::label('reason', 'Reason for change') !!}
                        {!! Form::textarea('reason', null, ['class' => 'form-control', 'rows' => '2']) !!}
                        @if ($errors->has('reason')) <p class="help-block">{{ $errors->first('reason') }}</p> @endif
                    </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="spin(this)"><i class="fa fa-envelope-o"></i> Send Request</button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>