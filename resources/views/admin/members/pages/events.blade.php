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
                @if(count($myTickets))
                    <div class="form-group clearfix">
                        <button class="btn btn-info btn-o pull-right clearfix" style="margin: 5px" id="deselectAll"><i class="fa fa-close"></i> Deselet All</button>
                        <button class="btn btn-success btn-o pull-right clearfix" style="margin: 5px" id="SelectAll"><i class="fa fa-check"></i> Select All</button>
                    </div>

                    {!! Form::open(['method' => 'post', 'route' => 'tickets.destroy']) !!}
                    @foreach($myTickets->sortBy('startDate') as $ticket)
                        @if($ticket->pricing && $ticket->event)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-white no-radius">
                                    <div class="panel-heading border-light" style="background: #01448c; color: white">
                                        <h4 class="panel-title">{{ ucfirst($ticket->pricing->venue->type) }} | <span class="text-bold">{{ $ticket->event->name }}</span></h4>
                                        <div class="panel-tools">
                                            <div class="form-inline">
                                                <div class="checkbox clip-check check-primary">
                                                    <input type="checkbox" id="{{ $ticket->id }}" value="{{$ticket->id}}" name="tickets[]">
                                                    <label for="{{ $ticket->id }}"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Start Date:</strong> @if($ticket->dates->first()) {{ $ticket->dates->first()->date }} @endif</p>
                                                <p><strong>Venue: </strong> {{ $ticket->pricing->venue->name }}</p>
                                                <p><strong>Price: </strong> R{{ $ticket->pricing->price }}</p>
                                                <a href="{{ route('ticket_edit', $ticket->id) }}" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i> Edit</a>
                                            </div>

                                            <div class="col-md-6">
                                                <p><strong>Ticket Number: </strong> {{ $ticket->code }}</p>
                                                <p><strong>Description: </strong> {{ $ticket->description }}</p>
                                                @if($ticket->invoice)
                                                    <p><strong>Invoice: </strong> # <a target="_blank" href="{{ route('invoices.show', $ticket->invoice->id) }}">{{ $ticket->invoice->reference }}</a></p>
                                                @else
                                                    <strong>Invoice: </strong> -
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                @else
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white no-radius">
                                <div class="panel-heading border-light">
                                    <h4 class="panel-title">{{ $member->first_name }} has <span class="text-bold">no events</span>
                                    </h4>
                                </div>
                                <div class="panel-body">
                                    <p>If you beleive this is a technical error, Please inform the development team.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(count($myTickets))
                    <hr>
                    <button type="submit" class="btn btn-danger btn-o btn-block" onclick="spin(this)"><i class="fa fa-trash"></i> Delete Selected Tickets</button>
                @endif
                {!! Form::close() !!}
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
    <script>
        $( document ).ready( function( )
        {$( '.delete' ).bootstrap_confirm_delete(
            {
                debug:    false,
                heading:  'Remove Ticket',
                message:  'Are you sure you want to delete this ticket?',
                data_type:'post',
                callback: function ( event )
                {
                    var button = event.data.originalObject;
                    button.closest( 'form' ).submit();
                },
            }
        );
        } );
    </script>

    @include('admin.members.includes.spin')
@stop