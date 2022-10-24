<div class="form-group @if ($errors->has('to')) has-error @endif">
    {!! Form::label('to', 'Select to date') !!}
    {!! Form::input('text', 'to', null, ['class' => 'form-control is-date']) !!}
    @if ($errors->has('to')) <p class="help-block">{{ $errors->first('to') }}</p> @endif
</div>

{!! Form::submit($button, ['class' => 'btn btn-success']) !!}