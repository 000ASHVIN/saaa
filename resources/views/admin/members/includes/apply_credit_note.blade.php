<div id="new_credit_note{{$invoice->reference}}" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content" style="background-color: white; text-align: left">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Invoice {{$invoice->reference}} Apply Credit Note</h4>
            </div>

            <div class="modal-body">
                {!! Form::open(['Method' => 'Post', 'route' => ["credit_invoice", $invoice->id]]) !!}

               <div class="form-group">
                    {!! Form::label('amount', 'Amount') !!}
                    {!! Form::input('text', 'amount', null, ['class' => 'form-control', 'placeholder' => '500.00']) !!}
                </div>

                <button type="submit" class="btn btn-info" onclick="spin(this)">Add Credit Note</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>