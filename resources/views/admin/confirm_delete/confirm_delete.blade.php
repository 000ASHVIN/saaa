<div id="confirm{!! $invoice->reference. '' .$payment->id !!}" class="modal fade modal-invoice" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content" style="background-color: white">
            <div class="modal-header">
                <h4 class="heaing">Payment Delete</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure that you want to remove this payment?</p>
                <p>Once this payment has been removed you will not be able to retreive this again.</p>
            </div>
            <div class="modal-footer">
                <button type="button"  id="delete" class="btn btn-default delete-me" data-dismiss="modal">Remove</button>
                <button type="button"  class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>

    </div>
</div>