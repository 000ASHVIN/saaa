<div class="modal fade" id=create_new_cpd_cycle role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Create a new Cycle</h4>
                            </div>

                            <div class="col-md-12">
                                {!! Form::open(['method' => 'Post', 'route' => 'dashboard.cycle.create']) !!}
                                    @include('dashboard.includes.CpdCycle.form', ['submit' => 'Create New Cycle'])
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>