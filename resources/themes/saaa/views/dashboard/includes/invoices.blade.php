<div class="col-md-6">
    <div class="heading-title heading-dotted text-center">
        <h4>Latest <span>Invoices</span></h4>
    </div>
    <div class="border-box">
        <table class="table table-striped">
            <thead>
            <th>Invoice Number</th>
            <th>Balance Due</th>
            <th>Status</th>
            </thead>
            <tbody>
            @if(count($user->invoices))
                @foreach($user->invoices()->latest()->take(2)->get() as $invoice)
                <tr>
                    <td>#{{ $invoice->reference }}</td>
                    <td>{{ money_format('%.2n', $invoice->total - $invoice->transactions->where('type', 'credit')->sum('amount')) }}</td>
                    <td>
                        @if($invoice->status == 'paid')
                            <a href="" class="label custom-label" data-toggle="tooltip" title="Paid" data-placement="right">
                                <i class="fa fa-check"></i>
                            </a>
                        @else
                            @if($invoice->status == 'unpaid' || $invoice->status == 'partial')
                                <a href=""
                                   class="label custom-label" data-toggle="tooltip" title="Unpaid" data-placement="right"> <i class="fa fa-times"></i>
                                </a>
                            @else
                                <a href=""
                                   class="label custom-label" data-toggle="tooltip" title="Cancelled" data-placement="right"> <i class="fa fa-ban"></i>
                                </a>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3">You have no available invoices.</td>
                </tr>
            @endif
            </tbody>
        </table>
        <p style="text-align: center">
            {{-- <a href="#" class="btn btn-primary" data-target="#payment" data-toggle="modal">Settle Account</a> --}}
            <a href="{{ route('dashboard.invoices') }}" class="btn btn-default">View Invoices</a>
        </p>
        @include('dashboard.includes.payment')
    </div>
</div>