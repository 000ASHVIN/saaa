<div class="form-group @if ($errors->has('name')) has-error @endif">
    {!! Form::label('name', 'Name') !!}
    {!! Form::input('text', 'name', null, ['class' => 'form-control', 'placeholder' => 'Presentation']) !!}
    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('url')) has-error @endif">
    {!! Form::label('url', 'Url') !!}
    {!! Form::input('text', 'url', null, ['class' => 'form-control', 'placeholder' => 'http://google.com']) !!}
    @if ($errors->has('url')) <p class="help-block">{{ $errors->first('url') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('instructions')) has-error @endif">
    {!! Form::label('instructions', 'Instructions') !!}
    {!! Form::textarea('instructions', null, ['class' => 'form-control', 'rows' => '3']) !!}
    @if ($errors->has('instructions')) <p class="help-block">{{ $errors->first('instructions') }}</p> @endif
</div>

<div class="form-group @if ($errors->has('secret')) has-error @endif">
    {!! Form::label('secret', 'Password') !!}
    {!! Form::input('text', 'secret', null, ['class' => 'form-control', 'placeholder' => 'As43F']) !!}
    @if ($errors->has('secret')) <p class="help-block">{{ $errors->first('secret') }}</p> @endif
</div>

<hr>
<div class="alert alert-info">
    If you are uploading files, you do not need to complete the fields, this will be automatically completed once the upload has been completed.
</div>

<div class="form-group">
    <label for="files[]">Upload Files</label>
    <input type="file" name="files[]" class="form-control" multiple>
</div>

<hr>
<button class="btn btn-info" onclick="spin(this)">Save</button>
<button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>