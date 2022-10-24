<div class="form-group @if ($errors->has('designation_id')) has-error @endif">
    {!! Form::label('designation_id', 'Please select on of the '.ucfirst($user->body->title).' '.'designations') !!}
    {!! Form::select('designation_id', $user->body->designations->pluck('title', 'id'), null, ['class' => 'form-control']) !!}
    @if ($errors->has('designation_id')) <p class="help-block">{{ $errors->first('designation_id') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('start_date')) has-error @endif">
    {!! Form::label('start_date', 'Start Date') !!}
    {!! Form::input('text', 'start_date', null, ['class' => 'form-control is-date']) !!}
    @if ($errors->has('start_date')) <p class="help-block">{{ $errors->first('start_date') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('end_date')) has-error @endif">
    {!! Form::label('end_date', 'End Date') !!}
    {!! Form::input('text', 'end_date', null, ['class' => 'form-control is-date']) !!}
    @if ($errors->has('end_date')) <p class="help-block">{{ $errors->first('end_date') }}</p> @endif
</div>

{!! Form::submit($submit, ['class' => 'btn btn-primary']) !!}