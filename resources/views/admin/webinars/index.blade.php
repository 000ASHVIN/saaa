@extends('admin.layouts.master')

@section('title', 'Webinars')
@section('description', 'View All Webinar Links')

@section('content')
    <section>
        <div class="container">
            <table class="table">
                <thead>
                    <th>Name</th>
                    <th>Link</th>
                    <th>Password</th>
                </thead>
                <tbody>
                @foreach($webinars as $webinar)
                <tr>
                    <td>{{ ($webinar->pricing ? $webinar->pricing->event->name : $webinar->id ." Event pricing has been removed")  }}</td>
                    <td><a target="_blank" href="{{ ($webinar->url ? : "#") }}">{{ ($webinar->url ? : "#") }}</a></td>
                    <td>{{ ($webinar->passcode) ? : "No Password" }}</td>
                </tr>
                @endforeach
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
    </script>
@stop