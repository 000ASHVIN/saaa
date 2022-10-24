<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Name') !!}
    {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('price')) has-error @endif">
    {!! Form::label('price', 'Price') !!}
    {!! Form::input('text', 'price', null, ['class' => 'form-control']) !!}
    @if ($errors->has('price')) <p class="help-block">{{ $errors->first('price') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('min_user')) has-error @endif">
    {!! Form::label('min_user', 'Minimum User') !!}
    {!! Form::input('text', 'min_user', null, ['class' => 'form-control']) !!}
    @if ($errors->has('min_user')) <p class="help-block">{{ $errors->first('min_user') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('max_user')) has-error @endif">
    {!! Form::label('max_user', 'Maximum User') !!}
    {!! Form::input('text', 'max_user', null, ['class' => 'form-control']) !!}
    @if ($errors->has('max_user')) <p class="help-block">{{ $errors->first('max_user') }}</p> @endif
</div>


<div class="form-group @if ($errors->has('billing')) has-error @endif">
        <small class="pull-right" style="font-style: italic">billings will not be displayed on the website.</small>
        {!! Form::label('billing', 'Billing') !!}
        {!! Form::select('billing', [
            'month' => 'Monthly',
            'year' => 'Yearly'
        ],null, ['class' => 'form-control']) !!}
        @if ($errors->has('billing')) <p class="help-block">{{ $errors->first('billing') }}</p> @endif
    </div>

    

<div class="form-group @if ($errors->has('is_active')) has-error @endif">
    <small class="pull-right" style="font-style: italic">is_actives will not be displayed on the website.</small>
    {!! Form::label('is_active', 'Is this a active ?') !!}
    {!! Form::select('is_active', [
        1 => 'Yes',
        0 => 'No'
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('is_active')) <p class="help-block">{{ $errors->first('is_active') }}</p> @endif
</div>

<button type="submit" class="btn btn-primary">{{ $submit }}</button>