<div id="{{$venue->id}}_date_create" class="modal fade modal-aside horizontal right" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Create a new date</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['method' => 'post', 'route' => ['admin.venues.dates.create', $venue->id]]) !!}
                    @include('admin.event.includes.dates.form', ['submit' => 'Create Date'])
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>