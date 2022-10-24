@extends('admin.layouts.master')

@section('title', 'Synced Events')
@section('description', 'All synced events.')

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            {!! Form::open(['method' => 'post', 'route' => 'admin.event.search']) !!}
            <div class="form-group @if ($errors->has('event_name')) has-error @endif">
                {!! Form::label('event_name', 'Search Events') !!}
                {!! Form::input('text', 'event_name', null, ['class' => 'form-control', 'placeholder' => 'Ethics Independence and NOCLAR']) !!}
                @if ($errors->has('event_name')) <p class="help-block">{{ $errors->first('event_name') }}</p> @endif
            </div>

            <button class="btn btn-primary" onclick="spin(this)"><i class="fa fa-search"></i> Search</button>
            {!! Form::close() !!}
            <hr>
            
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Notifications</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @foreach ($events as $event)
                <tr>
                    <td><a class="label label-success" href="/admin/events/{{$event->id}}">{{$event->type}}</a></td>
                    <td><a href="/admin/events/{{$event->id}}">{{$event->name}}</a></td>
                    <td>{{$event->start_date}}</td>
                    <td>{{$event->end_date}}</td>
                    <td>
                        @if ($event->notifications != null)
                            <span>{{$event->notifications->status_text}}<br/> {{$event->notifications->schedule_date}}</span>
                        @else
                            <span>Not Scheduled</span>
                        @endif
                    </td>
                    <td class="text-center"> {{ count($event->tickets) }}</td>
                    <td>
                        <div class="btn-group">
                            <a href="/admin/synced_event/show/{{ $event->slug }}" class="btn btn-primary dropdown-toggle btn-sm">
                                Edit
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>

            <div class="text-center">
                @if(count($events))
                    {!! $events->render() !!}
                @endif
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

        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
    </script>

@stop
