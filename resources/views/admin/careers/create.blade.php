@extends('admin.layouts.master')

@section('title', 'Careers')
@section('description', 'Create a new Job')

@section('content')
    <br>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                {!! Form::open(['Method' => 'STORE', 'route' => ['store']]) !!}
                    @include('admin.careers.includes.form', ['SubmitButton' => 'Create Job'])
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