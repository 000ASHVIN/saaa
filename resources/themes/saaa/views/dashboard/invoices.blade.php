@extends('app')

@section('title', 'Latest Invoices')

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li class="active">My Invoices</li>
                    </ol>
                </li>
            </ol>
        </li>
    </ol>
@stop

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-8">
                <div class="row">
                    <div class="col-md-12">
                        <div class="border-box">
                            <table class="table table-hover">
                                <thead>
                                    <th>Date</th>
                                    <th>Invoice Number</th>
                                    <th>Balance Due</th>
                                    <th>Status</th>
                                    <th class="text-right"><div style="margin-right: 27px">Actions</div></th>
                                </thead>
                                <tbody>
                                @if(count($invoices))
                                    @foreach($invoices as $invoice)
                                        <tr style="display: table-row;">
                                            <td>
                                                {{ $invoice->created_at->toFormattedDateString() }}
                                            </td>
                                            <td>
                                                #{{ $invoice->reference }}
                                            </td>
                                            <td>
                                                R {{ $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount') }}
                                            </td>
                                            <td>
                                                @if($invoice->status == 'paid')
                                                    <a href="" class="label label-success" data-toggle="tooltip" title="Paid" data-placement="right">
                                                        <i class="fa fa-check"></i>
                                                    </a>
                                                @else
                                                    @if($invoice->status == 'unpaid')
                                                        <a href=""
                                                           class="label label-warning" data-toggle="tooltip" title="Unpaid" data-placement="right"> <i class="fa fa-times"></i>
                                                        </a>
                                                    @elseif($invoice->status == 'partial')
                                                        <a href=""
                                                           class="label label-success" data-toggle="tooltip" title="Unpaid" data-placement="right"> <i class="fa fa-times"></i>
                                                        </a>
                                                    @else
                                                        <a href=""
                                                           class="label label-default" data-toggle="tooltip" title="Cancelled" data-placement="right"> <i class="fa fa-ban"></i>
                                                        </a>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                @if(count($invoice->creditMemos))
                                                    <a target="_blank" href="#" data-toggle="modal" data-target="#credit_notes_{{$invoice->id}}" class="label label-default">Credit Notes</a>
                                                    @include('dashboard.includes.credit_notes.index')
                                                @endif
                                                <a target="_blank" href="/invoices/view/{{ $invoice->id }}" class="btn btn-primary btn-xs">View</a>
                                                @if($invoice->status == 'unpaid' || $invoice->status == 'partial')
                                                    <a href="/invoices/settle/{{ $invoice->id }}"
                                                       class="btn btn-success btn-xs">Settle</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">You have no invoices available.</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="text-left">
                            {!! $invoices->render() !!}
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
@stop