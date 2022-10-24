@extends('admin.layouts.master')

@section('title', 'Create New Industry')
@section('description', 'Create A New Industry')

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::open(['method' => 'post', 'route' => 'admin.industries.store']) !!}
                        @include('admin.industries.partials.form', ['button' => 'Create Email Template'])
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@endsection