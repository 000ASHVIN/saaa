<div id="{{$venue->id}}_update" class="modal fade modal-aside horizontal right" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Venue</h4>
            </div>
            <div class="modal-body">
                {!! Form::model($venue, ['method' => 'post', 'route' => ['admin.event.venue.update', $event->slug, $venue->id]]) !!}
                    @include('admin.event.includes.venues.form', ['submit' => 'Update Venue'])
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>