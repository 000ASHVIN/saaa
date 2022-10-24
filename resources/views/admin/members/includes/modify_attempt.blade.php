<div id="modify_attempt_{{$attempt->id}}" class="modal fade modal-aside horizontal right" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Attempt #{{ $int }} : {{ $attempt->assessment->title }}</h4>
            </div>

            {!! Form::model($attempt, ['method' => 'POST', 'route' => ['admin.event.attempt.update', $attempt->id]]) !!}
            <div class="modal-body">
                <div class="form-group @if ($errors->has('created_at')) has-error @endif">
                    {!! Form::label('created_at', 'Attempt Percentage') !!}
                    {!! Form::input('text', 'created_at', date_format($attempt->created_at, 'd F Y'), ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                    @if ($errors->has('created_at')) <p class="help-block">{{ $errors->first('created_at') }}</p> @endif
                </div>

                <div class="form-group @if ($errors->has('percentage')) has-error @endif">
                    {!! Form::label('percentage', 'Attempt Percentage') !!}
                    {!! Form::input('text', 'percentage', $attempt->percentage.'%', ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                    @if ($errors->has('percentage')) <p class="help-block">{{ $errors->first('percentage') }}</p> @endif
                </div>

                <div class="form-group @if ($errors->has('passed')) has-error @endif">
                    {!! Form::label('passed', 'Attempt Passed') !!}
                    {!! Form::select('passed', [
                        true => 'Yes',
                        false => 'No'
                    ],null, ['class' => 'form-control']) !!}
                    @if ($errors->has('passed')) <p class="help-block">{{ $errors->first('passed') }}</p> @endif
                </div>

                {!! Form::submit('Update Attempt', ['class' => 'btn btn-info']) !!}
            </div>
            {!! Form::close() !!}
        </div>

    </div>
</div>