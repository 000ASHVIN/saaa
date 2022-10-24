<p>Please note that this will create a new date for the selected venue, In order to access this date click on the dates tab</p>
<hr>

<p style="font-weight: bold">
    Venue: {!! $venue->name !!} <br>
    Address Line One: {!! $venue->address_line_one !!} <br>
    Address Line Two: {!! $venue->address_line_two !!} <br>
    City: {!! $venue->city !!} <br>
    Province: {!! $venue->province !!} <br>
    Country: {!! $venue->country !!} <br>
    Area Code: {!! $venue->area_code !!} <br>
</p>

<hr>

<div class="form-group @if ($errors->has('is_active')) has-error @endif">
    {!! Form::label('is_active', 'Date Status') !!}
    {!! Form::select('is_active', [
        true => 'Active',
        false => 'Not Active',
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('is_active')) <p class="help-block">{{ $errors->first('is_active') }}</p> @endif
</div>

@if(isset($date))
<div class="form-group @if ($errors->has('date')) has-error @endif">
    {!! Form::label('date', 'New Date') !!}
    {!! Form::input('text', 'date', \Carbon\Carbon::parse($date->date), ['class' => 'is-date form-control']) !!}
    @if ($errors->has('date')) <p class="help-block">{{ $errors->first('date') }}</p> @endif
</div>
@else
    <div class="form-group @if ($errors->has('date')) has-error @endif">
    {!! Form::label('date', 'New Date') !!}
    {!! Form::input('text', 'date', null, ['class' => 'is-date form-control']) !!}
    @if ($errors->has('date')) <p class="help-block">{{ $errors->first('date') }}</p> @endif
    </div>
@endif

<hr>

{!! Form::submit($submit, ['class' => 'btn btn-success']) !!}
<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>

