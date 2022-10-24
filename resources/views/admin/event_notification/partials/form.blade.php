<div class="form-group @if ($errors->has('event')) has-error @endif">
    {!! Form::label('event', 'Event') !!}
    {!! Form::select('event', $events->pluck('name','id'), old('event')?old('event'):request()->event, ['class' => 'form-control select2', 'placeholder' => 'Select event' ]) !!}
    @if ($errors->has('event')) <p class="help-block">{{ $errors->first('event') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('schedule_date')) has-error @endif">
    {!! Form::label('schedule_date', 'Schedule Date') !!}
    {!! Form::input('text', 'schedule_date', old('schedule_date')?old('schedule_date'):Carbon\Carbon::now()->addDays(1)->format('Y-m-d'), ['class' => 'form-control is-date']) !!}
    @if ($errors->has('schedule_date')) <p class="help-block">{{ $errors->first('schedule_date') }}</p> @endif
</div>

{!! Form::submit($button, ['class' => 'btn btn-success']) !!}