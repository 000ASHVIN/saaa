<div class="form-group @if ($errors->has('selected_features[]')) has-error @endif">
    {!! Form::label('selected_features[]', 'Please select your features') !!}
    {!! Form::select('selected_features[]', $features->pluck('name', 'id')->toArray(), $pricing->features->pluck('id')->toArray(), ['class' => 'select2', 'multiple' => true, 'style' => 'width:100%']) !!}
    @if ($errors->has('selected_features[]')) <p class="help-block">{{ $errors->first('selected_features[]') }}</p> @endif
</div>

<hr>
{!! Form::submit($submit, ['class' => 'btn btn-info']) !!}
<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>