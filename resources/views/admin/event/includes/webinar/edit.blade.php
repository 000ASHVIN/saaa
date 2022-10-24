<div id="webinar_{{$webinar->id}}_edit" class="modal fade modal-aside horizontal right">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    Create a new Link
                </h4>
            </div>
            <div class="modal-body">
                {!! Form::model($webinar, ['method' => 'post', 'route' => ['admin.event.webinar.update', $webinar->id]]) !!}
                    @include('admin.event.includes.webinar.form', ['submit' => 'Update'])
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>