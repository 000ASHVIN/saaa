@extends('admin.layouts.master')

@section('title', 'Create New Professional Body')
@section('description', 'Create New')

@section('content')
    <section>
        <br>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
           <div class="row">
               {!! Form::open(['method' => 'post', 'route' => 'admin.professional_bodies.store']) !!}
                    @include('admin.professional_bodies.includes.form', ['button' => 'Create'])
               {!! Form::close() !!}
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
        $('.select2').select2();
    </script>
@stop