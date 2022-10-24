@extends('admin.layouts.master')

@section('title', 'Careers')
@section('description', 'Job Applications')

@section('content')

@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop