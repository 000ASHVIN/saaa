@extends('admin.layouts.master')

@section('title', 'Gallery')
@section('description', 'Edit Folder: '.$folder->title)

@section('content')
    <seection>
        <div class="container-fluid container-fullw bg-white">
            <div class="row">
                {!! Form::model($folder, ['method' => 'PUT', 'route' => ['admin.folders.update', $folder->slug]]) !!}
                    @include('admin.gallery.folders.includes.form', ['button' => 'Update Folder'])
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