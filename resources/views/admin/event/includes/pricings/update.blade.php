<div id="{{$pricing->id}}_pricing_update" class="modal fade modal-aside horizontal right" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Update Pricing</h4>
            </div>
            <div class="modal-body">
                {!! Form::model($pricing, ['method' => 'post', 'route' => ['admin.event.pricings.update', $pricing->id]]) !!}
                    @include('admin.event.includes.pricings.form', ['submit' => 'Update Pricing', 'pricing' => $pricing])
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>