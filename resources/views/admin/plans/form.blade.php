<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('name', 'Plan name') !!}
            {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('is_practice', 'Practice License') !!}
            {!! Form::select('is_practice', [
                false => 'Not A Practice License',
                true => 'Practice License'
            ],null, ['class' => 'form-control','id' => 'is_practice']) !!}
        </div>

        <div class="form-group @if ($errors->has('enable_bf')) has-error @endif">
            {!! Form::label('enable_bf', 'Black Friday Discounts') !!}
            {!! Form::select('enable_bf', [
                '' => 'Please Select',
                true => 'Enable Black Friday',
                false => 'Disable Black Friday',
            ] ,null, ['class' => 'form-control']) !!}
            @if ($errors->has('enable_bf')) <p class="help-block">{{ $errors->first('enable_bf') }}</p> @endif
        </div>

        <div class="form-group">
            {!! Form::label('last_minute', 'Last Minute Package') !!}
            {!! Form::select('last_minute', [
                '' => 'Please Select..',
                true => 'Yes',
                false => 'No',
            ],null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('interval', 'Plan Interval') !!}
            {!! Form::select('interval', [
                'month' => 'Monthly',
                'year' => 'Yearly',
            ],null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('package_type', 'Package Type') !!}
            {!! Form::select('package_type', [
                '' => 'none',
                'individual' => 'individual',
                'business' => 'business',
                'trainee' => 'trainee'
            ],null, ['class' => 'form-control']) !!}
        </div>



    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('inactive', 'Status') !!}
            {!! Form::select('inactive', [
                true => 'Not Active',
                false => 'Active'
            ],null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('is_custom', 'Can select their own feautres?') !!}
            {!! Form::select('is_custom', [
                false => 'No',
                true => 'Yes'
            ],null, ['class' => 'form-control', 'id'=>'chk_custom_plan']) !!}
        </div>

        <div class="form-group" id="max_no_of_features" style="display: none;">
            {!! Form::label('max_no_of_features', 'No Of Features') !!}
            {!! Form::input('number', 'max_no_of_features', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('price', 'Plan price') !!}
            {!! Form::input('number', 'price', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('bf_price', 'Black Friday Price') !!}
            {!! Form::input('number', 'bf_price', null, ['class' => 'form-control','step'=>"any"]) !!}
        </div>

        <div class="form-group">
            {!! Form::label('interval_count', 'Interval count') !!}
            {!! Form::input('number', 'interval_count', null, ['class' => 'form-control']) !!}
        </div>

        <div class="form-group" id="pricing_group">
            {!! Form::label('pricing_group', 'Please select your pricing group') !!}
            {!! Form::select('pricing_group[]', $pricing_group,  (isset($plan))?@$plan->pricingGroup->pluck('id')->toArray():'', ['class' => 'form-control select2' ,'multiple' => 'true']) !!}
        </div>
    </div>


    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('description', 'Plan description') !!}
            {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3]) !!}
        </div>
    </div>
</div>









<div class="form-group">
    {!! Form::label('trial_period_days', 'Plan trail period days') !!}
    {!! Form::input('number', 'trial_period_days', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('PlanFeaturesList', 'Please select all features for this plan') !!}
    {!! Form::select('PlanFeaturesList[]', $features, null, ['class' => 'form-control select2', 'multiple' => 'true']) !!}
</div>

<div class="form-group">
    {!! Form::label('ProfessionList', 'Please select all professions for this plan') !!}
    {!! Form::select('ProfessionList[]', $professions, null, ['class' => 'form-control select2', 'multiple' => 'true']) !!}
</div>

<div class="form-group">
    {!! Form::submit($SubmitButton, ['class' => 'btn btn-info']) !!}
</div>