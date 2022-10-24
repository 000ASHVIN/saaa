@extends('admin.layouts.master')

@section('title', 'Gallery')
@section('description', 'Create Folder')

@section('content')
    <seection>
        <div class="container-fluid container-fullw bg-white">
            <div class="row">
                {!! Form::open(['method' => 'post', 'route' => 'admin.folders.store']) !!}
                    @include('admin.gallery.folders.includes.form', ['button' => 'Create Folder'])
                {!! Form::close() !!}
            </div>
        </div>
    </seection>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop