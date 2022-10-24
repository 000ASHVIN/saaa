<div id="confirm{!! $invoice->reference !!}" class="modal fade modal-invoice" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content text-left" style="background-color: white">
            <div class="modal-header">
                <h4 class="heading">Invoice Cancellation</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure that you want to cancel this invoice?</p>
                <p>Once this invoice has been cancelled you will not be able to undo this.</p>
                <hr>
                <div class="form-group @if ($errors->has('reason')) has-error @endif">
                    {!! Form::label('reason', 'Reason for cancellation') !!}
                    {!! Form::textarea('reason', 'Invoice #'.$invoice->reference.' was cancelled due to non payment. No service applied.', ['class' => 'form-control']) !!}
                    @if ($errors->has('reason')) <p class="help-block">{{ $errors->first('reason') }}</p> @endif
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit"  id="delete" class="btn btn-default delete-me">Cancel Invoice</button>
                <button type="button"  class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>

    </div>
</div>