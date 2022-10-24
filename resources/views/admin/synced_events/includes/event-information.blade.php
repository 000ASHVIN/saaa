<div class="col-md-12">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group @if ($errors->has('name')) has-error @endif">
                {!! Form::label('name', 'Name') !!}
                {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
                @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
            </div>

            <div class="form-group @if ($errors->has('subscription_event')) has-error @endif">
                {!! Form::label('subscription_event', 'CPD Subscription Event') !!}
                {!! Form::select('subscription_event', [
                    true => 'Yes',
                    false => 'No',
                ],null, ['class' => 'form-control']) !!}
                @if ($errors->has('subscription_event')) <p class="help-block">{{ $errors->first('subscription_event') }}</p> @endif
            </div>

            <div class="form-group @if ($errors->has('is_open_to_public')) has-error @endif">
                {!! Form::label('is_open_to_public', 'Should this event be visible to the public ?') !!}
                {!! Form::select('is_open_to_public', [
                    true => 'Yes',
                    false => 'No'
                ],null, ['class' => 'form-control']) !!}
                @if ($errors->has('is_open_to_public')) <p class="help-block">{{ $errors->first('is_open_to_public') }}</p> @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group @if ($errors->has('is_active')) has-error @endif">
                {!! Form::label('is_active', 'Status') !!}
                {!! Form::select('is_active', [
                    true => 'Active',
                    false => 'Not Active',
                ],null, ['class' => 'form-control']) !!}
                @if ($errors->has('is_active')) <p class="help-block">{{ $errors->first('is_active') }}</p> @endif
            </div>

            <div class="form-group @if ($errors->has('type')) has-error @endif">
                {!! Form::label('type', 'Seminar / Webinar / Conference') !!}
                {!! Form::select('type', [
                     'seminar' => 'Seminar',
                     'webinar' => 'Webinar',
                     'conference' => 'Conference',
                 ], null, ['class' => 'form-control']) !!}
                @if ($errors->has('type')) <p class="help-block">{{ $errors->first('type') }}</p> @endif
            </div>

            <div class="form-group @if ($errors->has('category')) has-error @endif">
                {!! Form::label('category', 'Type') !!}
                {!! Form::select('category', [
                    'all_cpd_subs' => 'Everyone (This event is open to anyone for registrations)',
                    'free' => 'Free Event (This is a free event)',
                ],null, ['class' => 'form-control']) !!}
                @if ($errors->has('category')) <p class="help-block">{{ $errors->first('category') }}</p> @endif
            </div>

        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="form-group @if ($errors->has('tags')) has-error @endif">
                {!! Form::label('tags', 'Categories') !!}
                {!! Form::select('tags[]', $categories, null, ['class' => 'select2 form-control', 'multiple' => true]) !!}
                @if ($errors->has('tags')) <p class="help-block">{{ $errors->first('tags') }}</p> @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group @if ($errors->has('background_url')) has-error @endif">
                {!! Form::label('background_url', 'Top background cover URL') !!}
                <span class="pull-right"><strong><small><i>1903 X 566</i></small></strong></span>
                {!! Form::input('text', 'background_url', null, ['class' => 'form-control']) !!}
                @if ($errors->has('background_url')) <p class="help-block">{{ $errors->first('background_url') }}</p> @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group @if ($errors->has('presenter')) has-error @endif">
                {!! Form::label('presenter', 'Please select your presenter') !!}
                {!! Form::select('presenter[]', $presenters, null, ['class' => 'select2 form-control', 'multiple' => true]) !!}
                @if ($errors->has('presenter')) <p class="help-block">{{ $errors->first('presenter') }}</p> @endif
            </div>
        </div>
    </div>
</div>
<div class="col-md-12"><hr></div>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group @if ($errors->has('start_date')) has-error @endif">
                {!! Form::label('start_date', 'Start Date') !!}
                {!! Form::input('text', 'start_date', null, ['class' => 'is-date form-control']) !!}
                @if ($errors->has('start_date')) <p class="help-block">{{ $errors->first('start_date') }}</p> @endif
            </div>

            <div class="form-group @if ($errors->has('end_date')) has-error @endif">
                {!! Form::label('end_date', 'End Date') !!}
                {!! Form::input('text', 'end_date', null, ['class' => 'is-date form-control']) !!}
                @if ($errors->has('end_date')) <p class="help-block">{{ $errors->first('end_date') }}</p> @endif
            </div>

            <div class="form-group @if ($errors->has('end_time')) has-error @endif">
                {!! Form::label('end_time', 'End Time') !!}
                {!! Form::input('text', 'end_time', null, ['class' => 'form-control timepicker']) !!}
                @if ($errors->has('end_time')) <p class="help-block">{{ $errors->first('end_time') }}</p> @endif
            </div>


        </div>
        <div class="col-md-6">
            <div class="form-group @if ($errors->has('next_date')) has-error @endif">
                {!! Form::label('next_date', 'Next Date') !!}
                {!! Form::input('text', 'next_date', null, ['class' => 'is-date form-control']) !!}
                @if ($errors->has('next_date')) <p class="help-block">{{ $errors->first('next_date') }}</p> @endif
            </div>

            <div class="form-group @if ($errors->has('start_time')) has-error @endif">
                {!! Form::label('start_time', 'Start Time') !!}
                {!! Form::input('text', 'start_time', null, ['class' => 'form-control timepicker']) !!}
                @if ($errors->has('start_time')) <p class="help-block">{{ $errors->first('start_time') }}</p> @endif
            </div>

            <div class="form-group @if ($errors->has('featured_image')) has-error @endif">
                {!! Form::label('featured_image', 'Featured Image') !!}
                {!! Form::select('featured_image', [
                    '' => 'Please Select',
                    'http://imageshack.com/a/img922/8194/PvOjE0.jpg' => 'Webinar | Seminar',
                    'http://imageshack.com/a/img922/7981/Nd3fK0.jpg' => 'Webinar',
                    'http://imageshack.com/a/img922/8194/PvOjE0.jpg' => 'Seminar'
                ],null, ['class' => 'form-control']) !!}
                @if ($errors->has('featured_image')) <p class="help-block">{{ $errors->first('featured_image') }}</p> @endif
            </div>
        </div>
    </div>
</div>
<div class="col-md-12"><hr></div>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group @if ($errors->has('is_redirect')) has-error @endif">
                {!! Form::label('is_redirect', 'Is Redirect') !!}
                <span class="pull-right" style="font-style: italic"><strong>Only if event is not hosted with us.</strong></span>
                {!! Form::select('is_redirect', [
                   null => 'Please Select',
                   true => 'Yes',
                   false => 'No'
               ],null, ['class' => 'form-control']) !!}
                @if ($errors->has('is_redirect')) <p class="help-block">{{ $errors->first('is_redirect') }}</p> @endif
            </div>


        </div>
        <div class="col-md-6">
            <div class="form-group @if ($errors->has('redirect_url')) has-error @endif">
                {!! Form::label('redirect_url', 'Redirect Url') !!}
                <span class="pull-right" style="font-style: italic"><strong>Only if event is not hosted with us</strong></span>
                {!! Form::input('text', 'redirect_url', null, ['class' => 'form-control', 'placeholder' => 'http://www.thesait.org.za/']) !!}
                @if ($errors->has('redirect_url')) <p class="help-block">{{ $errors->first('redirect_url') }}</p> @endif
            </div>

        </div>
    </div>
</div>

<div class="col-md-12"><hr></div>
<div class="col-md-12">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group @if ($errors->has('video_title')) has-error @endif">
                {!! Form::label('video_title', 'Heading for Video') !!}
                <span class="pull-right" style="font-style: italic"><strong>Display previous video title</strong></span>
                {!! Form::input('text', 'video_title', null, ['class' => 'form-control']) !!}
                @if ($errors->has('video_title')) <p class="help-block">{{ $errors->first('video_title') }}</p> @endif
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group @if ($errors->has('video_url')) has-error @endif">
                {!! Form::label('video_url', 'Video URL') !!}
                <span class="pull-right" style="font-style: italic"><strong>Render Previous video URL</strong></span>
                {!! Form::input('text', 'video_url', null, ['class' => 'form-control']) !!}
                @if ($errors->has('video_url')) <p class="help-block">{{ $errors->first('video_url') }}</p> @endif
            </div>
        </div>
    </div>
</div>
<div class="col-md-12"><hr></div>
<div class="col-md-12">

    <div class="form-group @if ($errors->has('registration_instructions')) has-error @endif">
        {!! Form::label('registration_instructions', 'Registration Instructions') !!}
        {!! Form::textarea('registration_instructions', null, ['class' => 'description form-control', 'rows' => '6']) !!}
        @if ($errors->has('registration_instructions')) <p class="help-block">{{ $errors->first('registration_instructions') }}</p> @endif
    </div>

    <div class="form-group @if ($errors->has('short_description')) has-error @endif">
        {!! Form::label('short_description', 'Short Description') !!}
        {!! Form::textarea('short_description', null, ['class' => 'description form-control', 'rows' => '6']) !!}
        @if ($errors->has('short_description')) <p class="help-block">{{ $errors->first('short_description') }}</p> @endif
    </div>

    <div class="form-group @if ($errors->has('description')) has-error @endif">
        {!! Form::label('description', 'Description') !!}
        {!! Form::textarea('description', null, ['class' => 'form-control ckeditor']) !!}
        @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
    </div>
</div>

<div class="col-md-12">
    <div class="form-group">
        {{-- {!! Form::submit($submit, ['class' => 'btn btn-success', 'id' => 'create-event']) !!} --}}
    </div>
</div>