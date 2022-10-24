<div id="extra_{{$extra->id}}_update" class="modal fade modal-aside horizontal right" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Extra</h4>
            </div>

            <div class="modal-body">
                {!! Form::model($extra, ['method' => 'post', 'route' => ['admin.event.extra.update', $event->slug, $extra->id]]) !!}
                    @include('admin.event.includes.extra.form', ['submit' => 'Create Extra'])
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>