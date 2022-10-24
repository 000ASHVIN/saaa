<div id="ptp_invoice_{{ $invoice->id }}" class="modal fade text-left" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            {!! Form::open(['method' => 'Post', 'route' => ['admin.member.ptp_invoice_arrangment', $invoice->id]]) !!}
            <div class="modal-body">
                <div class="alert alert-warning">
                    <p><strong>Please Note</strong> that once a date has been selected, the client will be notified via email with the date you have selected.
                        A note will be automatically placed on the account with the selected promise to pay date.
                    </p>
                </div>

               <div class="center">
                   @if(strtotime($invoice->ptp_date) > 0)
                       <p><strong>PTP:</strong> {{ $invoice->ptp_date }}</p>
                       <hr>
                   @endif
               </div>

                @if(strtotime($invoice->ptp_date) < 0)
                    <div class="form-group @if ($errors->has('date')) has-error @endif">
                        {!! Form::input('text', 'date', null, ['class' => 'form-control datepicker', 'v-model' => 'date']) !!}
                        @if ($errors->has('date')) <p class="help-block">{{ $errors->first('date') }}</p> @endif
                    </div>
                @endif

                <div class="text-center">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    @if(strtotime($invoice->ptp_date) > 0)
                        <button type="submit" onclick="send(this)" class="btn btn-warning">Remove PTP</button>
                    @else
                        <button type="submit" onclick="send(this)" class="btn btn-info">Confirm PTP</button>
                    @endif
                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>