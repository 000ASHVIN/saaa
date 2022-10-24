@extends('app')

@section('meta_tags')
    <title>{{ config('app.name') }} | CPD Provider</title>
    <meta name="description" content="A library of on demand learning videos covering a wide range of accountancy and practice management topics that are always available for viewing.">
    <meta name="Author" content="{{ config('app.name') }}"/>
@endsection

@section('content')

@section('title')
    Webinars On-Demand
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('webinars_on_demand_index') !!}
@stop

@section('styles')
<style>
    /* .ribbon {
        width: 340px;
        position: absolute;
        right: -2px;
        top: -2px;
        z-index: 1;
        top: 28px;
        text-align: right;
    }
    .ribbon .ribbon-inner {
        width: 290px;
        left: -72px;
        top: 90px;
        transform: rotate(-90deg);
    }
     */

.margin_price_hour{
    top: 10px;
}
.max-lines {
    display: block; /* or inline-block */
    text-overflow: ellipsis;
    word-wrap: break-word;
    overflow: hidden;
    max-height: 5.7em;
    line-height: 1.8em;
    }
    .category_rotate{
        background: #8cc03c;;
        transform: rotate(-90deg);
    }
    /* Read More Button CSS */
    .read-more-state {
    display: none;
    }
    .read-more-target {
    opacity: 0;
    max-height: 0;
    font-size: 0;
    transition: .25s ease;
    }
    .read-more-state:checked ~ .read-more-wrap .read-more-target {
    opacity: 1;
    font-size: inherit;
    max-height: 999em;
    }
    .read-more-state ~ .read-more-trigger:before {
    content: 'Read More';
    }
    .read-more-state:checked ~ .read-more-trigger:before {
    content: 'Read Less';
    }
    .read-more-trigger {
    cursor: pointer;
    display: inline-block;
    padding: 0 .5em;
    color: #8cc03c;
    font-size: .9em;
    line-height: 2;
    font-weight: bold;
    font-size: 15px;
    }
    /* End Read More Button CSS */

    .categoryActive, .categoryActive:hover, .categoryActive:focus { 
        background-color: #FFA500;
    }
    .btn-category {
        margin-right: 5px;
        margin-bottom: 7px;
        border-width: 1px;
        height: 30px;
        line-height: 1;
    }
    .btn-category.active, .btn-category.active:hover, .btn-category.active:focus {
        background-color:#8cc03c;
        color: #ffffff;
    }

    section#slider input {
        color:#000000;
        background-color:#FFFFFF;
        box-shadow: 4px 4px 7px #8e8e8e !important;
    }

    .search-header{
        /* background-image: url("/assets/themes/taxfaculty/img/webinars_search.jpg") ; */
        /* background-image: url("/assets/themes/taxfaculty/img/WOD_Header Banner.jpg") ; */
        background-repeat: no-repeat;
        background-position: center center;
        /* background-size: cover; */
        position: relative;
        /* height: 324px; */
        /* text-align: center; */
    }

    section#slider {
        top: 50%;
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
        /* bottom: 25%; */
        left: 15%;
        right: 15%;
        position: absolute;
        background-color:transparent;
    }

    .search-box {
        /* background-color: #8cc03c; */
        border-bottom: 0px;
    }

    .strip {
        text-align: center;
        bottom: 0px;
        position: absolute;
        background-color: #8cc03c;
        color: #FFFFFF;
        width: 100%;
        padding: 10px 0px;
    }
    #slider .btn {
        margin-top: 14px;
    }

    section#slider .btn-default {
        background-color: #8cc03c;
        /* box-shadow: 7px 7px 5px #8cc03c; */
    }

    section#slider .btn-default:hover {
        background-color: #8cc03c;
    }
    .no-webinars {
        margin-top:2%;
    }
    section.alternate {
        padding-top: 0px;
    }
    @media only screen and (max-width:768px){ 
        .search-text {
            margin-bottom: 0px !important;
        }
        .price-wrapper {
            margin-bottom: 0px !important;
        }
        section div.row>div{margin-bottom: 5px;}
    }

    section div.row>div{margin-bottom: 5px;}

    /* }         */
    .card-body {
        padding: 13px;
    }
    .webinar_section .card-body .card-title {
        font-size: 18px;
        color: #8cc03c;
    }
    .webinar_section {
        margin-top: 20px;
    }
    .webinar_section h2 {
        margin-bottom: 0 !important;
    }
    .webinar_section_title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #E0E0E0;
        margin-bottom: 17px;
    }
    .webinar_section_title a {
        color: #3399ff;
        font-weight: 600;
    }
    .webinar_section img {
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
    @media only screen and (min-width:991px) {
        .card_button {
            text-align: center;
        }
        .card_button a:first-child {
            margin-bottom: 5px;
            margin-right: 3px;
        }
        .webinar_section img {
            height: 160px;
        }
    }
    /* @media only screen and (max-width:991px) { */
        .card_button {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
        }
        .card_button a {
            font-size: 13px;
        }
    /* } */
    @media (min-width: 768px) and (max-width: 991px) {
        .webinar_section img {
            height: 210px;
        }
    }
    .card_button .already_owned {
        width:100%;
        text-align:center;
    }
    .read_more a {
        background-color: #009cae;
        width: 100%;
        color: white;
        margin-top: 15px;
        margin-bottom: 15px;
    }
    .card_wod {
        background: #069cad;
        padding: 20px 20px 35px;
        box-shadow: 0px 7px 16px 4px rgb(0 0 0 / 38%);
    }
    .card_wod h3 {
        color: white;
    }
    .card_wod .indicator {
        background: #008e88;
        color: white;
        display: flex;
        position: relative;
    }

    .card_wod .indicator .right_top_arrow {
        /* height: 35px; */
        height: 28px;
        width: 36px;
        /* right: -13px; */
        right: -16px;
        position: absolute;
        border-left: 7px solid #01565b;
        background: #069cad;
        /* top: -13px; */
        top: -12px;
        transform: rotate(-41deg);
    }
    .card_wod .indicator .right_bottom_arrow {
        /* height: 35px; */
        height: 28px;
        width: 36px;
        /* right: -13px; */
        right: -16px;
        position: absolute;
        border-left: 7px solid #01565b;
        background: #069cad;
        /* bottom: -13px; */
        bottom: -11px;
        transform: rotate(41deg);
    }

    @media only screen and (max-width: 991px) {
        .card_wod .indicator p {
            padding: 15px;
            margin-bottom: 0px;
        }
        .card_wod .indicator .right_top_arrow {
            height: 35px;
            right: -13px;
            top: -13px;
        }
        .card_wod .indicator .right_bottom_arrow {
            height: 35px;
            right: -13px;
            bottom: -13px;
        }
    }
    
    @media only screen and (min-width:991px) {
        .card_wod .indicator p {
            padding: 11px;
            margin-bottom: 0px;
            font-size: 12px;
            padding-right: 17px;
        }
    }
    
    .wod_card_image {
        position: relative;
    }
    .overlay_image img {
        width: 100%;
    }
    .overlay_play_icon {
        position: absolute !important;
        width: 100px;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        opacity: 0.8;
    }
    .overlay_play_icon:hover {
        opacity: 1;
    }
    .overlay_play_icon svg {
        font-size: 100px;
        color: white;
        cursor: pointer;
    }
    .category-block {
        padding: 35px 0px;
    }
    @media (max-width: 768px) {
        .category-block {
            padding: 20px 0px;
        }
    }
    @media only screen and (min-width: 768px) {
        .vertical-center {
            /* padding-top: 20px; */
        }
        .card_wod h3 {
            /* height: 72px; */
            overflow: hidden;
            /* line-height: 1.5em; */
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    }
    .card_wod_boxes .category-block img {
        max-width: 100%;
        box-shadow: 0px 7px 16px 4px rgba(0, 0, 0, 0.38);
    }
</style>
@stop


{!! Form::open(['method' => 'Post', 'route' => 'webinars_on_demand.search']) !!}
<div class="search-header">
    <img src="/assets/themes/taxfaculty/img/WOD_Header Banner.jpg" style="width:100%;">
    <section id="slider">
        @include('webinars_on_demand.includes.search-box')   
    </section> 
    <!-- <div class="strip">
        <h4 style="color:#FFFFFF;margin: 0 0 0px 0;">ONLINE. ANYWHERE. ANYTIME</h4>
    </div> -->
</div>
    <section class="alternate" style="background-image: url('/assets/frontend/images/demo/wall2.jpg');">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                </div>
                <div class="col-md-12 intro" style="margin-top: 10px; text-align: center;">
                    <div class="">
                        <h4 style="color: #009cae; text-align: center; font-size: 34px; border-bottom: 2px solid #009cae; line-height: 50px;">ONLINE. ANYWHERE. ANYTIME</h4>
                    </div>
                    <p>Missed a recent webinar? You can catch-up here, our tax topics will bring you up to speed with the latest developments in tax. Topics are put together to develop and maintain your professional competency by technical experts.</p>
                    <p>View the videos on your cell phone, at the office or at home and track your personal skills development goals – right at your fingertips.</p>
                </div>
                <div class="card_wod_boxes">
                   <div class="row" style="margin-left: 0px !important; margin-right: 0px !important;">

                    @if (count($new_releases))
                        <div class="col-sm-6 col-md-3 category-column">
                            <div class="category-block">
                                <a href="#" data-scroll="new_releases_id">
                                    <img src="{{ asset('/assets/themes/taxfaculty/img/wod_new_releases.jpg') }}">
                                    {{-- <div class="card_wod">
                                        <h3 class="vertical-center">New Releases</h3>

                                        <div class="indicator">
                                            <p><strong>View our new releases here</strong></p>
                                            <div class="right_top_arrow right_arrow"></div>
                                            <div class="right_bottom_arrow right_arrow"></div>
                                            <div class="half_square"></div>
                                        </div>
                                    </div> --}}

                                </a>
                            </div>
                        </div>
                    @endif

                    @if (count($best_seller))
                        <div class="col-sm-6 col-md-3 category-column">
                            <div class="category-block">
                                <a href="#" data-scroll="best_seller_id">
                                    <img src="{{ asset('/assets/themes/taxfaculty/img/wod_best_sellers.jpg') }}">
                                    {{-- <div class="card_wod">
                                        <h3 class="vertical-center">Best Sellers</h3>

                                        <div class="indicator">
                                            <p><strong>View our best sellers here</strong></p>
                                            <div class="right_top_arrow right_arrow"></div>
                                            <div class="right_bottom_arrow right_arrow"></div>
                                            <div class="half_square"></div>
                                        </div>
                                    </div> --}}

                                </a>
                            </div>
                        </div>
                    @endif
                   
                    @if (count($webinar_series))
                        <div class="col-sm-6 col-md-3 category-column">
                            <div class="category-block">
                                <a href="#" data-scroll="webinar_series_id">
                                    <img src="{{ asset('/assets/themes/taxfaculty/img/wod_webinar_series.jpg') }}">
                                    {{-- <div class="card_wod">
                                        <h3 class="vertical-center">Webinar Series</h3>

                                        <div class="indicator">
                                            <p><strong>View our webinar series here</strong></p>
                                            <div class="right_top_arrow right_arrow"></div>
                                            <div class="right_bottom_arrow right_arrow"></div>
                                            <div class="half_square"></div>
                                        </div>
                                    </div> --}}

                                </a>
                            </div>
                        </div>
                    @endif

                    @if (count($free_webinar))
                        <div class="col-sm-6 col-md-3 category-column">
                            <div class="category-block">
                                <a href="#" data-scroll="free_webinar_id">
                                    <img src="{{ asset('/assets/themes/taxfaculty/img/wod_free_webinar.jpg') }}">
                                    {{-- <div class="card_wod">
                                        <h3>Free Webinars</h3>

                                        <div class="indicator">
                                            <p><strong>View our free webinars here</strong></p>
                                            <div class="right_top_arrow right_arrow"></div>
                                            <div class="right_bottom_arrow right_arrow"></div>
                                            <div class="half_square"></div>
                                        </div>
                                    </div> --}}

                                </a>
                            </div>
                        </div>
                    @endif

                   </div>
                </div>
                <div class="col-md-12">
                    @include('webinars_on_demand.includes.search')
                </div>
                <div class="search-category">
                    <div class="col-md-12">

                        @if (count($new_releases))
                            <div class="webinar_section new_releases" id="new_releases_id">
                                <div class="webinar_section_title">
                                    <h2 class="title"><strong>New Releases</strong></h2>
                                    <a href="{{ route('webinars_on_demand.webinar_type', 'new_releases') }}" class=""> See More</a>
                                </div>
                                
                                <div class="row">
                                    @foreach($new_releases as $video)
                                    @include('webinars_on_demand.includes.video')
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if (count($best_seller))
                            <div class="webinar_section best_sellers" id="best_seller_id">
                                <div class="webinar_section_title">
                                    <h2 class="title"><strong>Best Sellers</strong></h2>
                                    <a href="{{ route('webinars_on_demand.webinar_type', 'best_seller') }}" class=""> See More</a>
                                </div>
                                <div class="row">
                                    @foreach($best_seller as $key => $video)
                                    @include('webinars_on_demand.includes.video')
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        @if (count($webinar_series))
                            <div class="webinar_section webinar_series" id="webinar_series_id">
                                <div class="webinar_section_title">
                                    <h2 class="title"><strong>Webinar Series</strong></h2>
                                    <a href="{{ route('webinars_on_demand.webinar_type', 'webinar_series') }}" class=""> See More</a>
                                </div>
                                
                                <div class="row">
                                    @foreach($webinar_series as $video)
                                    @include('webinars_on_demand.includes.video')
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if (count($free_webinar))
                            <div class="webinar_section free_webinar" id="free_webinar_id">
                                <div class="webinar_section_title">
                                    <h2 class="title"><strong>Free Webinars On-Demand</strong></h2>
                                    <a href="{{ route('webinars_on_demand.webinar_type', 'free_webinar') }}" class=""> See More</a>
                                </div>
                                
                                <div class="row">
                                    @foreach($free_webinar as $video)
                                    @include('webinars_on_demand.includes.video')
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if (count($all_webinar))
                            <div class="webinar_section all_webinar" id="all_webinar_id">
                                <div class="webinar_section_title">
                                    <h2 class="title"><strong>All Webinars On Demand</strong></h2>
                                    <a href="{{ route('webinars_on_demand.webinar_type', 'all_webinar') }}" class=""> See More</a>
                                </div>
                                
                                <div class="row">
                                    @foreach($all_webinar as $video)
                                    @include('webinars_on_demand.includes.video')
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
{!! Form::close() !!}
@endsection

@section('scripts')
    <script src="/js/app.js"></script>
    <script>

        function get_button_value(name) {
            var value = false;
            return $('input[name='+name+']:checked').val();
        }

        // redirect to show webinar
        function webinar_read_more(url) {
            window.location.href = url
        }
        //end

        jQuery(document).ready(function () {

            $('a[data-scroll]').click(function(e) {
                e.preventDefault();

                //Set Offset Distance from top to account for fixed nav
                if($(window).width()>768) {
                    var offset = 80;
                }
                else {
                    var offset = 10;
                }
                var target = ( '#' + $(this).data('scroll') );
                var $target = $(target);

                //Animate the scroll to, include easing lib if you want more fancypants easings
                $('html, body').stop().animate({
                    'scrollTop': $target.offset().top - offset
                }, 1000, 'swing');
            });

            function checkIndicatorHeight() {
                if($('.card_wod .indicator').height() > 58) {
                    if($(window).width()>991) {
                        var height = '38px';
                        var right = '-12px';
                    }
                    else {
                        var height = '49px';
                        var right = '-8px';
                    }
                } else {
                    if($(window).width()>991) {
                        var height = '28px';
                        var right = '-16px';
                    }
                    else {
                        var height = '35px';
                        var right = '-13px';
                    }
                }
                $('.right_top_arrow').css('height',height);
                $('.right_top_arrow').css('right',right);

                $('.right_bottom_arrow').css('height',height);
                $('.right_bottom_arrow').css('right',right);
            }

            checkIndicatorHeight();
            $(window).resize(function(){
                checkIndicatorHeight();
            });

            var webinar_types = ["best_sellers", "new_releases", "webinar_series", "all_webinar", "free_webinar"];
            webinar_types.forEach(function(item, index) {
                var webinar_title_height = 0;
                $('.'+item+' .card-title').each(function() {
                    if($(this).height() > webinar_title_height) {
                        webinar_title_height = $(this).height();
                    }
                });
                $('.'+item+' .card-title').css('min-height', webinar_title_height);

                // if(window.width > 991) {
                //     $('.'+item+' .card_button').each(function() {
                //         if($(this).height() < 85) {
                //             $(this).css('height', 85);
                //             $(this).css('padding', '22.5px 0 22.5px 0');
                //         }
                //     });   
                // }
            });
            //end

            $('#sub_cat').hide();
            $('#sub_sub_cat').hide();

            // On click of clear search button
            $('.clear-button').click(function(){
                $('input[name=category]:checked').each(function(){
                    $(this).prop('checked', false);
                    $(this).parent().removeClass('active');
                });
                $('#sub_cat').hide();
                $('#sub_sub_cat').hide();
                $("#sub_category").empty();
                $("#sub_sub_category").empty();
                searchCategory();
            });

            // When a category is selected
            $('input[name=category]').change(function() {

                $('.clear-button').show();
                category = null;
                if($(this).prop('checked')) {
                    category = $(this).val();
                }

                if (category) {

                    // Hide and empty sub and sub sub categories
                    $("#sub_category").empty();
                    $('#sub_cat').hide();

                    $('#sub_sub_category').empty();
                    $('#sub_sub_cat').hide();

                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: '{{ route('webinars_on_demand.video.category') }}',
                        data: {'category':category,'_token':'{{ csrf_token() }}'},
                        error: function (xhr, settings, exception) {
                            alert('The update server could not be contacted.');
                        },
                        success: function (res) {

                            if (res) {

                                $('#sub_cat').show();
                                $.each(res, function (key, value) {
                                    $("#sub_category").append('<button type="button" class="btn btn-default btn-category" > <input type="radio" name="sub_category" value="'+value.id+'" autocomplete="off">' + value.title + '</button>');
                                    
                                });
                                 getSubCategory();
                            }

                            searchCategory();

                        }
                    });
                }
            }); 
        });

        function getSubCategory() {
            
            $('input[name=sub_category]').change(function(){

                var sub_category = null;
                if($(this).prop('checked')) {
                    sub_category = $(this).val();
                }
                if (sub_category) {

                    // Hide and empty sub sub categories
                    $('#sub_sub_category').empty();
                    $('#sub_sub_cat').hide();

                    $.ajax({
                        type: "POST",
                        cache: false,
                        url: '{{ route('webinars_on_demand.video.category') }}',
                        data: {'category':sub_category,'_token':'{{ csrf_token() }}'},
                        error: function (xhr, settings, exception) {
                            alert('The update server could not be contacted.');
                        },
                        success: function (res) {

                            if (res) {
                               $('#sub_sub_cat').show();
                               $('#sub_sub_category').empty();
                               $.each(res, function (key, value) {
                                    $("#sub_sub_category").append('<button type="button" class="btn btn-default btn-category" > <input type="radio" name="sub_sub_category" value="'+value.id+'" autocomplete="off">' + value.title + '</button>');
                                });
                                getSubSubCategory();
                              
                            }
                            searchCategory();

                        }
                    });
                }
            });
        
        }

        // Set event for sub sub category on change
        function getSubSubCategory() {
            $('input[name=sub_sub_category]').change(function(){ 
                searchCategory();
            });
        }

        // Call search function
        function searchCategory() {

            var category = get_button_value('category');
            var sub_category = get_button_value('sub_category');
            var sub_sub_category = get_button_value('sub_sub_category');
            var title = $("#title").val();
            
            if(!category) {
                category = null;
            }

            if(!sub_category) {
                sub_category = null;
            }

            if(!sub_sub_category) {
                sub_sub_category = null;
            }

            if(!title) {
                title = null;
            }

            $.ajax({ 
                type: "POST",
                cache: false,
                url: '/webinars_on_demand/search/category',
                data: {
                        'title': title,
                        'category':category,
                        'sub_category' :sub_category,
                        'sub_sub_category' :sub_sub_category,
                        '_token':'{{ csrf_token() }}'
                        },
                error: function (xhr, settings, exception) {
                    alert('The update server could not be contacted.');
                },  
                success: function (response) {
                    $('.search-category').html(response);
                    var webinar_title_height = 0;
                    $('.card-title').each(function() {
                        if($(this).height() > webinar_title_height) {
                            webinar_title_height = $(this).height();
                        }
                    });
                    $('.card-title').css('min-height', webinar_title_height);
                }
            });
            
        }
    </script>
@stop