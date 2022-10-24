@extends('app')

@section('title')
    My Webinars on Demand
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('dashboard.my_webinars') !!}
@stop

@section('styles')
    <style type="text/css">
        .bootstrap-dialog-message form, .bootstrap-dialog-message .form-group {
            margin-bottom: 0;
        }
        .event-container-inner {
            max-width: 100%;
        }

        /* .my-webinars-btn-container {
            position: relative;
            top: 15px;
        } */

        .new-events-view {
            min-height: 0px;
        }

        .btn-container {
            line-height:100px;
            /* text-align: center;
            display: inline-block;
            position: absolute;
            bottom: 50%; */
        }

        .browse-webinar-button-container {
            text-align: right;
            display: inline-block;
        }
        @media only screen and (max-width:768px){ 
            section div.row>div{margin-bottom:0px;}

            .browse-webinar-button-container .btn {
                margin-top:10px;
            }

            .btn-container {
                line-height:55px;
            }

        }

         /* Filter Tab CSS*/
        .events-tab {
            list-style-type: none;
            padding: 0px;
            margin-bottom: 0px;
        }

        ul li.tablist {
            display: inline-block;
            margin-right: 4px;
        }

        ul li.tablist a {
            padding-left:20px;
            padding-right:20px;
        }

        ul li.tablist.active a {
            background-color: #8cc03c;
            color: #ffffff;
            border: #8cc03c solid 1px;
        }
        /*End Filter tabs CSS*/
    </style>
@endsection

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')
            <div class="col-lg-9 col-md-9 col-sm-8">
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::open(['method' => 'get', 'route' => 'dashboard.webinars_on_demand.index']) !!} 
                            @include('webinars_on_demand.includes.search_webinar')
                        {!! Form::close() !!}
                    </div>
                </div>
                <hr>
                <div class="col-lg-12 col-md-12 col-sm-12" id="myWebinars">
                    <ul class="nav nav-tabs nav-top-border">
                        @if($is_practice_package)
                            @foreach ($practice_package_tabs as $tab)
                                <li class="{{ $activeTab=='tab_'.$tab->id?'active':'' }}"><a data-id="{{ 'tab_'.$tab->id }}" href="#{{ 'tab_'.$tab->id }}" data-toggle="tab">{{ $tab->name }}</a></li>
                            @endforeach
                        @else

                            <li class="{{ $activeTab=='my_webinars'?'active':'' }}"><a data-id="my_webinars" href="#my_webinar" data-toggle="tab">Purchased</a></li>
                            <li class = "{{ $activeTab=='free_wods'?'active':'' }}"><a data-id="free_wods" href="#free_wods" data-toggle="tab">Free</a></li>
                            <li class = "{{ $activeTab=='browse_webinars'?'active':'' }}"><a data-id="browse_webinars" href="#browse_webinar" data-toggle="tab">Browse WODs</a></li>

                        @endif

                        <li class = "{{ $activeTab=='webinar_series'?'active':'' }}"><a data-id="webinar_series" href="#webinar_series" data-toggle="tab">Series</a></li>

                    </ul>

                    <div class="tab-content">
                        @if($is_practice_package)

                            @foreach ($practice_package_tabs as $tab)
                            <div class="tab-pane fade in {{ $activeTab=='tab_'.$tab->id?'active':'' }}" id="{{ 'tab_'.$tab->id }}">
                                <?php
                                    $browse_videos = $tab->tab_videos;
                                ?>
                                @include('dashboard.includes.browse_webinars_tickets')
                            </div>
                            @endforeach

                        @else

                            <div class="tab-pane fade in {{ $activeTab=='my_webinars'?'active':'' }}" id="my_webinar">
                                @include('dashboard.includes.my_webinars_tickets')
                            </div>

                            <div class="tab-pane fade in {{ $activeTab=='browse_webinars'?'active':'' }}" id="browse_webinar">

                                <ul class="events-tab">
                                    <li class="tablist {{ $browseactiveTab=='paid_browse_videos'?'active':'' }}"><a data-id="paid_browse_videos" href="#paid_browse_videos" data-toggle="tab" class="btn btn-default">Paid</a></li>
                                    <li class = "tablist {{ $browseactiveTab=='free_browse_videos'?'active':'' }}"><a data-id="free_browse_videos" href="#free_browse_videos" data-toggle="tab" class="btn btn-default">Free</a></li>
                                </ul>
                            
                                <div class="tab-content">
                                    
                                    <?php
                                        $browse_videos = $paid_browse_videos;
                                    ?>
                                    <div class="tab-pane fade in {{ $browseactiveTab=='paid_browse_videos'?'active':'' }}" id="paid_browse_videos">
                                        
                                        @include('dashboard.includes.browse_webinars_tickets')
                                    </div>
                                    
                                    <?php
                                        $browse_videos = $free_browse_videos;
                                    ?>
                                    <div class="tab-pane fade in {{ $browseactiveTab=='free_browse_videos'?'active':'' }}" id="free_browse_videos">
                                        @include('dashboard.includes.browse_webinars_tickets')
                                    </div>
                                </div>
                            </div>

                            <?php
                                $browse_videos = $free_wods;
                            ?>
                            <div class="tab-pane fade in {{ $activeTab=='free_wods'?'active':'' }}" id="free_wods">
                                @include('dashboard.includes.browse_webinars_tickets')
                            </div>

                        @endif
                        
                        <?php
                            $browse_videos = $webinar_series;
                        ?>
                        <div class="tab-pane fade in {{ $activeTab=='webinar_series'?'active':'' }}" id="webinar_series">
                            @include('dashboard.includes.browse_webinars_tickets')
                        </div>

                    </div>
                </div>
        </div>
    </div>
</section>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
    jQuery(document).ready(function () {
        var activeTabName = $(".nav.nav-tabs.nav-top-border li.active a").attr("data-id");
        $('#webinars_on_demand').val(activeTabName);
        $(document).on('click','ul li',function(){
            var activeTabName = $(".nav.nav-tabs.nav-top-border li.active a").attr("data-id");
            $('#webinars_on_demand').val(activeTabName);
        });

        var activeTabName = $(".events-tab li.active a").attr("data-id");
        $('#browse_free_paid').val(activeTabName);
        $(document).on('click','ul li.tablist',function(){
            var activeTabName = $(".events-tab li.active a").attr("data-id");
            $('#browse_free_paid').val(activeTabName);
        });
    });
    </script>
    <script>
        jQuery(document).ready(function () {
          $('#sub_cat').hide();
          $('#sub_sub_cat').hide();
            $(document).on('change','#category',function(){
                $('#sub_sub_cat').hide();
                $("#sub_sub_category").empty();
                // $('#sub_cat').hide();
                var category = $(this).val();
                var category_text = $('#category option:selected').html();
                if (category) {
                   
                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: '{{ route('webinars_on_demand.video.category') }}',
                        data: {'category':category, 'is_dashboard': 1, '_token':'{{ csrf_token() }}'},
                        async: false,
                        error: function (xhr, settings, exception) {
                            alert('The update server could not be contacted.');
                        },
                        success: function (res) {
                            if (res) {
                               $('#sub_cat').show();

                                $("#sub_category").empty();
                                $("#sub_category").append('<option value="">Select</option>');
                             
                                $.each(res, function (key, value) {
                                    $("#sub_category").append('<option value="' + value.id + '">' + value.title + '</option>');
                                });

                                if(category_text == "Please Select.."){
                                    $('#sub_cat').hide();
                                    $('#sub_sub_cat').hide();
                                    $("#sub_category").empty();
                                    $("#sub_category").append('<option value="">Select</option>');
                                }
                                
                                $("#sub_category").val('');

                            } else {
                                $("#sub_category").empty();
                                $('#sub_category').append(`<option value=""> 
                                    Select
                                </option>`);
                                $('#sub_cat').hide();
                                $('#sub_sub_cat').hide();

                            }
                        }
                    });
                } else {
                    $("#sub_category").empty();
                }
            });
            
            @if($category)
                $('#category').trigger('change');
            @endif

            @if($sub_category)
                $("#sub_category").val('{{$sub_category}}');
            @endif

            @if($category == "null") 
            $('#sub_cat').hide();
                $("#sub_category").empty();
                $("#sub_category").append('<option value="">Select</option>');
            @endif

            // $('#sub_category').trigger('change');

        });
    </script>

<script>
        jQuery(document).ready(function () {
          $('#sub_sub_cat').hide();
            $(document).on('change','#sub_category',function(){
                var sub_category = $(this).val();
                if(sub_category == '') {
                    $('#sub_sub_cat').hide();
                }
                if (sub_category) {
                    $.ajax({
                        type: "POST",
                        cache: false,
                        async: false,
                        url: '{{ route('webinars_on_demand.video.category') }}',
                        data: {'category':sub_category, 'is_dashboard': 1, '_token':'{{ csrf_token() }}'},
                        error: function (xhr, settings, exception) {
                            alert('The update server could not be contacted.');
                        },
                        success: function (res) {
                            if (res) {
                               $('#sub_sub_cat').show();

                                $("#sub_sub_category").empty();
                                $("#sub_sub_category").append('<option value="">Select</option>');
                                
                                $.each(res, function (key, value) {
                                    $("#sub_sub_category").append('<option value="' + value.id + '">' + value.title + '</option>');
                                });

                            } else {
                                $("#sub_sub_category").empty();
                                $('#sub_sub_cat').hide();
                            }
                        }
                    });
                } else {
                    $("#sub_sub_category").empty();
                }
            });

            @if($sub_category)
                $('#sub_category').trigger('change');
            @endif

            @if($sub_sub_category)
                $("#sub_sub_category").val('{{$sub_sub_category}}');
            @endif

        });
    </script>
    <script>
        jQuery(document).ready(function () {
            $('.wod_video_play').click(function(){
                var video_id = $(this).closest('.row').find('#video_id').val();
                $.ajax({
                            type: "POST",
                            // cache: false,
                            // async: false,
                            url: '{{ route('webinars_on_demand.play') }}',
                            data:  {'_token':'{{ csrf_token() }}', 'video_id':video_id },
                            error: function (xhr, settings, exception) {
                                alert('The update server could not be contacted.');
                            },
                            success: function (res) {
                            //    console.log(res);
                            }
                        });
            });
        });
        </script>
@stop

