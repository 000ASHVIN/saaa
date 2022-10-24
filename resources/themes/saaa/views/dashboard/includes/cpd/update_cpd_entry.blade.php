<div class="modal fade" id="update_cpd_entry_{{$cpd->id}}" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Allocate CPD Hours</h4>
            </div>

            <div class="modal-body">
                {!! Form::model($cpd, ['method' =>'PUT', 'files' => true, 'route' => ['cpds.update', $cpd->id]]) !!}
                    @include('dashboard.includes.cpd.form', ['submit' => 'Update CPD Entry'])
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
