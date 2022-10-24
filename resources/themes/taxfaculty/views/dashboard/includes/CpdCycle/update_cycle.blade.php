<div class="modal fade" id=update_cpd_cycle role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Update Existing Cycle</h4>
                            </div>

                            <div class="col-md-12">
                                {!! Form::model($cycle, ['method' => 'Post', 'route' => ['dashboard.cycle.update',$cycle->id]]) !!}
                                    @include('dashboard.includes.CpdCycle.form', ['submit' => 'Update My Cycle'])
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
