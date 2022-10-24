<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Author Name') !!}
    {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('description')) has-error @endif">
    {!! Form::label('description', 'Description') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => '10', 'col' => '5']) !!}
    @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
</div>

{!! Form::submit($submit, ['class' => 'btn btn-success']) !!}