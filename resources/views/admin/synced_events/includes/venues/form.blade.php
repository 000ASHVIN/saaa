<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Name') !!}
    {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('is_active')) has-error @endif">
    {!! Form::label('is_active', 'Status') !!}
    {!! Form::select('is_active', [
        true => 'Active',
        false => 'Not Active'
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('is_active')) <p class="help-block">{{ $errors->first('is_active') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('type')) has-error @endif">
    {!! Form::label('type', 'Type') !!}
    {!! Form::select('type', [
        'face-to-face' => 'Face to Face',
        'online' => 'Online',
        'conference' => 'Conference',
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('type')) <p class="help-block">{{ $errors->first('type') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('max_attendees')) has-error @endif">
    {!! Form::label('max_attendees', 'Max Attendees') !!}
    {!! Form::input('text', 'max_attendees', null, ['class' => 'form-control', 'placeholder' => 'Leave blank for unlimited']) !!}
    @if ($errors->has('max_attendees')) <p class="help-block">{{ $errors->first('max_attendees') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('start_time')) has-error @endif">
    {!! Form::label('start_time', 'Start Time') !!}
    {!! Form::input('text', 'start_time', (isset($venue)? date('H:i:s',strtotime($venue->start_time)):null), ['class' => 'form-control timepicker']) !!}
    @if ($errors->has('start_time')) <p class="help-block">{{ $errors->first('start_time') }}</p> @endif
</div>


<div class="form-group @if ($errors->has('end_time')) has-error @endif">
        {!! Form::label('end_time', 'End Time') !!}
        {!! Form::input('text', 'end_time',  (isset($venue)? date('H:i:s',strtotime($venue->end_time)):null), ['class' => 'form-control timepicker']) !!}
        @if ($errors->has('end_time')) <p class="help-block">{{ $errors->first('end_time') }}</p> @endif
</div>
<div class="form-group @if ($errors->has('address_line_one')) has-error @endif">
    {!! Form::label('address_line_one', 'Address line one') !!}
    {!! Form::input('text', 'address_line_one', null, ['class' => 'form-control']) !!}
    @if ($errors->has('address_line_one')) <p class="help-block">{{ $errors->first('address_line_one') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('address_line_two')) has-error @endif">
    {!! Form::label('address_line_two', 'Address line two') !!}
    {!! Form::input('text', 'address_line_two', null, ['class' => 'form-control']) !!}
    @if ($errors->has('address_line_two')) <p class="help-block">{{ $errors->first('address_line_two') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('city')) has-error @endif">
    {!! Form::label('city', 'City') !!}
    {!! Form::input('text', 'city', null, ['class' => 'form-control']) !!}
    @if ($errors->has('city')) <p class="help-block">{{ $errors->first('city') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('province')) has-error @endif">
    {!! Form::label('province', 'Province') !!}
    {!! Form::input('text', 'province', null, ['class' => 'form-control']) !!}
    @if ($errors->has('province')) <p class="help-block">{{ $errors->first('province') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('country')) has-error @endif">
    {!! Form::label('country', 'Country') !!}
    {!! Form::input('text', 'country', null, ['class' => 'form-control']) !!}
    @if ($errors->has('country')) <p class="help-block">{{ $errors->first('country') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('area_code')) has-error @endif">
    {!! Form::label('area_code', 'Area code') !!}
    {!! Form::input('text', 'area_code', null, ['class' => 'form-control']) !!}
    @if ($errors->has('area_code')) <p class="help-block">{{ $errors->first('area_code') }}</p> @endif
</div>

<hr>
{!! Form::submit($submit, ['class' => 'btn btn-info']) !!}
<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
