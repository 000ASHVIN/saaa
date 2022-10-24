@extends('admin.layouts.master')

@section('title', 'New Author')
@section('description', 'Create a new author for news articles')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('admin.authors.index') }}" class="btn btn-success"><i class="fa fa-arrow-left"></i> All Authors</a>
                <hr>
                {!! Form::open() !!}
                    @include('admin.blog.authors.includes.form', ['submit' => 'Create Author'])
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