@extends('admin.layouts.master')

@section('title', $status->name)
@section('description', 'Lead Status')

@section('content')
    <section>
        <br>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            {!! Form::model($status, ['method' => 'put', 'route' => ['admin.leads.status.update', $status->id]]) !!}
                @include('admin.leads.statuses.form', ['button' => 'Update Status'])
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