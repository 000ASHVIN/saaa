<div class="form-group">
    {!! Form::label('name', 'Feature name') !!}
    {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('description', 'Feature description') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3]) !!}
</div>

<div class="form-group">
    {!! Form::label('year', 'Topic Year') !!}
    {!! Form::text('year', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group @if ($errors->has('selectable')) has-error @endif">
    {!! Form::label('selectable', 'Can this topic be selected for custom packages?') !!}
    {!! Form::select('selectable', [
        '' => 'Please Select',
        true => 'Yes',
        false => 'No'
    ], null, ['class' => 'form-control']) !!}
    @if ($errors->has('selectable')) <p class="help-block">{{ $errors->first('selectable') }}</p> @endif
</div>

<div class="form-group">
    {!! Form::hidden('value', 1, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::submit($SubmitButton, ['class' => 'btn btn-info']) !!}
</div>