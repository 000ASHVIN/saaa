<div id="webinar_create" class="modal fade modal-aside horizontal right">
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
                {!! Form::open(['method' => 'post', 'route' => ['admin.event.webinar.store', $event->slug]]) !!}
                    @include('admin.event.includes.webinar.form', ['submit' => 'Create Webinar'])
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>