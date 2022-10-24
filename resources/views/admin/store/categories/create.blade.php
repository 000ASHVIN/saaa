@extends('admin.layouts.master')

@section('title', 'Store')
@section('description', 'Category: Create New')

@section('content')
    <section>
        <br>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::open(['method' => 'POST', 'route' => 'admin.store_categories.store']) !!}
                        @include('admin.store.categories.partials.form', ['button' => 'Create'])
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