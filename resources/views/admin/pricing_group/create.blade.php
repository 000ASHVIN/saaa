@extends('admin.layouts.master')

@section('title', 'Pricing Group')
@section('description', 'Create new pricing group')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('admin.pricing_group.index') }}" class="btn btn-success"><i class="fa fa-arrow-left"></i> All Pricing Group</a>
                <hr>
                {!! Form::open(['method' => 'post', 'route' => 'admin.pricing_group.store', 'files' => true]) !!}
                    @include('admin.pricing_group.includes.form', ['submit' => 'Save Pricing Group'])
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@stop

@section('scripts')
    <script>
        $('.select2').select2({
            placeholder: "Please select",
        });
    </script>

    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop