<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('title')) has-error @endif">
            {!! Form::label('title', 'Webinar Title') !!}
            {!! Form::input('text', 'title', null, ['class' => 'form-control']) !!}
            @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
        </div>

        <div class="form-group @if ($errors->has('webinar_url')) has-error @endif">
            {!! Form::label('webinar_url', 'Clickmeeting webinar url') !!}
            {!! Form::input('text', 'webinar_url', null, ['class' => 'form-control']) !!}
            @if ($errors->has('webinar_url')) <p class="help-block">{{ $errors->first('webinar_url') }}</p> @endif
        </div>

        <div class="form-group @if ($errors->has('date')) has-error @endif">
            {!! Form::label('date', 'Webinar Date') !!}
            {!! Form::input('text', 'date', null, ['class' => 'form-control is-date']) !!}
            @if ($errors->has('date')) <p class="help-block">{{ $errors->first('date') }}</p> @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('video_url')) has-error @endif">
            {!! Form::label('video_url', 'Vimeo video url') !!}
            {!! Form::input('text', 'video_url', null, ['class' => 'form-control']) !!}
            @if ($errors->has('video_url')) <p class="help-block">{{ $errors->first('video_url') }}</p> @endif
        </div>

        <div class="form-group @if ($errors->has('webinar_password')) has-error @endif">
            {!! Form::label('webinar_password', 'Clickmeeting webinar password') !!}
            {!! Form::input('text', 'webinar_password', null, ['class' => 'form-control']) !!}
            @if ($errors->has('webinar_password')) <p class="help-block">{{ $errors->first('webinar_password') }}</p> @endif
        </div>

        <div class="form-group @if ($errors->has('time')) has-error @endif">
            {!! Form::label('time', 'Webinar Time') !!}
            {!! Form::input('text', 'time', null, ['class' => 'form-control timepicker']) !!}
            @if ($errors->has('time')) <p class="help-block">{{ $errors->first('time') }}</p> @endif
        </div>
    </div>

    <div class="col-md-12">

        <div class="form-group @if ($errors->has('event')) has-error @endif">
            {!! Form::label('event', 'Please select your event') !!}
            {!! Form::select('event[]', $events, null, ['class' => 'select2 form-control']) !!}
            @if ($errors->has('event')) <p class="help-block">{{ $errors->first('event') }}</p> @endif
        </div>

        <div class="form-group @if ($errors->has('description')) has-error @endif">
            {!! Form::label('description', 'Description') !!}
            {!! Form::textarea('description', null, ['class' => 'form-control ckeditor']) !!}
            @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
        </div>

        <a href="{{ route('admin.live_webinars.index') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</a>
        {!! Form::submit($button, ['class' => 'btn btn-primary']) !!}

    </div>
</div>