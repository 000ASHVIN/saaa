@extends('admin.layouts.master')

@section('title', 'Webinar Series')
@section('description', 'Add')

@section('css')
    <style>
        .checkbox-wrapper label {
            line-height: 21px;
        }
    </style>
@stop

@section('content')
    <br>
    @include('admin.webinar_series.includes.forms');
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            jQuery('#fix_price_series').on('change', function(){
                setPriceboxes();
            });

            $('.select2').select2({
                placeholder: "Please select",
            });
        });

        jQuery(document).ready(function () {

            @if(isset($category_id) && !empty($category_id))
                $('#category').val('{{ $category_id }}');
            @endif
            @if(isset($sub_category_id) && !empty($sub_category_id))
                $('#sub_category').val('{{ $sub_category_id }}');
                $('#sub_cat').show();
            @endif
            @if(isset($sub_sub_category_id) && !empty($sub_sub_category_id))
                $('#sub_sub_category').val('{{ $sub_sub_category_id }}');
                $('#sub_sub_cat').show();
            @endif

            // Category on change
            $(document).on('change','#category',function(){
                var category = $(this).val();
                if (category) {
                    get_child_categories(category, 'parent');
                }
            });

            // Sub category on change
            $(document).on('change','#sub_category',function(){
                var category = $(this).val();
                if (category) {
                    get_child_categories(category, 'child');
                }
            });

        });

        function get_child_categories(category, type) {

            var options = [];
            var subcategory_wrapper='';
            var subcategory_dropdown='';
            if(type=='parent') {
                subcategory_wrapper='sub_cat';
                subcategory_dropdown='sub_category';
            }
            else if(type=='child') {
                subcategory_wrapper='sub_sub_cat';
                subcategory_dropdown='sub_sub_category';
            }
            $.ajax({
                type: "POST",
                cache: false,
                url: '/webinars_on_demand/category',
                data: {'category':category,'_token':'{{ csrf_token() }}'},
                error: function (xhr, settings, exception) {
                    alert('The update server could not be contacted.');
                },
                success: function (res) {
                    
                    if (res) {
                        $('#'+subcategory_wrapper).show();

                        $("#"+subcategory_dropdown).empty();
                        $("#"+subcategory_dropdown).append('<option value="">Select</option>');
                        
                        $.each(res, function (key, value) {
                            $("#"+subcategory_dropdown).append('<option value="' + key + '">' + value + '</option>');
                        });

                    } else {
                        $("#"+subcategory_dropdown).empty();
                        $('#'+subcategory_wrapper).hide();
                    }
                    if(type=='parent') {
                        $("#sub_sub_category").empty();
                        $('#sub_sub_cat').hide();
                    }
                }
            });
            return options;
        }

        function setPriceboxes() {
            if(jQuery('#fix_price_series').prop('checked')) {
                jQuery('#originalAmount').prop('readonly', false);
            }
            else {
                jQuery('#originalAmount').prop('readonly', true);
            }
        }

    </script>
@stop