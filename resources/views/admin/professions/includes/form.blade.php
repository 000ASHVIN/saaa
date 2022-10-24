<div class="form-group @if ($errors->has('title')) has-error @endif">
    {!! Form::label('title', 'Title') !!}
    {!! Form::input('text', 'title', null, ['class' => 'form-control']) !!}
    @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('is_active')) has-error @endif">
    {!! Form::label('is_active', 'Active') !!}
    {!! Form::select('is_active', [
        true => 'Active',
        false => 'Not Active'
    ], null, ['class' => 'form-control']) !!}
    @if ($errors->has('is_active')) <p class="help-block">{{ $errors->first('is_active') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('body_list')) has-error @endif">
    {!! Form::label('body_list', 'Please select your plan') !!}
    {!! Form::select('body_list[]', $bodies, null, ['class' => 'select2 form-control', 'multiple' => true]) !!}
    @if ($errors->has('body_list')) <p class="help-block">{{ $errors->first('body_list') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('features')) has-error @endif">
    {!! Form::label('features', 'Feature Content') !!}
    {!! Form::textarea('features', null, ['class' => 'form-control ckeditor']) !!}
    @if ($errors->has('features')) <p class="help-block">{{ $errors->first('features') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('description')) has-error @endif">
    {!! Form::label('description', 'CPD Content') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control ckeditor']) !!}
    @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('resource_center')) has-error @endif">
    {!! Form::label('resource_center', 'Resource Center Content') !!}
    {!! Form::textarea('resource_center', null, ['class' => 'form-control ckeditor']) !!}
    @if ($errors->has('resource_center')) <p class="help-block">{{ $errors->first('resource_center') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('view_resource_center')) has-error @endif">
    {!! Form::label('view_resource_center', 'Resource Center Access') !!}
    {!! Form::select('view_resource_center', [
        true => 'Yes',
        false => 'No'
    ], null, ['class' => 'form-control']) !!}
    @if ($errors->has('view_resource_center')) <p class="help-block">{{ $errors->first('view_resource_center') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('sponsor_list')) has-error @endif">
    {!! Form::label('sponsor_list', 'Please select sponsors') !!}
    {!! Form::select('sponsor_list[]', $sponsors, null, ['class' => 'select2 form-control', 'multiple' => true]) !!}
    @if ($errors->has('sponsor_list')) <p class="help-block">{{ $errors->first('sponsor_list') }}</p> @endif
</div>

   
{!! Form::submit($button, ['class' => 'btn btn-primary']) !!}