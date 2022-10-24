@extends('admin.layouts.master')

@section('title', 'Create a new lead status')
@section('description', 'Profession')

@section('content')
    <section>
        <br>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            {!! Form::open(['method' => 'Post', 'route' => ['admin.leads.status.store']]) !!}
                @include('admin.leads.statuses.form', ['button' => 'Create'])
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
    </script>
@stop