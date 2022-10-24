@extends('app')


@section('meta_tags')
    <title> Past Webinars & Events | {{ config('app.name') }}</title>
@endsection

@section('title')
    Events / Conferences
@stop

@section('intro')
    View our past events and recordings
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('events') !!}
@stop

@section('content')
    <section class="alternate" style="background-image: url('/assets/frontend/images/demo/wall2.jpg');">
        <div class="container">
            @include('events.includes.search_form', ['select' => 'past'])

            <div class="row">
                <div class="col-md-12">
                    @if(count($events))
                        @foreach($events as $event)
                            @include('events.includes.event_single_block')
                        @endforeach
                        {!! $events->render() !!}
                    @else
                        <div class="event-container-box clearfix">
                            <h4>No Events was found</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@stop
