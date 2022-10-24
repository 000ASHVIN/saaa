<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Name') !!}
    {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('is_active')) has-error @endif">
    {!! Form::label('is_active', 'Status') !!}
    {!! Form::select('is_active',
    [
        true => 'Active',
        false => 'Not Active',
    ],
    null, ['class' => 'form-control']) !!}
    @if ($errors->has('is_active')) <p class="help-block">{{ $errors->first('is_active') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('price')) has-error @endif">
    {!! Form::label('price', 'Price') !!}
    {!! Form::input('text', 'price', null, ['class' => 'form-control']) !!}
    @if ($errors->has('price')) <p class="help-block">{{ $errors->first('price') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('cpd_hours')) has-error @endif">
    {!! Form::label('cpd_hours', 'CPD Hours') !!}
    {!! Form::input('number', 'cpd_hours', null, ['class' => 'form-control']) !!}
    @if ($errors->has('cpd_hours')) <p class="help-block">{{ $errors->first('cpd_hours') }}</p> @endif
</div>

<hr>
{!! Form::submit($submit, ['class' => 'btn btn-info']) !!}
<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>