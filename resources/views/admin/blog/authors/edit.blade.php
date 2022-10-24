@extends('admin.layouts.master')

@section('title', 'Update Author')
@section('description', 'Edit author attributes for news articles')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('admin.authors.index') }}" class="btn btn-success"><i class="fa fa-arrow-left"></i> All Authors</a>
                <hr>
                {!! Form::model($author, ['method' => 'post', 'route' => ['admin.authors.update', $author->id]]) !!}
                    @include('admin.blog.authors.includes.form', ['submit' => 'Update Author'])
                {!! Form::close() !!}
            </div>
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