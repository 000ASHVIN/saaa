@extends('app')

@section('title', 'My Support Tickets')

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li class="active">My Support Tickets</li>
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

                <div class="form-group text-right">
                    <a href="{{ route('support_ticket.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-envelope-o"></i> New Ticket</a>
                </div>

                @if (count($tickets))
                    @foreach($tickets as $ticket)
                        <div class="panel panel-default">
                            <div class="panel-heading clearfix">
                                <div class="pull-left">
                                    {{ str_limit(ucfirst($ticket->subject), '250') }}
                                </div>
                                <div class="pull-right">
                                    <div class="label label-round label-success"><i class="fa fa-envelope-o"></i> {{ $ticket->thread->replies }}</div>
                                    @if($ticket->fresh_ticket_id)
                                        <a href="{{ route('support_ticket.show', $ticket->id) }}"><div class="label label-round label-primary">Show Replies <i class="fa fa-arrow-right"></i>&nbsp; </div></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-bordered-dotted margin-bottom-30 text-center">
                        <h4><strong><i class="fa fa-support"></i> My Support Tickets</strong></h4>
                        <p>You have no support tickets available</p>
                        <p>Need help? create a new support ticket by clicking on New Ticket</p>
                    </div>
                @endif

                {!! $tickets->render() !!}
            </div>
        </div>
    </section>
@stop

@section('scripts')

@stop