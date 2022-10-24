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

<?php
    $superUser = auth()->user()->hasRole('super');
    $user_has_access = userHasAccess(auth()->user());
?>

@section('content')

    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            @include('admin.members.includes.nav')
            <div class="col-md-9 col-sm-9 nopadding">
                @if(auth()->user()->hasRole('super', 'accounts-administrator'))
                    <div class="pull-right">
                        <a href="{{ route('consolidate_invoices', $member->id) }}" data-confirm-content="Are you sure you want to consolodate all CPD subscription Invoices"  class="btn btn-info"><i class="fa fa-check"></i> Consolidate CPD Invoices</a>
                        <br>
                        <br>
                    </div>
                @endif
                <table class="table table-striped table-hover table-bordered" id="projects">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Reference</th>
                        <th class="hidden-xs">Disc</th>
                        <th>Total</th>
                        <th class="hidden-xs">Balance</th>
                        <th class="hidden-xs text-left">Status</th>
                        @if($user_has_access)
                            <th class="hidden-xs text-left">PTP</th>
                        @endif
                        @if($superUser)
                            <th class="hidden-xs text-left">Agent</th>
                        @endif
                        <th class="hidden-xs text-left">Terms</th>
                        @if($user_has_access)
                            <th class="text-center" colspan="2">Tools</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                        @if($invoices)
                            @foreach($invoices->sortByDesc('created_at') as $invoice)
                                <tr>
                                    <td>
                                        {{ $invoice->created_at->toFormattedDateString() }}
                                    </td>
                                    <td>
                                        <a target="_blank" href="{{ route('invoices.show',$invoice->id) }}"
                                           class="btn btn-link">{{ $invoice->reference }}</a>
                                    </td>
                                    <td class="hidden-xs">R {{ $invoice->discount }}</td>
                                    <td>{{ money_format('%.2n', (($invoice->total<99999999.99)?$invoice->total:$invoice->transactions->where('type', 'debit')->sum('amount') )) }}</td>
                                    <td class="hidden-xs">R {{ $invoice->transactions->where('type', 'debit')->sum('amount') - $invoice->transactions->where('type', 'credit')->sum('amount') }}</td>
                                    <td class="hidden-xs text-left">
                                        @if($invoice->status == 'paid')
                                            Paid
                                        @else
                                            {{ ucwords($invoice->status) }}
                                        @endif
                                    </td>
                                    
                                    @if($user_has_access)
                                        <td>
                                            <a href="#" class="btn btn-xs {{ (strtotime($invoice->ptp_date) > 0 ? "btn-success" : "btn-default") }}" data-toggle="modal" data-target="#ptp_invoice_{{ $invoice->id }}">PTP</a>
                                            @include('admin.members.includes.promise_to_pay')
                                        </td>
                                    @endif
                                    @if($superUser)
                                        <td class="text-center">
                                            @if ($invoice->salesPerson && $invoice->salesPerson != 'system')
                                                <a href="/admin/invoices/{{ $invoice->id }}/allocate-system" class="btn btn-xs btn-danger" data-toggle="tooltip" title="({{ $invoice->salesPerson }})  <br> Allocate to system" data-html="true">
                                                    <i class="fa fa-close"></i>
                                                </a>
                                            @elseif($invoice->salesPerson)
                                                {{ ucwords($invoice->salesPerson) }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endif
                                    <td class="hidden-xs text-left">
                                        {{ $invoice->is_terms_accepted ? 'Yes' : 'No' }}
                                    </td>
                                    @if($user_has_access)
                                        <td class="text-center">
                                            {!! Form::open(['url' => '/admin/invoices/'.$invoice->id.'/cancel', 'id' => $invoice->reference]) !!}
                                            <a href="/admin/invoices/{{ $invoice->id }}/allocate" data-toggle="tooltip" title="Allocate"
                                            class="btn btn-xs btn-success">
                                                <i class="fa fa-check"></i>
                                            </a>

                                            @include('admin.confirm_delete.confirm_cancel')
                                            <a href="#" data-target="#new_credit_note{!!$invoice->reference!!}" data-toggle="modal"
                                            class="btn btn-xs btn-info">
                                                <i class="fa fa-check"></i>
                                            </a>

                                            @if($member->wallet->amount > 0)
                                                <a href="{{ route('dashboard.wallet.pay', [$member->id, $invoice->id]) }}"
                                                class="btn btn-xs btn-warning" data-confirm-title="Wallet Payment" data-confirm-content="Are you sure you want to settle this invoice using the client's wallet?">
                                                    <i class="fa fa-check"></i>
                                                </a>
                                            @endif

                                            @if($invoice->status != 'paid' && $invoice->status != 'cancelled')
                                                <a href="#" data-target="#confirm{!!$invoice->reference!!}" data-toggle="modal" class="btn btn-xs btn-danger">
                                                    <i class="fa fa-close"></i>
                                                </a>
                                            @else
                                                <a href="#" class="btn btn-xs btn-danger disabled">
                                                    <i class="fa fa-close"></i>
                                                </a>
                                            @endif

                                            <a onclick="send(this)" href="{{ route('resend_invoice', $invoice->id) }}"><span class="label label-success"><i class="fa fa-send"></i></span></a>

                                            {!! Form::close() !!}
                                            @include('admin.members.includes.apply_credit_note', $invoice)
                                        </td>

                                        <td class="text-center">
                                            <a href="#"
                                            class="btn btn-xs btn-warning" data-toggle="modal" data-target="#{{$invoice->id}}invoice_notes">
                                                TXN
                                            </a>

                                            @if($invoice->creditMemos->count())
                                                <a target="_blank" href="#" data-toggle="modal" data-target="#credit_notes_{{$invoice->id}}" class="btn btn-info btn-xs">CR</a>
                                                @include('dashboard.includes.credit_notes.index')
                                            @else
                                                <a target="_blank" href="#" class="btn btn-default btn-xs" disabled="disabled">CR</a>
                                            @endif
                                            @include('admin.members.includes.invoice_notes', $invoice)
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8">No invoice records found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                <div class="text-center">
                    @if(count($invoices))
                        {!! $invoices->render() !!}
                    @endif
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