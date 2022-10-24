<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Name') !!}
    {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('title')) has-error @endif">
    {!! Form::label('title', 'Title') !!}
    {!! Form::input('text', 'title', null, ['class' => 'form-control']) !!}
    @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('hidden')) has-error @endif">
    {!! Form::label('hidden', 'Open To Public') !!}
    {!! Form::select('hidden', [
        '0' => 'Vissible',
        '1' => 'Not Vissible'
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('hidden')) <p class="help-block">{{ $errors->first('hidden') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('company')) has-error @endif">
    {!! Form::label('company', 'Company') !!}
    {!! Form::input('text', 'company', null, ['class' => 'form-control']) !!}
    @if ($errors->has('company')) <p class="help-block">{{ $errors->first('company') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('avatar')) has-error @endif">
    {!! Form::label('avatar', 'Avatar') !!}
    {!! Form::file('avatar', null, ['class' => 'form-control']) !!}
    @if ($errors->has('avatar')) <p class="help-block">{{ $errors->first('avatar') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('bio')) has-error @endif">
    {!! Form::label('bio', 'Biography') !!}
    {!! Form::textarea('bio', null, ['class' => 'ckeditor form-control']) !!}
    @if ($errors->has('bio')) <p class="help-block">{{ $errors->first('bio') }}</p> @endif
</div>

<a class="btn btn-info" href="{{ route('admin.presenters.index') }}">Back</a>
{!! Form::submit($submit, ['class' => 'btn btn-success']) !!}
