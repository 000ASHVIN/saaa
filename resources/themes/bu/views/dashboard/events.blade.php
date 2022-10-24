@extends('app')

@section('title', 'My '.\Carbon\Carbon::now()->year.' CPD Events')

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li class="active">My Events</li>
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
                <div class="alert alert-bordered-dotted margin-bottom-30">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">x</span><span
                                class="sr-only">Close</span></button>
                    <h4><strong>Event Tickets & Resources</strong></h4>

                    <p>
                        Please click on any of the following events to view your available tickets. <br>
                        For the related links and resources to the event please click on "Links & Resources" and go the required tab.
                    </p>

                    <p>To Claim CPD, Please click on Events & Resources and then go to the CPD Tab</p>
                </div>

                @include('dashboard.includes.tickets',['tickets' => $tickets, 'limit' => '0', 'howMany' => '50'])

               {{--<div class="pull-right">--}}
                   {{--{!! $tickets->render() !!}--}}
               {{--</div>--}}
            </div>
        </div>
    </section>
@stop

@section('scripts')

@stop