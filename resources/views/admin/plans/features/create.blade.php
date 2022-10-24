@extends('admin.layouts.master')

@section('title', 'Membership Features')
@section('description', 'Create Membership Feature')

@section('content')
    <section>
        <br>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::open(['method' => 'post']) !!}
                        @include('admin.plans.features.form', ['SubmitButton' => 'Create Feature'])
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