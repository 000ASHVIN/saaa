<div id="note_{{$note->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog text-left">

        {!! Form::model($note, ['method' => 'POST', 'route' => ['admin.notes.update', $note->id]]) !!}
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="heaing">Edit Note - {{ date_format($note->created_at, 'd F Y') }}</h4>
            </div>

            <div class="modal-body">
                <div class="form-group @if ($errors->has('logged_by')) has-error @endif">
                    {!! Form::label('logged_by', 'Change Logged By') !!}
                    {!! Form::select('logged_by', \App\Rep::all()->pluck('name', 'id') ,null, ['class' => 'form-control']) !!}
                    @if ($errors->has('logged_by')) <p class="help-block">{{ $errors->first('logged_by') }}</p> @endif
                </div>

                <div class="form-group @if ($errors->has('invoice_reference')) has-error @endif">
                    {!! Form::label('invoice_reference', 'Invoice Reference') !!}
                    {!! Form::input('text', 'invoice_reference', null, ['class' => 'form-control', 'placeholder' => '002310']) !!}
                    @if ($errors->has('invoice_reference')) <p class="help-block">{{ $errors->first('invoice_reference') }}</p> @endif
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update Note</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>