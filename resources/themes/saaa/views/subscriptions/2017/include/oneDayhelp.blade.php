<div class="modal fade" id="need_help_subscription_one" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">I Need help with One Day Only</h4>
            </div>

            <div class="modal-body">
                {!! Form::open(['method' => 'post', 'route' => 'information_for_subscription']) !!}
                <div class="col-md-6">
                    <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                        {!! Form::label('first_name', 'First Name') !!}
                        {!! Form::input('text', 'first_name', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group @if ($errors->has('last_name')) has-error @endif">
                        {!! Form::label('last_name', 'Last Name') !!}
                        {!! Form::input('text', 'last_name', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group @if ($errors->has('email_address')) has-error @endif">
                        {!! Form::label('email_address', 'Email Address') !!}
                        {!! Form::input('email', 'email_address', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('email_address')) <p class="help-block">{{ $errors->first('email_address') }}</p> @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group @if ($errors->has('cell')) has-error @endif">
                        {!! Form::label('cell', 'Cellphone Number') !!}
                        {!! Form::input('text', 'cell', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('cell')) <p class="help-block">{{ $errors->first('cell') }}</p> @endif
                    </div>
                </div>

                <div class="text-center">
                    <button onclick="spin(this)" class="btn btn-primary"><i class="fa fa-phone"></i> Submit</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
