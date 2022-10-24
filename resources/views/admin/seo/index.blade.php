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
    {!! Form::open(['method' => 'get', 'route' => 'seoSearchNew']) !!}

    <input type="hidden" name="tableName" id="tableName"  value="{{ $name }}">
    <div class="form-group @if ($errors->has('event_name')) has-error @endif">
        {!! Form::label('name', 'Searching') !!}
        {!! Form::input('text', 'name', null, ['class' => 'form-control', 'placeholder' => 'Search']) !!}
        @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
    </div>

    <button class="btn btn-primary" onclick="spin(this)"><i class="fa fa-search"></i> Search</button>
    {!! Form::close() !!}
    <div class="pull-right">
        <a class="btn btn-primary" href="{{ route('admin.seo_data.create') }}"><i class="fa fa-search"></i> Add New SEO</a>
    </div>
    <br>
            <hr>
           
            <div class="row">
                <div class="col-md-12">
                         <div class="tabbable">
                            @if(!isset($type))
                            <ul class="nav nav-tabs" id="navigation-tabs">
                                @foreach($array as $k=>$value)
                                <li @if($name == $k) class="active" @endif><a  href="{{route('seo',$k)}}">{{ucFirst($k)}} </a></li>
                                @endforeach
                               
                            </ul>
                            @endif
                            <div class="tab-content">
                                @include('admin.seo.list')
                            </div>
                        </div>
                </div>
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