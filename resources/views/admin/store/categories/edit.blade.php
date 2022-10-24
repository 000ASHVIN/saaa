@extends('admin.layouts.master')

@section('title', 'Store')
@section('description', 'Category: '.$category->title)

@section('content')
    <section>
        <br>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::model($category, ['method' => 'PUT', 'route' => ['admin.store_categories.update', $category->id]]) !!}
                        @include('admin.store.categories.partials.form', ['button' => 'Update'])
                    {!! Form::close() !!}
                </div>
            </div>
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