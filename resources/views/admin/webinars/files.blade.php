@extends('admin.layouts.master')

@section('title', 'Webinars')
@section('description', 'View All Webinar Files')

@section('content')
    <section>
        <br>
        <div class="container">
            @foreach($events as $event)
                <div class="panel-group">
                    <div class="panel panel-default">
                        <div class="panel-heading" data-toggle="collapse" href="#{{ $event->id }}">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" href="#{{ $event->id }}"><i class="fa fa-ticket"></i> {{ $event->name }}</a>
                                <div class="label label-success pull-right">Files: {{ count($event->links) }}</div>
                            </h4>
                        </div>
                        <div id="{{ $event->id }}" class="collapse">

                            <div class="panel-body">
                            @if(count($event->links))
                                <table class="table">
                                   <thead>
                                   <th>Name</th>
                                   <th>URL</th>
                                   <th>Instructions</th>
                                   </thead>
                                    <tbody>
                                        @foreach($event->links as $link)
                                            <tr>
                                                <td>{{ $link->name }}</td>
                                                <td><a target="_blank" href="{{$link->url}}">{{ $link->url }}</a></td>
                                                <td>{{ ($link->instructions)? : "None" }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>There is no files for {{ $event->name }}</p>
                            @endif

                            @if($event->pricings->contains('name', 'Online admission'))
                            <hr>
                            <table class="table">
                                <thead>
                                    <th>Title</th>
                                    <th>Reference</th>
                                    <th>Download Link</th>
                                </thead>
                                <tbody>
                                @if(count($event->pricings))
                                    @foreach($event->pricings as $pricing)
                                        @foreach($pricing->recordings as $recording)
                                            <tr>
                                                <td>{{ $recording->video->title }}</td>
                                                <td>{{ $recording->video->reference }}</td>
                                                <td><a target="_blank" href="{{ $recording->video->download_link }}">{{ $recording->video->download_link }}</a></td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            @endif

                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            {!! $events->render() !!}
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