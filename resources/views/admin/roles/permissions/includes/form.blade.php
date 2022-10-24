<div class="form-group">
    {!! Form::label('role', 'Available Roles') !!}
    {!! Form::select('role', $roles ,null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('all_permissions', 'Available Permissions') !!}
    {!! Form::select('all_permissions[]', $permissions ,null, ['class' => 'form-control', 'multiple' => 'multuple']) !!}
</div>

<div class="form-group">
    {!! Form::submit($button, ['class' => 'btn btn-primary']) !!}
</div>