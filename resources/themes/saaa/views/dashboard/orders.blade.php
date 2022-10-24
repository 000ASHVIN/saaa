@extends('app')

@section('title', 'Latest Purchase Orders')

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li class="active">My Orders</li>
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
                <div class="heading-title heading-dotted text-center">
                    <h4>Your Latest <span>Orders</span></h4>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @if(count($orders))
                            <table class="table table-striped" id="invoices_table">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Reference</th>
                                    <th>Type</th>
                                    <th>Total</th>
                                    <th>Discount</th>
                                    <th>Balance</th>
                                    <th >Status</th>
                                    <th class="text-center" colspan="2">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($orders->sortByDesc('id') as $order)
                                    <tr style="display: table-row;">
                                        <td>{{ $order->created_at->toFormattedDateString() }}</td>
                                        <td>#{{ $order->reference }}</td>
                                        <td>{{ ucfirst($order->type) }}</td>
                                        <td>R{{ number_format($order->total, 2) }}</td>
                                        <td>R{{ number_format($order->discount, 2) }}</td>
                                        <td>R{{ number_format($order->balance, 2) }}</td>
                                        <td>
                                            @if($order->status == 'paid')
                                                <a href="" class="label label-success" data-toggle="tooltip" title="Paid" data-placement="right">
                                                    <i class="fa fa-check"></i>
                                                </a>
                                            @else
                                                @if($order->status == 'unpaid')
                                                    <a href=""
                                                       class="label label-warning" data-toggle="tooltip" title="Unpaid" data-placement="right"> <i class="fa fa-times"></i>
                                                    </a>
                                                @elseif($order->status == 'partial')
                                                    <a href=""
                                                       class="label label-warning" data-toggle="tooltip" title="Unpaid" data-placement="right"> <i class="fa fa-times"></i>
                                                    </a>
                                                @else
                                                    <a href=""
                                                       class="label label-danger" data-toggle="tooltip" title="Cancelled" data-placement="right"> <i class="fa fa-ban"></i>
                                                    </a>
                                                @endif
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            <a target="_blank" href="{{ route('order.show', $order->id) }}" class="btn btn-primary btn-xs">View</a>
                                            @if($order->status == 'unpaid' || $order->status == 'partial')
                                                <a href="{{ route('order.settle', $order->id) }}" class="btn btn-success btn-xs">Settle</a>
                                            @else
                                                <button class="btn btn-success btn-xs disabled">Settle</button>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($order->status == 'unpaid')
                                                <a data-toggle="modal" data-target="#order_{{$order->id}}" class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Cancel</a>
                                            @else
                                                <button class="btn btn-danger btn-xs disabled"><i class="fa fa-times"></i> Cancel</button>
                                            @endif
                                        </td>
                                    </tr>
                                    @include('dashboard.includes.orders.cancel')
                                @endforeach
                                </tbody>
                            </table>

                            <div class="pull-right">
                                {!! $orders->render() !!}
                            </div>
                        @else
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Reference</th>
                                    <th>Type</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th class="text-right"><div style="margin-right: 27px">Actions</div></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="6">You have no orders yet.</td>
                                </tr>
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>
@stop