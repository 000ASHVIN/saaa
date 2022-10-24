@extends('admin.layouts.master')

@section('title', 'Events')
@section('description', 'All events.')

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
                    <th style="text-align: left">Start Date</th>
                    <th style="text-align: left">End Date</th>
                    <th class="text-center">Total Registrations</th>
                    <th>Tools</th>
                </tr>
                </thead>
                <tbody>
                @if(count($events))
                    @foreach($events as $event)
                        <tr>
                            <td><a class="label label-success" href="/admin/events/@{{ event.id }}">{{ ucfirst($event->type) }}</a></td>
                            <td><a href="/admin/events/@{{ event.id }}">{{ $event->name }}</a></td>
                            <td style="text-align: left">{{ date_format($event->start_date, 'd M Y') }}</td>
                            <td style="text-align: left">{{ date_format($event->end_date, 'd M Y') }}</td>
                            <td class="text-center">{{ count($event->tickets) }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle btn-sm" aria-expanded="true">
                                        Tools <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <a href="/admin/event/show/{{ $event->slug }}">
                                                Edit event
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/admin/events/{{ $event->id }}">
                                                Event stats
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/admin/event/export/{{ $event->slug }}">
                                                Export stats
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/admin/event/upload/{{ $event->slug }}/sendinBlue">
                                                Upload (SendinBlue)
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/admin/event/publish/{{ $event->slug }}/store">Publish to Store</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6">:( Sorry, No Events found.</td>
                    </tr>
                @endif
                </tbody>
            </table>
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