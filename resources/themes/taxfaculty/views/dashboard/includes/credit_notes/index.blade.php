<div id="credit_notes_{{$invoice->id}}" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Credit Notes for Invoice #{{ $invoice->reference }}</h4>
            </div>

            <div class="modal-body">
                <table class="table table-bordered table-stiped table-hover">
                    <thead>
                        <th>Number</th>
                        <th>Reference</th>
                        <th>Tags</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Amount</th>
                        <th class="text-center">Tools</th>
                    </thead>
                    <tbody>
                        @foreach($invoice->creditMemos as $creditmemo)
                            <tr>
                                <td>CN #{{ $creditmemo->id }}</td>
                                <td>{{ $creditmemo->reference }}</td>
                                <td>{{ $creditmemo->tags }}</td>
                                <td>{{ $creditmemo->description }}</td>
                                <td>{{ $creditmemo->category }}</td>
                                <td>R{{ number_format($creditmemo->amount / 100) }}</td>
                                <td class="text-center"><a target="_blank" href="{{ route('cn.view', [$creditmemo->invoice, $creditmemo->id]) }}" class="btn btn-primary btn-xs">View</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>