@extends('app')

@section('content')

@section('title')
    Updated legislation including Amendments
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('legislation') !!}
@stop

@if(auth()->check() && auth()->user()->ViewResourceCenter())
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('resource_centre.legislation.create') }}" class="btn btn-default" style="color: white; background-color: #173175; border-color: #173175;"><i class="fa fa-comment-o"></i> Start New Conversation</a>
                </div>
            </div>

            <hr>

            <div class="row mix-grid">
                <div class="col-md-12">
                    @if(count($tickets->results))
                        @foreach($tickets->results as $ticket)
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    {{ ucwords($ticket->subject) }}
                                    <div class="pull-right">
                                        @foreach($ticket->tags as $tag)
                                            <span class="label label-default" style="margin-left: 10px">
                                                {{ ucwords(str_replace('_', ' ', $tag)) }}
                                            </span>
                                        @endforeach
                                        <span style="margin-left: 10px" class="label label-primary"><a href="{{ route('resource_centre.legislation.show', $ticket->id) }}" style="color: white!important;">Read More <i class="fa fa-arrow-right"></i> </a></span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">There are no conversations available.</div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@else
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="border-box text-center" style="background-color: #fbfbfb; min-height:300px">
                        <i class="fa fa-lock" style="font-size: 12vh"></i>
                        <p>You do not have access to this page. <br> The Technical Resource Centre is part of the Designation CPD Subscription Packages.</p>
                        <a class="btn btn-primary" href="{{ route('resource_centre.home') }}"><i class="fa fa-arrow-left"></i> Back</a>
                        <a class="btn btn-default" href="/subscription_plans"><i class="fa fa-arrow-right"></i> View Packages</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif


@endsection