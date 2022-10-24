@extends('admin.layouts.master')

@section('title', 'Video')
@section('description', 'Edit')

@section('styles')
    <style>
        .select2-container--open{
            z-index:9999999
        }
        #event_id {
            width:100%;
        }
    </style>
@endsection

@section('content')
    <div class="modal fade" id="add-event-to-venue-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Add recording</h4>
                </div>
                <div class="modal-body">
                    @include('admin.videos.includes.modals.add-to-event-venue',['video' => $video, 'events' => $events])
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default close-modal" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary submit-modal">Add recording</button>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="tabbable">
        <ul class="nav nav-tabs" id="navigation-tabs">
            <li class="active"><a data-toggle="tab" href="#overview">Details</a></li>
            <li class=""><a data-toggle="tab" href="#recordings">Recordings</a></li>
            {{-- <li class=""><a data-toggle="tab" href="#cpds">CPDs</a></li> --}}
            <li class=""><a data-toggle="tab" href="#assessment">Assessments</a></li>
            <li class=""><a data-toggle="tab" href="#files">Files</a></li>
        </ul>

        <div class="tab-content">            
            @include('admin.videos.tabs.overview',compact('edit'))
            @include('admin.videos.tabs.recordings',compact('edit'))
            {{-- @include('admin.videos.tabs.cpds',compact('edit')) --}}
            @include('admin.videos.tabs.assessments',compact('edit'))
            @include('admin.videos.tabs.files',compact('edit'))
        </div>

    </div>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        window.addEventListener('load', function(){

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
        });
    </script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/css/bootstrap-dialog.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>
    <script src="//cdn.ckeditor.com/4.6.2/full/ckeditor.js"></script>
    <script>
        $(function () {
            $('#add-to-event-venue-button').click(function () {
                $('#add-event-to-venue-modal').modal('show');
            });
            $('.submit-modal').click(function () {
                $('#add-to-event-venue-form').submit();
            });
            $('.close-modal').click(function () {
                $('#add-event-to-venue-modal').modal('hide');
            });
        });
    </script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            $('#navigation-tabs a[href="#{{old('tab')}}"]').tab('show');
            new Clipboard('#copy-button');
        });
    </script>
    <script type="text/javascript">
        $('.select2').select2({
            placeholder: "Please select",
        });

        //fix modal force focus
        $.fn.modal.Constructor.prototype.enforceFocus = function () {
            var that = this;
            $(document).on('focusin.modal', function (e) {
                if ($(e.target).hasClass('select2')) {
                    return true;
                }

                if (that.$element[0] !== e.target && !that.$element.has(e.target).length) {
                    that.$element.focus();
                }
            });
        };
    </script>
@stop