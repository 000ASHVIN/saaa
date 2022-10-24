<div id="add_tag" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            {!! Form::open(['method' => 'post', 'route' => 'faq.tags_store']) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">New Tag</h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('title', 'Tag Title') !!}
                    {!! Form::input('text', 'title', null, ['class' => 'form-control', 'placeholder' => 'My Custom Tag']) !!}
                </div>

                <div class="form-group @if ($errors->has('type')) has-error @endif">
                    {!! Form::label('type', 'Select Type') !!}
                    {!! Form::select('type', [
                        '' => 'Please Select',
                        'general' => 'General',
                        'technical' => 'Technical',
                        'other' => 'Other',
                    ],null, ['class' => 'form-control']) !!}
                    @if ($errors->has('type')) <p class="help-block">{{ $errors->first('type') }}</p> @endif
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Create</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>

    </div>
</div>