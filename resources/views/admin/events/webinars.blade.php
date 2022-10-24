@extends('admin.layouts.master')

@section('title', 'Webinars')
@section('description', 'All users that downloaded Webinars'.' '.count(\App\DownloadWebinars::all()))

@section('content')
    <br>
    <div class="container">
        <div class="row">
            <table class="table">
                <thead>
                    <th>User</th>
                    <th>Webinar Video</th>
                    <th>Downloaded</th>
                </thead>
                <tbody>
                @if(count($webinars))
                    @foreach($webinars as $webinar)
                        <tr>
                            <td>{{ \App\Users\User::find($webinar->user_id)->first_name }} {{ \App\Users\User::find($webinar->user_id)->last_name }}</td>
                            <td>{{ $webinar->webinar_title }}</td>
                            <td>{{ $webinar->created_at }}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            {!! $webinars->render() !!}
        </div>
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