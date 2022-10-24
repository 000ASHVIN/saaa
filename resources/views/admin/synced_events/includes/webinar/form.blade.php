<div class="form-group @if ($errors->has('url')) has-error @endif">
    {!! Form::label('url', 'Webinar Link') !!}
    {!! Form::input('text', 'url', null, ['class' => 'form-control']) !!}
    @if ($errors->has('url')) <p class="help-block">{{ $errors->first('url') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('is_active')) has-error @endif">
    {!! Form::label('is_active', 'Status') !!}
    {!! Form::select('is_active', [
        true => 'Active',
        false => 'Not Active'
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('is_active')) <p class="help-block">{{ $errors->first('is_active') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('passcode')) has-error @endif">
    {!! Form::label('passcode', 'Passcode') !!}
    {!! Form::input('text', 'passcode', null, ['class' => 'form-control']) !!}
    @if ($errors->has('passcode')) <p class="help-block">{{ $errors->first('passcode') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('code')) has-error @endif">
    {!! Form::label('code', 'Webinar Room Code') !!}
    {!! Form::input('text', 'code', null, ['class' => 'form-control']) !!}
    @if ($errors->has('code')) <p class="help-block">{{ $errors->first('code') }}</p> @endif
</div>

<hr>
{!! Form::submit($submit, ['class' => 'btn btn-info']) !!}
<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>