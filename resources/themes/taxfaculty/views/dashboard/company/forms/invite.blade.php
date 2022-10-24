<div class="col-md-6">
    <div class="form-group @if ($errors->has('first_name')) has-error @endif">
        {!! Form::label('first_name', 'First Name') !!}
        {!! Form::input('text', 'first_name', null, ['class' => 'form-control']) !!}
        @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
    </div>
</div>

<div class="col-md-6">
    <div class="form-group @if ($errors->has('last_name')) has-error @endif">
        {!! Form::label('last_name', 'Last Name') !!}
        {!! Form::input('text', 'last_name', null, ['class' => 'form-control']) !!}
        @if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
    </div>
</div>

<div class="col-md-6">
    <div class="form-group @if ($errors->has('cell')) has-error @endif">
        {!! Form::label('cell', 'Cellphone Number') !!}
        {!! Form::input('text', 'cell', null, ['class' => 'form-control']) !!}
        @if ($errors->has('cell')) <p class="help-block">{{ $errors->first('cell') }}</p> @endif
    </div>
</div>

<div class="col-md-6">
    <div class="form-group @if ($errors->has('alternative_cell')) has-error @endif">
        {!! Form::label('alternative_cell', 'Alternative Cellphone Number') !!}
        {!! Form::input('text', 'alternative_cell', null, ['class' => 'form-control']) !!}
        @if ($errors->has('alternative_cell')) <p class="help-block">{{ $errors->first('alternative_cell') }}</p> @endif
    </div>
</div>

<div class="col-md-6">
    <div class="form-group @if ($errors->has('email')) has-error @endif">
        {!! Form::label('email', 'Email Address') !!}
        {!! Form::input('text', 'email', null, ['class' => 'form-control']) !!}
        @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
    </div>
</div>

<div class="col-md-6">
    <div class="form-group @if ($errors->has('id_number')) has-error @endif">
        {!! Form::label('id_number', 'ID Number') !!}
        {!! Form::input('text', 'id_number', null, ['class' => 'form-control']) !!}
        @if ($errors->has('id_number')) <p class="help-block">{{ $errors->first('id_number') }}</p> @endif
    </div>
</div>

<div class="col-md-12">
    <hr>
    <a href="{{ route('dashboard.company.index') }}" class="btn btn-primary"><i class="fa fa-close"></i> Cancel Invite</a>
    <button type="submit" class="btn btn-success" onclick="spin(this)"><i class="fa fa-send"></i>{{ $button }}</button>
</div>