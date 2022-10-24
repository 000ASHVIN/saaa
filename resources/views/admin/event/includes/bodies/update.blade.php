<div id="{{$pricing->id}}_body_update" class="modal fade modal-aside horizontal right" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{ $pricing->name }} Bodies</h4>
            </div>
            <div class="modal-body">
                {!! Form::model($pricing, ['method' => 'post', 'route' => ['admin.event.pricing.bodies.sync', $pricing->id]]) !!}

                <p>Please select carefully your professional bodies from the list below. If you have selected your professional body and a user is a member of this body this is the pricing he will receive for this venue.</p>
                <hr>

                <div class="form-group @if ($errors->has('bodyList[]')) has-error @endif">
                    {!! Form::label('bodyList[]', 'Please select your professional bodies') !!}
                    {!! Form::select('bodyList[]', $bodies->pluck('title', 'id')->toArray(), $pricing->bodies->pluck('id')->toArray(), ['class' => 'select2', 'multiple' => true, 'style' => 'width:100%']) !!}
                    @if ($errors->has('bodyList[]')) <p class="help-block">{{ $errors->first('bodyList[]') }}</p> @endif
                </div>

                {!! Form::submit('Save', ['class' => 'btn btn-info']) !!}
                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>

                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>