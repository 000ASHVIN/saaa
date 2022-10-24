<div class="form-group @if ($errors->has('url')) has-error @endif">
    {!! Form::label('url', 'Link Url') !!}
    {!! Form::input('text', 'url', null, ['class' => 'form-control']) !!}
    @if ($errors->has('url')) <p class="help-block">{{ $errors->first('url') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Name') !!}
    {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('instructions')) has-error @endif">
    {!! Form::label('instructions', 'Instructions') !!}
    {!! Form::input('text', 'instructions', null, ['class' => 'form-control']) !!}
    @if ($errors->has('instructions'))
        <p class="help-block">{{ $errors->first('instructions') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('secret')) has-error @endif">
    {!! Form::label('secret', 'Password') !!}
    {!! Form::input('text', 'secret', null, ['class' => 'form-control']) !!}
    @if ($errors->has('secret')) <p class="help-block">{{ $errors->first('secret') }}</p> @endif
</div>

<div class="pull-right">
    <button data-dismiss="modal" class="btn btn-default">Close</button>
    {!! Form::submit($submit, ['class' => 'btn btn-primary']) !!}
</div>