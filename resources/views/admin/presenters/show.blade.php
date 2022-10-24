
@extends('admin.layouts.master')

@section('title', $presenter->name)
@section('description', str_limit($presenter->title.' '.$presenter->company, 90))
@section('content')
    <br>
    <div class="row">
        <div class="panel-white col-sm-12">
            <br>
            {!! Form::model($presenter, ['method' => 'Patch', 'route' => ['admin.presenters.update', $presenter->id], 'files' => true]) !!}
                @include('admin.presenters.includes.form', ['submit' => 'Update Presenter'])
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