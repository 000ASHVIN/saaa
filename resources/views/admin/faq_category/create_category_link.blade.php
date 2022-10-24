@extends('admin.layouts.master')

@section('title', 'Link Category')
@section('description', 'Link FAQ Category')

@section('content')
    <section>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::open(['method' => 'post']) !!}
                        @include('admin.faq_category.includes.form', ['button' => 'Link Category'])
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
    <script src="/admin/assets/js/index.js"></script>
@endsection