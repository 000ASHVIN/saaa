<div id="{{$invoice->id}}invoice_notes" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content" style="background-color: white; text-align: left">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Invoice #{{$invoice->reference}} Transactions</h4>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                    <th>Date</th>
                    <th>Tags</th>
                    <th>Invoice</th>
                    <th>Reference</th>
                    <th class="text-right">Dr</th>
                    <th class="text-right">Cr</th>
                    </thead>
                    <tbody>
                    @if($invoice->transactions->count())
                        @foreach($invoice->transactions->sortBy('date') as $transaction)
                            <tr class="
                            {{ ($transaction->tags == 'Payment') ? 'success' : '' }}
                            {{ ($transaction->tags == 'Discount') ? 'info' : '' }}
                            {{ ($transaction->tags == 'Cancellation') ? 'danger' : '' }}
                            ">
                                <td>{{ $transaction->date->toFormattedDateString() }}</td>
                                <td>{{ $transaction->display_type }}</td>
                                <td>
                                    <a target="_blank" href="{{ route('invoices.show',$invoice->id) }}">#{{ $invoice->reference }}</a>
                                </td>
                                <td>{{ $transaction->description }}</td>
                                <td class="text-right">{{ ($transaction->type == 'debit') ? $transaction->amountAsCurrency() : '-' }}</td>
                                <td class="text-right">{{ ($transaction->type == 'credit') ? $transaction->amountAsCurrency() : '-' }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>