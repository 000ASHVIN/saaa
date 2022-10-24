<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Name') !!}
    {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('email')) has-error @endif">
    {!! Form::label('email', 'Email Address') !!}
    {!! Form::input('text', 'email', null, ['class' => 'form-control']) !!}
    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('user_id')) has-error @endif">
    {!! Form::label('user_id', 'Actual User Account') !!}
    {!! Form::select('user_id', $agents->pluck('name', 'id'), null, ['class' => 'form-control']) !!}
    @if ($errors->has('user_id')) <p class="help-block">{{ $errors->first('user_id') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('active')) has-error @endif">
    {!! Form::label('active', 'Status') !!}
    {!! Form::select('active', ['1'=>'Active','0'=>'Not Active'], null, ['class' => 'form-control']) !!}
    @if ($errors->has('active')) <p class="help-block">{{ $errors->first('active') }}</p> @endif
</div>