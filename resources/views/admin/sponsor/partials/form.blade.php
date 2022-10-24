<div id="sponsor" class="tab-pane fade in active">
<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('name')) has-error @endif">
            {!! Form::label('name', 'Name') !!}
            {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
            @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group @if ($errors->has('title')) has-error @endif">
            {!! Form::label('title', 'Title') !!}
            {!! Form::input('text', 'title', null, ['class' => 'form-control']) !!}
            @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
        </div>
    </div> 
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('email_id')) has-error @endif">
            {!! Form::label('email_id', 'Email Id') !!}
            {!! Form::input('text', 'email_id', null, ['class' => 'form-control']) !!}
            @if ($errors->has('email_id')) <p class="help-block">{{ $errors->first('email_id') }}</p> @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group @if ($errors->has('is_active')) has-error @endif">
            {!! Form::label('is_active', 'Status') !!}
            {!! Form::select('is_active', [
                0 => 'No',
                1 => 'Yes'
            ],null, ['class' => 'form-control']) !!}
            @if ($errors->has('is_active')) <p class="help-block">{{ $errors->first('is_active') }}</p> @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group @if ($errors->has('is_premium_partner')) has-error @endif">
            {!! Form::label('is_premium_partner', 'Premiunm Partner') !!}
            {!! Form::select('is_premium_partner', [
                0 => 'No',
                1 => 'Yes'
            ],null, ['class' => 'form-control']) !!}
            @if ($errors->has('is_premium_partner')) <p class="help-block">{{ $errors->first('is_premium_partner') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
<div class="col-md-12">
    <div class="form-group @if ($errors->has('content')) has-error @endif">
        {!! Form::label('content', 'Content') !!}
        {!! Form::textarea('content', null, ['class' => 'ckeditorEmail form-control ']) !!}
        @if ($errors->has('content')) <p class="help-block">{{ $errors->first('content') }}</p> @endif
    </div>
</div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group @if ($errors->has('short_description')) has-error @endif">
            {!! Form::label('short_description', 'Short Description') !!}
            {!! Form::textarea('short_description', null, ['class' => 'form-control ckeditorEmail']) !!}
            @if ($errors->has('short_description')) <p class="help-block">{{ $errors->first('short_description') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group @if ($errors->has('logo')) has-error @endif">
            {!! Form::label('logo', 'logo') !!}
            {!! Form::file('logo', null, ['class' => 'form-control']) !!}
            @if ($errors->has('logo')) <p class="help-block">{{ $errors->first('logo') }}</p> @endif
        </div>  
    </div> 
</div> 

<button class="btn btn-primary"><i class="fa fa-check"></i> {!! $button !!}</button>
</div>

