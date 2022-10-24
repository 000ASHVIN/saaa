@extends('admin.layouts.master')

@section('title', 'New Article')
@section('description', 'Create new article')

@section('styles')
    <link href="/assets/admin/assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="/assets/admin/assets/plugins/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('admin.news.index') }}" class="btn btn-success"><i class="fa fa-arrow-left"></i> All Articles</a>
                <hr>
                {!! Form::open(['method' => 'post', 'route' => 'admin.news.store', 'files' => true]) !!}
                    @include('admin.blog.articles.includes.form', ['submit' => 'Save Article'])
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@stop

@section('scripts')
<script src="/assets/admin/assets/plugins/clockpicker/dist/jquery-clockpicker.min.js"></script>
<script src="/assets/admin/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <script>
        $('.select2').select2({
            placeholder: "Please select",
        });
        $('.timepicker').clockpicker();
        $('.timepicker').val('08:00');
    </script>

    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop