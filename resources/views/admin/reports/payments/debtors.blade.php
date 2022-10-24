@extends('admin.layouts.master')

@section('title', 'Debtors Report')
@section('description', 'Oustanding this year.')

@section('content')
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="row">

                <div class="col-md-6">
                    {!! Form::open(['Method' => 'post', 'route' => 'admin.reports.payments.debtors.date_range_post']) !!}
                        @include('admin.reports.payments.includes.debtors_form', ['button' => 'Run Report'])
                    {!! Form::close() !!}
                </div>

                <div class="col-md-6">
                    {!! Form::open(['method' => 'post', 'url' => '/admin/reports/payments/debtors/export']) !!}
                        @include('admin.reports.payments.includes.debtors_form', ['button' => 'Run Export Report'])
                    {!! Form::close() !!}
                </div>

            </div>
            <hr>
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Labels</th>
                        @foreach($transactions->groupBy('month') as $month => $transaction)
                        <th class="text-right">
                            {{ $month }}
                        </th>
                        @endforeach
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions->groupBy('category') as $category => $transaction)
                    <tr>
                        <td>{{ ucfirst($category) }} Transactions</td>
                        @foreach($transaction->groupBy('month') as $month => $trans)
                        <td class="text-right">
                            {{ money_format('%.2n', $trans->where('type', 'debit')->sum('amount') - $trans->where('type', 'credit')->sum('amount')) }}
                        </td>
                        @endforeach
                        <td class="text-right">
                            <b>{{ money_format('%.2n', $transaction->where('type', 'debit')->sum('amount') - $transaction->where('type', 'credit')->sum('amount')) }}</b>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <th><b>Total</b></th>                    
                    @foreach($transactions->groupBy('month') as $month => $transaction)
                        <th class="text-right">
                            {{ money_format('%.2n', $transaction->where('type', 'debit')->sum('amount') - $transaction->where('type', 'credit')->sum('amount')) }}
                        </th>
                    @endforeach
                        <th class="text-right">                            
                            {{ money_format('%.2n', $transactions->where('type', 'debit')->sum('amount') - $transactions->where('type', 'credit')->sum('amount')) }}
                        </th>
                </tfoot>
            </table>
        </div>
    </div>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            $('.is-date').datepicker;
        });
    </script>
@stop