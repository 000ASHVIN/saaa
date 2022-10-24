<div class="col-md-6">
    <div class="form-group @if ($errors->has('title')) has-error @endif">
        {!! Form::label('title', 'Title') !!}
        {!! Form::input('text', 'title', null, ['class' => 'form-control']) !!}
        @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
    </div>
</div>

<div class="col-md-6">
    <div class="form-group @if ($errors->has('email')) has-error @endif">
        <small class="pull-right"><i><strong>Verification Email Address</strong></i></small>
        {!! Form::label('email', 'Email Address') !!}
        {!! Form::input('text', 'email', null, ['class' => 'form-control']) !!}
        @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
    </div>
</div>

<div class="col-md-6">
    <div class="form-group @if ($errors->has('contact_number')) has-error @endif">
        {!! Form::label('contact_number', 'Contact Number') !!}
        {!! Form::input('text', 'contact_number', null, ['class' => 'form-control']) !!}
        @if ($errors->has('contact_number')) <p class="help-block">{{ $errors->first('contact_number') }}</p> @endif
    </div>
</div>

<div class="col-md-6">
    <div class="form-group @if ($errors->has('logo')) has-error @endif">
        {!! Form::label('logo', 'Logo') !!}
        {!! Form::file('logo', null, ['class' => 'form-control']) !!}
        @if ($errors->has('logo')) <p class="help-block">{{ $errors->first('logo') }}</p> @endif
    </div>
</div>



<div class="col-md-12">
    <div class="form-group @if ($errors->has('plan_list')) has-error @endif">
        {!! Form::label('plan_list', 'Please select your plan') !!}
        {!! Form::select('plan_list[]', $plans, null, ['class' => 'select2 form-control', 'multiple' => true]) !!}
        @if ($errors->has('plan_list')) <p class="help-block">{{ $errors->first('plan_list') }}</p> @endif
    </div>
</div>

<div class="col-md-6">
    <div class="form-group @if ($errors->has('seminar')) has-error @endif">
        {!! Form::label('seminar', 'Seminar Price') !!}
        {!! Form::input('text', 'seminar', null, ['class' => 'form-control']) !!}
        @if ($errors->has('seminar')) <p class="help-block">{{ $errors->first('seminar') }}</p> @endif
    </div>
</div>

<div class="col-md-6">
    <div class="form-group @if ($errors->has('webinar')) has-error @endif">
        {!! Form::label('webinar', 'Webinar Price') !!}
        {!! Form::input('text', 'webinar', null, ['class' => 'form-control']) !!}
        @if ($errors->has('webinar')) <p class="help-block">{{ $errors->first('webinar') }}</p> @endif
    </div>
</div>

<div class="col-md-12">
    <div class="form-group @if ($errors->has('address')) has-error @endif">
        {!! Form::label('address', 'Address') !!}
        {!! Form::textarea('address', null, ['class' => 'form-control', 'rows' =>'5']) !!}
        @if ($errors->has('address')) <p class="help-block">{{ $errors->first('address') }}</p> @endif
    </div>
</div>

<div class="col-md-12">
    <div class="form-group @if ($errors->has('description')) has-error @endif">
        {!! Form::label('description', 'Description') !!}
        {!! Form::textarea('description', null, ['class' => 'ckeditor form-control']) !!}
        @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
    </div>
</div>

<div class="col-md-12">
    {!! Form::submit($button, ['class' => 'btn btn-success']) !!}
</div>