

@extends('admin.layouts.master')

@section('title', 'New Presenter')
@section('description', 'Please ensure that you fill all the fields')
@section('content')
    <br>
    <div class="row">
        <div class="panel-white col-sm-12">
            <br>
            {!! Form::open(['method' => 'post', 'route' => 'admin.presenters.store', 'files' => true]) !!}
                @include('admin.presenters.includes.form', ['submit' => 'Create Presenter'])
            {!! Form::close() !!}
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

        $('.description').summernote({
            height: 200,
            toolbar: [
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph', 'style']],
                ['Insert', ['hr']],
                ['Misc', ['codeview', 'fullscreen']]
            ]
        });
    </script>
@stop