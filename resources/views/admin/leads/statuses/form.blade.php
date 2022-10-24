<div class="col-md-6">
    <div class="form-group @if ($errors->has('name')) has-error @endif">
        {!! Form::label('name', 'Name') !!}
        {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>
    
    {!! Form::submit($button, ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.leads.status.index') }}" class="btn btn-info">Back</a>
</div>