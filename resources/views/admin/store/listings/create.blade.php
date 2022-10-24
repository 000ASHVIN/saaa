@extends('admin.layouts.master')

@section('title', 'Store')
@section('description', 'Create listing')

@section('content')
    <br>
    @include('admin.store.listings.includes.forms')
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop