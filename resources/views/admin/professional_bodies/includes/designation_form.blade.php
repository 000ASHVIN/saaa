{!! Form::hidden('body_id', $body->id) !!}

<div class="form-group @if ($errors->has('title')) has-error @endif">
    {!! Form::label('title', 'Designation Title') !!}
    {!! Form::input('text', 'title', null, ['class' => 'form-control']) !!}
    @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('description')) has-error @endif">
    {!! Form::label('description', 'Description') !!}
    {!! Form::input('text', 'description', null, ['class' => 'form-control']) !!}
    @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('interval')) has-error @endif">
    {!! Form::label('interval', 'Interval') !!}
    {!! Form::select('interval', [
        null => 'Please select',
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('interval')) <p class="help-block">{{ $errors->first('interval') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('tax')) has-error @endif">
    {!! Form::label('tax', 'Tax Hours') !!}
    {!! Form::input('text', 'tax', null, ['class' => 'form-control', 'placeholder' => '2.5 Hours']) !!}
    @if ($errors->has('tax')) <p class="help-block">{{ $errors->first('tax') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('ethics')) has-error @endif">
    {!! Form::label('ethics', 'Ethics Hours') !!}
    {!! Form::input('text', 'ethics', null, ['class' => 'form-control', 'placeholder' => '2.5 Hours']) !!}
    @if ($errors->has('ethics')) <p class="help-block">{{ $errors->first('ethics') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('auditing')) has-error @endif">
    {!! Form::label('auditing', 'Auditing Hours') !!}
    {!! Form::input('text', 'auditing', null, ['class' => 'form-control', 'placeholder' => '2.5 Hours']) !!}
    @if ($errors->has('auditing')) <p class="help-block">{{ $errors->first('auditing') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('accounting')) has-error @endif">
    {!! Form::label('accounting', 'Accounting Hours') !!}
    {!! Form::input('text', 'accounting', null, ['class' => 'form-control', 'placeholder' => '2.5 Hours']) !!}
    @if ($errors->has('accounting')) <p class="help-block">{{ $errors->first('accounting') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('verifiable')) has-error @endif">
    {!! Form::label('verifiable', 'Verifiable Hours') !!}
    {!! Form::input('text', 'verifiable', null, ['class' => 'form-control', 'placeholder' => '2.5 Hours']) !!}
    @if ($errors->has('verifiable')) <p class="help-block">{{ $errors->first('verifiable') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('non_verifiable')) has-error @endif">
    {!! Form::label('non_verifiable', 'Non Verifiable Hours') !!}
    {!! Form::input('text', 'non_verifiable', null, ['class' => 'form-control', 'placeholder' => '2.5 Hours']) !!}
    @if ($errors->has('non_verifiable')) <p class="help-block">{{ $errors->first('non_verifiable') }}</p> @endif
</div>

{!! Form::submit($submit, ['class' => 'btn btn-primary']) !!}