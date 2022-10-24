<div class="modal fade" id="order_{{$order->id}}" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Order #{{ $order->reference }}</h4>
            </div>

            <div class="modal-body">
                <p>Please note that by cancelling this purchase order, you will no longer have access to the event or store item purchased.</p>
                <p>If you wish to cancel this order, click on "I wish to cancel" or cancel.</p>

                {!! Form::open(['method' => 'post', 'route' => ['order.cancel', $order->id]]) !!}
                    <button class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-warning" type="submit" onclick="spin(this)">I wish to cancel</button>
                {!! Form::close() !!}
            </div>
        </div>

    </div>
</div>