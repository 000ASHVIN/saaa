<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Name') !!}
    {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

@if (isset($pricing))
    <div class="form-group @if ($errors->has('venueList')) has-error @endif">
        {!! Form::label('venueList', 'Venue') !!}
        <select name="venueList" id="venueList" class="form-control">
            @foreach($event->AllVenues as $venue)
                <option {{ ($pricing->venueList == $venue->id) ? "selected" : "" }} value="{{ $venue->id}} ">{{ ucfirst($venue->name).' - '.ucfirst(($venue->city ? : "Webinar")) }}</option>
            @endforeach
        </select>
        @if ($errors->has('venueList')) <p class="help-block">{{ $errors->first('venueList') }}</p> @endif
    </div>
@else
    <div class="form-group @if ($errors->has('venueList')) has-error @endif">
        {!! Form::label('venueList', 'Venue') !!}
        <select name="venueList" id="venueList" class="form-control">
            @foreach($event->AllVenues as $venue)
                <option value="{{ $venue->id}} ">{{ ucfirst($venue->name).' - '.ucfirst(($venue->city ? : "Webinar")) }}</option>
            @endforeach
        </select>
        @if ($errors->has('venueList')) <p class="help-block">{{ $errors->first('venueList') }}</p> @endif
    </div>
@endif

<div class="form-group @if ($errors->has('description')) has-error @endif">
    {!! Form::label('description', 'Description') !!}
    {!! Form::input('text', 'description', null, ['class' => 'form-control']) !!}
    @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('day_count')) has-error @endif">
    {!! Form::label('day_count', 'How Many Days') !!}
    {!! Form::input('text', 'day_count', null, ['class' => 'form-control', 'placeholder' => '1']) !!}
    @if ($errors->has('day_count')) <p class="help-block">{{ $errors->first('day_count') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('price')) has-error @endif">
    {!! Form::label('price', 'Price') !!}
    {!! Form::input('text', 'price', null, ['class' => 'form-control', 'placeholder' => '1299']) !!}
    @if ($errors->has('price')) <p class="help-block">{{ $errors->first('price') }}</p> @endif
</div>

<div class="form-check">
    <input class="form-check-input has_subscription_discount" type="checkbox" name="has_subscription_discount" id="has_subscription_discount_{{$pricing ? $pricing->id : ''}}" {{$pricing && $pricing->subscription_discount ? 'checked' : ''}}>
    <label class="form-check-label" for="has_subscription_discount_{{$pricing ? $pricing->id : ''}}">
      Has Subscription Discount?
    </label>
</div>

<div class="form-group cpd-price @if ($errors->has('subscription_discount')) has-error @endif" style="{{$pricing && $pricing->subscription_discount ? '' : 'display: none;'}}">
    {!! Form::label('subscription_discount', 'CPD Subscription Discount') !!}
    {!! Form::input('text', 'subscription_discount', null, ['class' => 'form-control', 'placeholder' => '0']) !!}
    @if ($errors->has('subscription_discount')) <p class="help-block">{{ $errors->first('subscription_discount') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('is_active')) has-error @endif">
    {!! Form::label('is_active', 'Status') !!}
    {!! Form::select('is_active', [
        true => 'Active',
        false => 'Not Active'
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('is_active')) <p class="help-block">{{ $errors->first('is_active') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('cpd_hours')) has-error @endif">
    {!! Form::label('cpd_hours', 'CPD Hours') !!}
    <span class="pull-right" style="font-style: italic">Enter the number of hours</span>
    {!! Form::input('text', 'cpd_hours', null, ['class' => 'form-control', 'placeholder' => '8']) !!}
    @if ($errors->has('cpd_hours')) <p class="help-block">{{ $errors->first('cpd_hours') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('cpd_verifiable')) has-error @endif">
    {!! Form::label('cpd_verifiable', 'CPD Verifiable') !!}
    {!! Form::select('cpd_verifiable', [
        true => 'Yes',
        false => 'No'
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('cpd_verifiable')) <p class="help-block">{{ $errors->first('cpd_verifiable') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('attendance_certificate')) has-error @endif">
    {!! Form::label('attendance_certificate', 'Attendance Certificate') !!}
    {!! Form::select('attendance_certificate', [
        true => 'Yes',
        false => 'No'
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('attendance_certificate')) <p class="help-block">{{ $errors->first('attendance_certificate') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('can_manually_claim_cpd')) has-error @endif">
    {!! Form::label('can_manually_claim_cpd', 'Can Claim CPD Manually') !!}
    {!! Form::select('can_manually_claim_cpd', [
        null => 'Please select..',
        true => 'Yes',
        false => 'No'
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('can_manually_claim_cpd')) <p class="help-block">{{ $errors->first('can_manually_claim_cpd') }}</p> @endif
</div>

<hr>
{!! Form::submit($submit, ['class' => 'btn btn-info']) !!}
<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>