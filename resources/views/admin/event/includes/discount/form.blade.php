<div class="form-group @if ($errors->has('title')) has-error @endif">
    {!! Form::label('title', 'Title') !!}
    {!! Form::input('text', 'title', null, ['class' => 'form-control']) !!}
    @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('description')) has-error @endif">
    {!! Form::label('description', 'Description') !!}
    {!! Form::input('text', 'description', null, ['class' => 'form-control']) !!}
    @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('code')) has-error @endif">
    {!! Form::label('code', 'Code') !!}
    {!! Form::input('text', 'code', null, ['class' => 'form-control']) !!}
    @if ($errors->has('code')) <p class="help-block">{{ $errors->first('code') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('discount_type')) has-error @endif">
    {!! Form::label('discount_type', 'Discount Type') !!}
    {!! Form::select('discount_type', [
        'percentage' => 'Percentage',
        'amount' => 'Amount',
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('discount_type')) <p class="help-block">{{ $errors->first('discount_type') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('discount_amount')) has-error @endif">
    {!! Form::label('discount_amount', 'Discount Amount') !!}
    {!! Form::input('text', 'discount_amount', null, ['class' => 'form-control']) !!}
    @if ($errors->has('discount_amount')) <p class="help-block">{{ $errors->first('discount_amount') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('has_limited_uses')) has-error @endif">
    {!! Form::label('has_limited_uses', 'Is Limited') !!}
    {!! Form::select('has_limited_uses', [
        true => 'Yes',
        false => 'No'
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('has_limited_uses')) <p class="help-block">{{ $errors->first('has_limited_uses') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('remaining_uses')) has-error @endif">
    {!! Form::label('remaining_uses', 'Remaining Uses') !!}
    <span class="pull-right"><i>0 for unlimited</i></span>
    {!! Form::input('text', 'remaining_uses', null, ['class' => 'form-control']) !!}
    @if ($errors->has('remaining_uses')) <p class="help-block">{{ $errors->first('remaining_uses') }}</p> @endif
</div>

<hr>
{!! Form::submit($submit, ['class' => 'btn btn-info']) !!}
<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
