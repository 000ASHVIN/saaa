@extends('admin.layouts.master')

@section('title', 'Event Venue Management')
@section('description', 'This will allow Administrators to close the Event Venues')

@section('content')
    <br>
    @foreach($events as $event)
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading"><a data-toggle="collapse" href="#{{$event->id}}"><i class="fa fa-ticket"></i> {{ $event->name }}</a></div>
                <div id="{{$event->id}}" class="collapse">
                    @foreach($event->venues as $venue)
                        <br>
                        {!! Form::model($venue, ['Method' => 'post', 'route' => ['event_venues_close', $venue->id], 'class' => 'form-inline', 'id' => 'event'.$event->id]) !!}
                        <div class="row">
                            <div class="col-md-12">{!! form::label('is_active', $venue->name ) !!}</div>
                            <div class="col-md-12">
                                <div class="form-group" style="width: 92% !important;">
                                    {!! Form::select('is_active', [
                                        '1' => "Venue is active",
                                        '0' => 'inactive'
                                    ], null, ['class' => 'form-control', 'style' => 'width:100%']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::submit('Update', ['class' => 'update btn btn-danger']) !!}
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
   <div class="col-md-8 text-right">
       {!! $events->render() !!}
   </div>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop