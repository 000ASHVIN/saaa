
<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('title')) has-error @endif">
            {!! Form::label('title', 'Industry') !!}
            {!! Form::input('text', 'title', null, ['class' => 'form-control']) !!}
            @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
        </div>
    </div>
</div>

<button class="btn btn-primary" onclick="spin(this)"><i class="fa fa-check"></i> Submit Form</button>

