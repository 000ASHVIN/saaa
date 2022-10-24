<div class="form-group @if ($errors->has('title')) has-error @endif">
    {!! Form::label('title', 'Title') !!}
    {!! Form::input('text', 'title', null, ['class' => 'form-control']) !!}
    @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('active')) has-error @endif">
    {!! Form::label('active', 'Display in store') !!}
    {!! Form::select('active', [
        true => 'Yes',
        false => 'No'
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('active')) <p class="help-block">{{ $errors->first('active') }}</p> @endif
</div>

{!! Form::submit($button, ['class' => 'btn btn-success']) !!}