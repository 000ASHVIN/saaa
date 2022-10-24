<div id="discount_{{$order->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog text-left">
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h4 class="heaing">Discount Invoice Order #{{ $order->reference }}</h4>
            </div>
            {!! Form::open(['method' => 'POST', 'route' => ['post_discount_order', $order->id]]) !!}

            <div class="modal-body">
                <table class="table">
                    <tr>
                        <td>Date</td>
                        <td>Total</td>
                        <td>Discount</td>
                        <td>Balance</td>
                    </tr>
                    <tr>
                        <td>{{ date_format($order->created_at, 'd F Y') }}</td>
                        <td>R{{ number_format($order->total, 2) }}</td>
                        <td>R{{ number_format($order->discount, 2) }}</td>
                        <td>R{{ number_format($order->balance, 2) }}</td>
                    </tr>
                </table>

                <div class="form-group @if ($errors->has('amount')) has-error @endif">
                    {!! Form::label('amount', 'Discount Amount') !!}
                    {!! Form::input('text', 'amount', null, ['v-model' => 'discount_amount', 'class' => 'form-control', 'placeholder' => 'Enter your amount example: 200']) !!}
                    @if ($errors->has('amount')) <p class="help-block">{{ $errors->first('amount') }}</p> @endif
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="spin(this)">Discount</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>