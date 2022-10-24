@extends('admin.layouts.master')

@section('title', 'Event Notifications')
@section('description', 'Event Notifications')

@section('content')
    <section>
        <br>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('admin.event.notifications.create') }}" class="pull-right btn btn-primary"><i class="fa fa-pencil"></i> Create New</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped">
                        <thead>
                            <th>Event</th>
                            <th>Schedule Date</th>
                            <th>Status</th>
                            <th></th>
                        </thead>
                        <tbody>
                            @if (count($notifications))
                                @foreach($notifications as $notification)
                                    <tr>
                                        <td>{{ $notification->event->name }}</td>
                                        <td>{{ $notification->schedule_date }}</td>
                                        <td>{{ $notification->status_text }}</td>
                                        <td>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['admin.event.notifications.destroy', $notification->id]]) !!}
                                                <button class="btn btn-sm btn-danger"><i class="fa fa-close"></i></button>
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                    <tr>
                                        <td colspan="5">
                                            No notifications scheduled.
                                        </td>
                                    </tr>
                            @endif
                        </tbody>
                    </table>
                    {!! $notifications->render() !!}
                </div>
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop