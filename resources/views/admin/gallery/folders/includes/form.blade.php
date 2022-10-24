<div class="form-group @if ($errors->has('title')) has-error @endif">
    {!! Form::label('title', 'Title') !!}
    {!! Form::input('text', 'title', null, ['class' => 'form-control']) !!}
    @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('date')) has-error @endif">
    {!! Form::label('date', 'Date') !!}
    {!! Form::input('text', 'date', null, ['class' => 'form-control is-date form-control']) !!}
    @if ($errors->has('date')) <p class="help-block">{{ $errors->first('date') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('type')) has-error @endif">
    {!! Form::label('type', 'Type') !!}
    {!! Form::select('type', [
        'seminar' => 'Seminar',
        'webinar' => 'Webinar',
        'conference' => 'Conference',
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('type')) <p class="help-block">{{ $errors->first('type') }}</p> @endif
</div>

<a href="{{ route('admin.folders.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
{!! Form::submit($button, ['class' => 'btn btn-success']) !!}