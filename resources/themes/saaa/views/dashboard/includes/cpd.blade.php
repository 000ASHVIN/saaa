    <div class="modal fade" id="ajax" role="basic" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                    <h4 class="modal-title">Allocate CPD Hours</h4>
                </div>

                <div class="modal-body">
                    {!! Form::open(['files' => true, 'route' => 'cpds.store']) !!}
                        @include('dashboard.includes.cpd.form', ['submit' => 'Allocate CPD'])
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
