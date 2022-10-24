@extends('admin.layouts.master')

@section('title', 'Sales Reps')
@section('description', 'View All Sales Reps')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="col-md-12">
            <div class="row">
                {!! Form::open(['method' => 'Post', 'route' => 'admin.reps.save']) !!}
                @include('admin.reps.includes.form')
                <button type="submit" class="btn btn-primary">Create Sales Rep</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="/admin/assets/js/index.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop