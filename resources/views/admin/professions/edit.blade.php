@extends('admin.layouts.master')

@section('title', $profession->title)
@section('description', 'Profession')

@section('content')
    <section>
        <br>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            {!! Form::model($profession, ['method' => 'Post', 'route' => ['admin.professions.update', $profession->slug]]) !!}
                @include('admin.professions.includes.form', ['button' => 'Update Profession'])
            {!! Form::close() !!}
        </div>
    </section>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
        $('.select2').select2();
    </script>
@stop