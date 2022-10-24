<div id="changePaymentMethod" class="modal fade" role="dialog">
    <div class="modal-dialog">
        {!! Form::open(['method' => 'post', 'route' => ['admin.member.change_payment_method', $member->id]]) !!}
        
        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h4 class="heaing">Change Payment Method</h4>
            </div>

            <div class="modal-body">
                <div class="form-group @if ($errors->has('payment_method')) has-error @endif">
                    {!! Form::label('payment_method', 'Payment Method') !!}
                    {!! Form::select('payment_method', ['credit_card' => 'Credit Card', 'debit_order' => 'Debit Order', 'eft' => 'EFT', 'instant_eft' => 'Instant EFT'], $member->payment_method ?? null, ['class' => 'form-control', 'placeholder' => 'Please Select Payment Method']) !!}
                    @if ($errors->has('payment_method')) <p class="help-block">{{ $errors->first('payment_method') }}</p> @endif
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Change</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>