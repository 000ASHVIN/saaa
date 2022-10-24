@extends('admin.layouts.master')

@section('title', 'Payments')
@section('description', 'View Payments that was allocated on each day')

@section('styles')
    <style>
        .dataTables_filter{
            float: right;
        }

        input.form-control.input-sm {
            width: 500px;
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            <div class="col-md-5">
                @include('admin.payments.includes.form')
            </div>

            <div class="col-md-7" style="padding-top: 20px; background-color: rgba(227, 227, 227, 0.05); border: 1px dashed rgba(227, 227, 227, 0.47);">
                @if($transactions)
                    <table class="table">
                        <thead>
                        @foreach($categories as $key => $value)
                            <th>{{ $key }}</th>
                        @endforeach
                        <th>Credit Cards</th>
                        <th>EFT</th>
                        <th>Instant EFT</th>
                        <th>Debit Order</th>
                        <th>Cash</th>
                        <th>Snap Scan</th>
                        </thead>
                        <tfoot>
                            <tr>
                                <td colspan="7">Date selected report: {{ $from->toFormattedDateString() }} - {{ $to->toFormattedDateString() }}</td>
                            </tr>
                            <tr>
                                <td colspan="7"><a href="{{ route('admin.payments.export', [date_format($from, 'd F Y'), date_format($to, 'd F Y'), $type]) }}" class="btn btn-xs btn-info">Export Data</a></td>
                            </tr>
                        </tfoot>
                        <tbody>
                        <tr>
                            @foreach($categories as $key => $value)
                                <td>{{ count($value) }}</td>
                            @endforeach
                            <td>{{ $transactions->where('method', 'cc')->count() }}</td>
                            <td>{{ $transactions->where('method', 'eft')->count() }}</td>
                            <td>{{ $transactions->where('method', 'instant_eft')->count() }}</td>
                            <td>{{ $transactions->where('method', 'debit')->count() }}</td>
                            <td>{{ $transactions->where('method', 'cash')->count() }}</td>
                            <td>{{ $transactions->where('method', 'snap_scan')->count() }}</td>
                        </tr>
                        </tbody>

                    </table>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @foreach($categories as $key => $value)
                    <div class="panel panel-default">
                        <div class="panel-heading" style="text-transform: uppercase;">
                            {{ $key }}
                            <div class="pull-right">Total = {{ money_format('%.2n', $value->sum('amount')) }}</div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <table class="table table-striped table-bordered mytable" cellspacing="0" width="100%">
                                    <thead>
                                    <th>User</th>
                                    <th>Payment Date</th>
                                    <th>Amount</th>
                                    <th>Payment description</th>
                                    <th>Invoice For</th>
                                    <th class="text-center">Payment Method</th>
                                    <th class="text-center">Invoice Number</th>
                                    <th class="text-center">Invoice Balance</th>
                                    <th>Status</th>
                                    </thead>
                                    <tbody>
                                    @foreach($value as $transaction)
                                    @if($transaction->invoice)
                                        <tr>
                                            <td>
                                                <a href="{{ ($transaction->user) ? route('admin.members.show', $transaction->user->id) : "#" }}" target="_blank">
                                                    {{ ucfirst(($transaction->user) ? $transaction->user->first_name : 'User') }}
                                                    {{ ucfirst(($transaction->user) ? $transaction->user->last_name : 'Cancelled') }}
                                                </a>
                                            </td>
                                            <td>{{ date_format($transaction->date, 'Y/m/d') }}</td>
                                            <td>{{ money_format('%.2n', $transaction->amount) }}</td>
                                            <td>{{ $transaction->description }}</td>
                                            <td>
                                                @foreach($transaction->invoice->items as $item)
                                                    {{ $item->name }} <br>
                                                @endforeach
                                            </td>
                                            <td class="text-center">{{ $transaction->method }}</td>
                                            <td class="text-center"><a href="{{ route('invoices.show',$transaction->invoice->id) }}" target="_blank">{{ $transaction->ref }}</a></td>
                                            <td class="text-center">
                                                R {{ $transaction->invoice->transactions->where('type', 'debit')->sum('amount') - $transaction->invoice->transactions->where('type', 'credit')->sum('amount') }}
                                            </td>
                                            <td>{{ $transaction->invoice->status }}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="/assets/admin/vendor/moment/moment.min.js"></script>
    <script type="text/javascript" src="/assets/admin/vendor/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
    <script>
        $(function() {
            $('.daterange').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    format: 'YYYY-MM-DD'
                },
            });
        });

        $(document).ready(function() {
          $('.mytable').DataTable( {
                "paging":   false,
                "ordering": true,
                "info":     true,
                "order": [[ 1, 'asc' ]]
            } );
        } );

    </script>

    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
@endsection