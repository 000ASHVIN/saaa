@extends('admin.layouts.master')

@section('title', 'SEO')
@section('description', 'Edit SEO URL')
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
    {!! Form::model($redirect, ['method' => 'Patch', 'route' => ['admin.redirect.update', $redirect->id]]) !!}
    {{ method_field('PUT') }}
    @include('admin.seo.redirect.form', ['button' => 'Create Redirect'])
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