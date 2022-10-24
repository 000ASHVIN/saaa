<div id="designation_update_{{$designation->id}}" class="modal fade modal-aside horizontal right">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Update Designation
                </h4>
            </div>

            <div class="modal-body">
                {!! Form::model($designation, ['method' => 'PUT', 'route' => ['admin.professional_bodies.designation.update', $designation->id]]) !!}
                    @include('admin.professional_bodies.includes.designation_form', ['submit' => $submit])
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>