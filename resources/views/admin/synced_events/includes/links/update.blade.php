<div id="link_{{$link->id}}_update" class="modal fade modal-aside horizontal right">
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
                {!! Form::model($link, ['method' => 'post', 'route' => ['admin.event.link.update', $link->id]]) !!}
                    @include('admin.event.includes.links.form', ['submit' => 'Update'])
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>