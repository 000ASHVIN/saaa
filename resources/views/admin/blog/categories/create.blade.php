@extends('admin.layouts.master')

@section('title', 'Categories')
@section('description', 'Create a new category')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-success"><i class="fa fa-arrow-left"></i> All Categories</a>
                <hr>
                {!! Form::open(['method' => 'post', 'route' => ['admin.categories.create'], 'enctype' => 'multipart/form-data']) !!}
                    @include('admin.blog.categories.includes.form', ['submit' => 'Create Category'])
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            jQuery("#parent_id").on('change', function() {
                check_category_type();
            })

            function check_category_type() {
                if(jQuery('#parent_id').val()!='0') {
                    jQuery("#category_type_wrapper").hide();
                    jQuery("select.category_type").val('');
                }
                else {
                    jQuery("#category_type_wrapper").show();
                }
            }

            $('#remove_category_image').on('click', function(){
                $('#category_image_preview').hide();
                $('#input_remove_image').val('1');
            });
        });
    </script>
@stop