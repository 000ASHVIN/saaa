<div id="assign_assessment" class="modal fade modal-aside horizontal right" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Assign New Assessment</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['method' => 'post', 'route' => ['admin.video.assessment.store', $video->id]]) !!}

                <div class="form-group @if ($errors->has('assessments[]')) has-error @endif">
                    {!! Form::label('assessments[]', 'Please select your features') !!}
                    {!! Form::select('assessments[]', $assessments->pluck('title', 'id')->toArray(), $video->assessments->pluck('id')->toArray(), ['class' => 'select2', 'multiple' => true, 'style' => 'width:100%']) !!}
                    @if ($errors->has('assessments[]')) <p class="help-block">{{ $errors->first('assessments[]') }}</p> @endif
                </div>

                <hr>

                {!! Form::submit('Assign Assessment', ['class' => 'btn btn-info']) !!}
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>