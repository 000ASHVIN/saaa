@extends('admin.layouts.master')

@section('title', 'Recorded Webinars')
@section('description', 'This list is all the recorded webinars on our website')

@section('content')
    <div class="row">
        <div class="panel-white col-md-12">
            <br>
            <table class="table table-striped table-responsive">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Time</th>
                        <th>Date</th>
                        <th>Webinar Url</th>
                        <th>Tools</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($webinars))
                        @foreach($webinars as $webinar)
                            <tr>
                                <td>{{ $webinar->title }}</td>
                                <td><i class="fa fa-clock-o"></i> {{ $webinar->time }}</td>
                                <td><i class="fa fa-calendar"></i> {{ $webinar->date }}</td>
                                <td>
                                    <div class="form-inline">
                                        <input id="webinar_{{$webinar->id}}" style="width: 60%;" class="form-control" value="{!!  route('dashboard.webinars.watch', $webinar->slug) !!}">
                                        <button class="btn btn-success" id="copy-button" data-clipboard-target="#webinar_{{$webinar->id}}">Copy</button>
                                        <a href="{{ route('dashboard.webinars.watch', $webinar->slug) }}" target="_blank" class="btn btn-info">View</a>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="#" data-toggle="dropdown" class="btn btn-info dropdown-toggle btn-sm" aria-expanded="true">
                                            Tools <span class="caret"></span>
                                        </a>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a href="{{ route('admin.live_webinars.edit', $webinar->slug) }}">
                                                    Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a data-confirm-content="Are you sure you want to delete the selected webinar? Once deleted this webinar cannot be recovered" href="{{ route('admin.live_webinars.destroy', $webinar->slug) }}">
                                                    Remove
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5">You have not created any webinars, Why dont you create your first webinar now?</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <a href="{{ route('admin.live_webinars.create') }}" class="btn btn-primary">Create Webinar</a>
            <br>
            <br>
        </div>
    </div>
@stop
@section('scripts')
    <script src="/assets/admin/assets/js/index.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>

    <script>
        $(document).ready(function(){
            new Clipboard('#copy-button');
        })
    </script>
@stop