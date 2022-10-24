@extends('admin.layouts.master')
@section('title', $member->first_name . ' ' . $member->last_name)
@section('description', 'User Profile')

@section('css')
    <link href="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <style>
        .daterangepicker{
            z-index:99999!important;
        }
    </style>
@stop

@section('content')

    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            @include('admin.members.includes.nav')
            <div class="col-md-9 col-sm-9 nopadding">
                <table class="table table-bordered">
                    <thead>
                    <th class="center">Date</th>
                    <th>Type</th>
                    <th>Invoice</th>
                    <th>Reference</th>
                    <th class="text-right">Dr</th>
                    <th class="text-right">Cr</th>
                    </thead>
                    <tbody>
                    @if(count($member->transactions))
                        @foreach($member->transactions->sortBy('date') as $transaction)
                        @if($transaction->invoice)
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
                            @endif
                        @endforeach
                    @endif
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="4" class="text-right">
                            <b>Totals: </b>
                        </td>
                        <td class="text-right">
                            <b>{{ money_format('%.2n', $member->transactions->where('type', 'debit')->sum('amount')) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ money_format('%.2n', $member->transactions->where('type', 'credit')->sum('amount')) }}</b>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right">
                            <b>Closing Balance:</b>
                        </td>
                        <td class="text-right">
                            <b>{{ money_format('%.2n', $member->transactions->where('type', 'debit')->sum('amount') - $member->transactions->where('type', 'credit')->sum('amount')) }}</b>
                        </td>
                    </tr>
                    </tfoot>
                </table>
                <div class="row">
                    <div class="col-md-12">
                        <div class="pull-right">
                            <div style="padding: 10px; border: 1px solid #e3e3e3">
                                {{--{{ dd($member->transactions->where('type', 'debit')->sum('amount') - $member->transactions->where('type', 'credit')->sum('amount')) }}--}}
                                Closing Balance: {{ money_format('%.2n', $member->transactions->where('type', 'debit')->sum('amount') - $member->transactions->where('type', 'credit')->sum('amount')) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script src="/assets/admin/assets/js/profile.js"></script>
    <script src="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.js"></script>
    <script src="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            Profile.init();
        });
    </script>
    <script src="/assets/admin/assets/js/bootstrap-confirm-delete.js"></script>
    @include('admin.members.includes.spin')
@stop