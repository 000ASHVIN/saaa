<div class="form-group">
    {!! Form::label('name', 'Tab name') !!}
    {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group @if ($errors->has('features')) has-error @endif">
    {!! Form::label('features', 'Feature Link') !!}
    {!! Form::select('features[]', $features,  (isset($practice_plan_tab))?$practice_plan_tab->features->pluck('id')->toArray():'', ['class' => 'select2 form-control', 'multiple' => true]) !!}
    @if ($errors->has('features')) <p class="help-block">{{ $errors->first('features') }}</p> @endif
</div>
<div class="form-group">
    {!! Form::label('sequence', 'Tab Sequence') !!}
    {!! Form::input('number', 'sequence', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::submit($SubmitButton, ['class' => 'btn btn-info']) !!}
    <a href="{!! route('admin.plans.features.index') !!}#practice_plan" class="btn btn-default">Back</a>
</div>