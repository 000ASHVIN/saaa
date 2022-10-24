<div id="{{$pricing->id}}_pricing_feature" class="modal fade modal-aside horizontal right">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    {{ $pricing->venue->name }}
                </h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['method' => 'post', 'route' => ['admin.event.features.sync', $pricing->id]]) !!}
                    @include('admin.synced_events.includes.feature.form', ['submit' => 'Save'])
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>