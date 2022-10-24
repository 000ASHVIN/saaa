<div class="form-group @if ($errors->has('assessment')) has-error @endif">
    {!! Form::label('assessment', 'Please select your assessments') !!}
    {!! Form::select('assessment[]', $assessments->pluck('title', 'id'), null, ['class' => 'form-control', 'multiple' => 'true']) !!}
    @if ($errors->has('assessment')) <p class="help-block">{{ $errors->first('assessment') }}</p> @endif
</div>

<div class="pull-right">
    <button data-dismiss="modal" class="btn btn-default">Close</button>
    {!! Form::submit('Save Assessment', ['class' => 'btn btn-primary']) !!}
</div>