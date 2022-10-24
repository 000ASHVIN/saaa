@extends('admin.layouts.master')

@section('title', 'SEO')
@section('description', 'SEO Create')

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
    {!! Form::open(['method' => 'post', 'route' => 'admin.seo_data.store']) !!}
   
    
    <div class="col-md-12">
        <div class="form-group @if ($errors->has('route')) has-error @endif">
            {!! Form::label('route', 'Route Url') !!}
            {!! Form::input('text', 'route', null, ['class' => 'form-control']) !!}
            @if ($errors->has('route')) <p class="help-block">{{ $errors->first('route') }}</p> @endif
        </div>
    </div>

            <div class="col-md-12">
                <div class="form-group @if ($errors->has('meta_title')) has-error @endif">
                    {!! Form::label('meta_title', 'Title') !!}
                    {!! Form::input('text', 'meta_title', null, ['class' => 'form-control']) !!}
                    @if ($errors->has('meta_title')) <p class="help-block">{{ $errors->first('meta_title') }}</p> @endif
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
                    {!! Form::submit('Add SEO', ['class' => 'btn btn-success']) !!}
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