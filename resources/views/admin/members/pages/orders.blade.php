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
    <?php   
    $user_has_access = userHasAccess(auth()->user());
    ?>
    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            @include('admin.members.includes.nav')
            <div class="col-md-9">
                <table class="table table-striped table-hover table-bordered" id="projects">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Type</th>
                        <th>Terms</th>
                        <th>Total</th>
                        <th>Discount</th>
                        <th>Balance</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" colspan="2">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (count($orders))
                        @foreach($orders->sortByDesc('id') as $order)
                            <tr style="display: table-row;">
                                <td>{{ $order->created_at->toFormattedDateString() }}</td>
                                <td>#{{ $order->reference }}</td>
                                <td>{{ ucfirst($order->type) }}</td>
                                <td class="hidden-xs text-left">
                                    {{ $order->is_terms_accepted ? 'Yes' : 'No' }}
                                </td>
                                <td>R{{ number_format($order->total, 2) }}</td>
                                <td>R{{ number_format($order->discount, 2) }}</td>
                                <td>R{{ number_format($order->balance, 2) }}</td>
                                <td class="text-center">
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
                                @if($user_has_access)
                                    <td>
                                    
                                        @if( $order->discount == 0 )    
                                        <a href="#" class="btn btn-xs btn-default" data-toggle="modal" data-target="#course_edit{{ $order->id }}">Apply Coupon Code</a>
                                        @else
                                        <a href="#" class="btn btn-xs btn-default" data-toggle="modal" data-target="">Coupon Code Applied</a>
                                        @endif
                                        
                                        
                                        @include('admin.members.includes.coupon_event_code')
                                    </td>
                                @endif
                                <td class="text-center">
                                    <a target="_blank" href="{{ route('order.show', $order->id) }}" class="btn btn-primary btn-xs">View</a>
                                    @if($user_has_access)
                                        @if($order->status == 'unpaid' || $order->status == 'partial')
                                            <a href="{{ route('allocate_order_payment', $order->id)}}" class="btn btn-success btn-xs">Settle</a>
                                            {{--  <a href="#" data-target="#discount_{{$order->id}}" data-toggle="modal" class="btn btn-warning btn-xs">Discount</a>  --}}

                                            @include('admin.members.includes.discount_purchase_order', ['order' => $order])
                                        @else
                                            <a href="#" class="btn btn-success btn-xs disabled">Settle</a>
                                            {{--  <a href="#" class="btn btn-warning btn-xs disabled">Discount</a>  --}}
                                        @endif
                                        <a href="{{ route('member.orders.resend', $order->id) }}" class="btn btn-success btn-xs">Resend</a>
                                    @endif

                                </td>

                                @if($user_has_access)
                                    <td>
                                        @if ($order->status == 'unpaid' || $order->status == 'partial')
                                            {!! Form::open(['method' => 'post', 'route' => ['order.cancel', $order->id]]) !!}
                                                <button class="btn btn-xs btn-danger">Cancel</button>
                                            {!! Form::close() !!}
                                        @else
                                            <button class="btn btn-xs btn-danger disabled">Cancel</button>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8">Member has no purchase orders..</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
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