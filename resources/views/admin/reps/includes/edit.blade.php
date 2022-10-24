<div id="rep_{{ $rep->id }}" class="modal fade" role="dialog">
    <div class="modal-dialog text-left">

        {!! Form::model($rep, ['method' => 'POST', 'route' => ['admin.reps.update', $rep->id]]) !!}
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="heaing">Edit Sales Rep - {{ ucwords($rep->name) }}</h4>
            </div>

            <div class="modal-body">
                @include('admin.reps.includes.form')
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Update Rep</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
        {!! Form::close() !!}

    </div>
</div>