@extends('admin.layouts.master')

@section('title', 'SEO')
@section('description', 'All SEO')

@section('styles')
    <link href="/assets/admin/assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="/assets/admin/assets/plugins/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
    <style>
        .daterangepicker{
            z-index: 10000 !important;
        }
        .select2-container--open{
            z-index:9999999
        }
        .popover.clockpicker-popover.bottom.clockpicker-align-left {
            z-index: 999999;
        }
    </style>
@endsection

@section('content')

<div class="container-fluid container-fullw padding-bottom-10 bg-white">
   <div class="row">
   {!! Form::model($data, ['method' => 'post', 'route' => ['seoUpdate', $data->id, $tableName]]) !!}
   <div class="col-md-12">
    <div class="form-group ">
        {!! Form::label('keyword', 'URL') !!}
        @if($name == 'videos')
        <a href="{{route('webinars_on_demand.show',$data->slug)}}" target="_blank">{{route('webinars_on_demand.show',$data->slug)}}</a>
        @elseif($name == 'courses')
        <a href="{{route('courses.show',$data->slug)}}" target="_blank">{{route('courses.show',$data->slug)}}</a>
        @elseif($name == 'event')
        <a href="{{route('events.show',$data->slug)}}" target="_blank">{{route('events.show',$data->slug)}}</a>
        @elseif($name == 'presenters')
        <a href="{{route('presenters.show',$data->slug)}}" target="_blank">{{route('presenters.show',$data->slug)}}</a>
        @elseif($name == 'posts')
        <a href="{{route('news.show',$data->slug)}}" target="_blank">{{route('news.show',$data->slug)}}</a>
        @endif
       
    </div>
</div>
            
            <div class="col-md-12">
                <div class="form-group @if ($errors->has('keyword')) has-error @endif">
                    {!! Form::label('keyword', 'Title') !!}
                    {!! Form::input('text', 'keyword', $data->checkMetaTitle(), ['class' => 'form-control']) !!}
                    @if ($errors->has('keyword')) <p class="help-block">{{ $errors->first('keyword') }}</p> @endif
                </div>
            </div>


            <div class="col-md-12">
                <div class="form-group @if ($errors->has('meta_description')) has-error @endif">
                    {!! Form::label('meta_description', 'Meta Description') !!}
                    {!! Form::textarea('meta_description', null, ['class' => 'form-control']) !!}
                    @if ($errors->has('meta_description')) <p class="help-block">{{ $errors->first('meta_description') }}</p> @endif
                </div>
            </div>
            
            <div class="col-md-12">
                <div class="form-group">
                    {!! Form::submit('Edit SEO', ['class' => 'btn btn-success']) !!}
                </div>
            </div>

       {!! Form::close() !!}
   </div>
</div>
@endsection

@section('scripts')
    <script src="/assets/admin/assets/plugins/clockpicker/dist/jquery-clockpicker.min.js"></script>
    <script src="/assets/admin/assets/plugins/timepicker/bootstrap-timepicker.min.js"></script>
    <script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>

    <script src="/assets/themes/saaa/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
    
@endsection