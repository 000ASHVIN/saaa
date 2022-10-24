<div id="{{$date->id}}_date_update" class="modal fade modal-aside horizontal right" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Date</h4>
            </div>
            <div class="modal-body">
                {!! Form::model($date, ['method' => 'post', 'route' => ['admin.venues.dates.update', $event->slug, $date->id, $venue->id]]) !!}
                    @include('admin.event.includes.dates.form', ['submit' => 'Update Date'])
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>