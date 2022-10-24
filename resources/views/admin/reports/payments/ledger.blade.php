@extends('admin.layouts.master')

@section('title', 'Transactions')
@section('description', $transactions->total() . ' Transactions.')

@section('content')
    <br>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-hover" id="sample-table-2">
                <thead>
                <tr>
                    <th class="center">Date</th>
                    <th>Type</th>
                    <th>Invoice</th>
                    <th>Reference</th>
                    <th class="text-right">Dr</th>
                    <th class="text-right">Cr</th>
                </tr>
                </thead>
                <tbody>
                    @if(count($transactions))
                    @foreach($transactions->sortBy('date') as $transaction)
                    <tr class="
                        {{ ($transaction->tags == 'Payment') ? 'success' : '' }}
                        {{ ($transaction->tags == 'Discount') ? 'info' : '' }}
                        {{ ($transaction->tags == 'Cancellation') ? 'danger' : '' }}
                    ">
                        <td>{{ $transaction->date->toFormattedDateString() }}</td>
                        <td>{{ $transaction->display_type }}</td>
                        <td>
                            <a target="_blank" href="{{ route('invoices.show',$transaction->invoice->id) }}">#{{ $transaction->invoice->reference }}</a>                            
                        </td>
                        <td>{{ $transaction->description }}</td>
                        <td class="text-right">{{ ($transaction->type == 'debit') ? $transaction->amountAsCurrency() : '-' }}</td>
                        <td class="text-right">{{ ($transaction->type == 'credit') ? $transaction->amountAsCurrency() : '-' }}</td>
                    </tr>
                    @endforeach
                    <tfoot>
                        <tr>                            
                            <td colspan="4" class="text-right">
                                <b>Totals: </b>
                            </td>
                            <td class="text-right">
                                <b>{{ money_format('%.2n', $transaction->totalDebit()) }}</b>
                            </td>
                            <td class="text-right">
                                <b>{{ money_format('%.2n', $transaction->totalCredit()) }}</b>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5" class="text-right">
                                <b>Closing Balance:</b>
                            </td>
                            <td class="text-right">
                                <b>{{ money_format('%.2n', $transaction->totalDebit() - $transaction->totalCredit()) }}</b>
                            </td>
                        </tr>
                    </tfoot>
                    @else
                    <tr>
                        <td>
                            We have no transactions...
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
            @if(count($transactions))
            {!! $transactions->render() !!}
            @endif            
        </div>
    </div>
@stop
@section('scripts')
    <script src="assets/admin/assets/js/index.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop