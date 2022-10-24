<div id="pricing_create" class="modal fade modal-aside horizontal right" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">New Pricing</h4>
            </div>
            <div class="modal-body">
                {!! Form::open(['method' => 'post', 'route' => ['admin.event.pricings.store', $event->slug]]) !!}
                    @include('admin.event.includes.pricings.form', ['submit' => 'Create Pricing', 'pricing' => null])
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>