<div id="designation_new" class="modal fade modal-aside horizontal right">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Create a new Designation
                </h4>
            </div>

            <div class="modal-body">
                {!! Form::open(['method' => 'POST', 'route' => 'admin.professional_bodies.designation.store']) !!}
                    @include('admin.professional_bodies.includes.designation_form', ['submit' => 'Create Designation'])
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>