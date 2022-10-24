<div id="discount_create" class="modal fade modal-aside horizontal right" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">New Discount</h4>
            </div>

            <div class="modal-body">
                {!! Form::open(['method' => 'post', 'route' => ['admin.event.discount.store', $event->slug]]) !!}
                    @include('admin.event.includes.discount.form', ['submit' => 'Create Discount'])
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>