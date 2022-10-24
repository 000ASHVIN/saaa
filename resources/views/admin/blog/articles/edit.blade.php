@extends('admin.layouts.master')

@section('title', 'Edit Article')
@section('description', 'Edit News Article'.' '.$post->title. '<br/>'.' URL: '.'<a target="_blank" href="'.url().'/news/read/'.$post->slug.'">'.url().'/news/read/'.$post->slug.'</a>' )

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
                {!! Form::model($post, ['method' => 'post', 'route' => ['admin.news.update', $post->slug], 'files' => true]) !!}
                    @include('admin.blog.articles.includes.form', ['submit' => 'Update Article'])
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
        if($('.timepicker').val() == ''){
            $('.timepicker').val('08:00');
        }
    </script>

    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop