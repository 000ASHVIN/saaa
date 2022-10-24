@extends('admin.layouts.master')

@section('title', 'Webinar Series')
@section('description', 'Edit')

@section('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .sortable-handle {
            cursor: move;
        }
        .modal-content .select2-container {
            z-index: 99999;
        }
    </style>
@endsection

@section('content')
    <div class="modal fade" id="add-webinar-to-series-modal" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add Webinar</h4>
                </div>
                <div class="modal-body">
                    {!! Former::open()->route('admin.webinar_series.assign_webinar',$webinar_series->id)->id('add-webinar-to-series-form')->method('POST') !!}
                        <h3>Webinar</h3>
                        <label for="event_id">
                            <select name="webinar_id" id="webinar_id" class="form-control select2">
                                @foreach ($videos as $video)
                                    <option value="{{ $video->id }}">{{ $video->title }}</option>  
                                @endforeach
                            </select>
                        </label>
                        <br><br>
                    {!! Former::close() !!}
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default close-modal" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary submit-modal">Add Webinar</button>
                </div>
            </div>
        </div>
    </div>
    <br>
    @include('admin.webinar_series.includes.forms',['edit' => true, 'webinar_series' => $webinar_series, 'videos' => $videos]);
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css"></link>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>
    <script>
        var select2_initialized = false;
        $(function () {
            $('#add-webinar-to-series-button').click(function () {
                $('#add-webinar-to-series-modal').modal('show');
                if(!select2_initialized) {
                    setTimeout(function() {
                        $('.select2').select2();
                    }, 500);
                }
            });
            $('.submit-modal').click(function () {
                $('#add-webinar-to-series-form').submit();
            });
            $('.close-modal').click(function () {
                $('#add-webinar-to-series-modal').modal('hide');
            });
        });
    </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        function removeWebinar(webinar_id) {
            $("#"+webinar_id).remove();
            changePosition(true);
        }
        function changePosition(deleteWebinar=false){
            var data = {};
            $.map($(".sortable").find('li.list-group-item'), function(el) {
                var obj = {};
                obj.sequence = $(el).index()+1;
                data[$(el).data('id')]=(obj);
            });
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                'url': '{{ route("admin.webinar_series.set_webinar_sequence",$webinar_series->id) }}',
                'type': 'POST',
                'data': { webinars: data },
                'success': function(data) {
                    if (data.success) {
                        swal({
                            title: "Success!",
                            text: "Sequnce updated!",
                            showConfirmButton: false,
                            timer: 1000,
                            type: "success"
                        })
                        if($(".sortable").find('li.list-group-item').length==0) {
                            $(".sortable").remove();
                            $(".webinar_list").append("<p>This series does not have any webinars.</p>");
                        }
                        if(deleteWebinar) {
                            setTimeout(function(){
                                window.location.reload();
                            },1000);
                        }
                    } else {
                        console.error(data.errors);
                    }
                },
                'error': function(){
                    console.error('Something wrong!');
                }
            });
        };
        $(document).ready(function(){
            var $sortableTable = $('.sortable');
            if ($sortableTable.length > 0) {
                $sortableTable.sortable({
                    handle: '.sortable-handle',
                    axis: 'y',
                    update: function(e, ui) {
                        changePosition();
                    },
                    cursor: "move"
                });
            }
        });
    </script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            setPriceboxes();
            setSalesPrice();

            $('.select2').select2({
                placeholder: "Please select",
            });

            jQuery('#fix_price_series').on('change', function(){
                setPriceboxes();
            });

            $('#discount, #originalAmount').on('keyup',function(){
                setSalesPrice();
            });


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
        });

        function setPriceboxes() {
            if(jQuery('#fix_price_series').prop('checked')) {
                jQuery('#originalAmount').prop('readonly', false);
            }
            else {
                jQuery('#originalAmount').prop('readonly', true);
            }
        }

        function setSalesPrice() {
            var originalAmount = $('#originalAmount').val();
            var discount = $('#discount').val();
            var amount = originalAmount - (originalAmount * discount) / 100;
            $('#amount').val(amount);
        }

    </script>
@stop