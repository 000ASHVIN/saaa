@extends('admin.layouts.master')

@section('title', 'Invoices')
@section('description', 'Oustanding invoices'. ' '.count($users))

@section('content')
    <br>
    <div class="row">
        <div class="col-md-12">
            <div class="text-right"><a href="{{ route('admin.reports.payments.outstanding-invoices-export') }}" class="btn btn-default">Export</a></div>
            <br>
                @foreach($users as $user)
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="btn btn-success btn-xs pull-right" style="color: white;" href="#{{$user->id}}" data-toggle="collapse">Show Invoices</a>
                                    <a class="btn btn-danger btn-xs pull-right" style="color: white; margin-right: 5px">Open Invoices: {{$user->invoice_count}}</a>
                                    <a class="btn btn-info btn-xs pull-left" style="color: white; margin-right: 5px">Payment Method: - {{ $user->payment_method }}</a>
                                    <a data-toggle="collapse" href="#{{$user->id }}">{{ $user->first_name }} {{ $user->last_name }} </a>
                                </h4>
                            </div>
                            <div id="{{$user->id }}" class="collapse">
                                    <div class="panel-body">
                                        <table class="table">
                                            <thead>
                                                <th>Date</th>
                                                <th>Invoice</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </thead>
                                            <tbody>
                                            @foreach($user->overdueInvoices() as $invoice)
                                                <tr>
                                                    <td>{{ $invoice->created_at->ToformattedDateString() }}</td>
                                                    <td><a target="_blank" href="{{ route('invoices.show', $invoice->id) }}">#{{ $invoice->reference }}</a></td>
                                                    <td>{{ money_format('%.2n', $invoice->total - $invoice->transactions->where('type', 'credit')->sum('amount')) }}</td>
                                                    <td>{{$invoice->status}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                            </div>
                        </div>
                    </div>
                @endforeach

        </div>
    </div>
@stop
@section('scripts')
    <script src="/assets/admin/assets/js/index.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop