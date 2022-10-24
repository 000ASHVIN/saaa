@extends('app')


@section('meta_tags')
    <title>Online Tax Courses | {{ config('app.name') }}</title>
    <meta name="description" content="The Tax Faculty is accredited as a non-profit tertiary & continuing professional development (CPD) provider. View our wide range of online courses here.">
    <meta name="Author" content="{{ config('app.name') }}"/>
@endsection

@section('styles')
<link href="/assets/frontend/css/intlTelInput.css" rel="stylesheet" type="text/css" />
<link href="/assets/frontend/css/demo.css" rel="stylesheet" type="text/css" />
<style>
    .course_container_div {
        text-align: center;
    }
    .coursebox img {
        max-height: 220px;
        min-height: 181px;
    }

    .coursebox img:hover {
        opacity: 0.32;
    }

    .coursebox .course-title {
        font-size: 20px;
        text-transform: uppercase;
        overflow: hidden;
        height: 90px;
    }

    .title {
        color: #009cae;
        text-align: center;
        margin-top: 30px;
        font-size: 40px;
        border-bottom: 2px solid #009cae;
        line-height:80px;
    } 
    input#cell {
        padding-left: 80px !important;
    }
    .find_course{
        background: #009cae;
        font-weight: bold;
        margin-top: 15px;
    }
    .course_banner_img{
        /* margin-top: 40px; */
        background: white !important;
    }
    
    .webinar_on_demand_price {
        background-color: #fff;
        padding-top: 5px;
        padding-bottom: 5px;
        color: black;
        font-weight: 700;
    }
    .download_brochure{
        background: #009cae;
        /* font-weight: bold; */
    }

    .course_intro_video.form-control{
        background: #173B63;
        /* font-weight: bold; */
        border-radius: 5px;
        width: 100%;
    }

    .course_intro_video.form-control:hover {
        background-color: #6EC1E4;
    }
    
    .btn-apply-now.form-control,
    .btn-read-more.form-control {
        width: 100%;
        text-transform: uppercase;
    }

    .search-form {
        padding: 10px;
        background-color: rgba(0, 0, 0, 0.05);
        /* border: 1px solid #e3e3e3; */
        border-radius: 15px;
    }
    input.form-control.event-title-filter.text-center {
        border: none !important;
        border-radius: 10px;
    }
    
    button.btn.btn-primary.find_course {
        border: none !important;
        border-radius: 10px;
        padding: 0 18px 0 18px;
    }
    
    button.btn.btn-primary.download_brochure.form-control {
        border: none !important;
        border-radius: 5px;
        width: 100%;
    }

    button.btn.btn-primary.download_brochure.form-control:focus {
        background-color: #009cae;
    }

    button.btn.btn-primary.download_brochure.form-control:hover {
        background-color: #173b63;
    }
    
    a.btn.btn-primary.form-control.change-color {
        border: none !important;
        border-radius: 4px;
        padding: 7px 18px 0 18px;
    }
    
    .btn-apply-now.form-control:hover,
    .btn-read-more.form-control:hover {
        background-color: #2eab52;
    }

    .brochure-popup-background {
        border-radius: 25px;
    }
    .course_brochure_popup .modal-dialog {
        -webkit-border-radius: 0;
        -moz-border-radius: 3px;
        top:35px;
        border-radius: 3px;
        -webkit-box-shadow: none !important;
        -moz-box-shadow: none !important;
        box-shadow: none !important;
    }
    
    .course_brochure_popup input.form-control.brochure-popup-text {
        border: none !important;
        border-radius: 7px;
    }
    .course_brochure_popup img {
        margin: -75px 0 0 0;
        height: 135px;
    }
    .brochure-popup-background{
        background:  #c9c9c8;
        border: none;
    }
    .brochure-popup-title{
        text-align: center;
        color:  #009cae;
        font-size: 40px;
    } 
    .brochure-popup-border{
        border-bottom: none;
    }
    .brochure-popup-required-title{
        font-weight: normal;
        font-style: italic;
    }
    .brochure-popup-footer{
        padding: 15px;
        text-align: center;
        border-top: none;
        margin-top: -30px;
    }
    .brochure-popup-text{
        font-weight: bold;
        color: black !important;
    }
    
    #course_brochure_popup_login .container_models{
        width: 90%;
        margin: 0 6% 6px 6%;
        font-size: 17px;
    }
    
    #course_brochure_popup_login .modal-footer.brochure-popup-footer a {
        width: 21%;
        border-radius: 5px;
        font-size: 16px;
    }
    .course_brochure_popup .close {
        margin: 11px;
        color: white;
        font-size: 20px;
    }
    .text-centre{
        text-align: center;
    
    }
    .theme-color.hidden-print {
        border:0px;
    }
    @media screen and (max-width: 991px) {
        .w_title {
        height: auto;
        }
    }
    .course_banner_img .search-box {
        top: 25%;
        bottom: 25%;
        left: 20%;
        right: 20%;
        position: absolute;
        background-color: transparent;
        border: 0;
    }
    #slider.course_banner_img .search-box input {
        color: #000000;
        background-color: #FFFFFF;
        box-shadow: 4px 4px 7px #8e8e8e !important;
    }
    #slider.course_banner_img .search-box .btn-default {
        background-color: #009cae;
    }
    .category-blocks .category-block img {
        max-width: 100%;
        box-shadow: 0px 7px 16px 4px rgba(0, 0, 0, 0.38);
    }
    .category-blocks .category-block {
        padding: 35px 13px;
        text-align: center;
    }
    .talk-to-human-section {
        padding: 10px 30px;
        background-color: #009cae;
        color: #ffffff;
        font-size: 28px;
        margin-bottom: 35px;
    }
    .talk-to-human-section .text-section {
        line-height: 50px;
        vertical-align: middle;
    }
    .talk-to-human-section .button-section {
        text-align: right;
    }
    .talk-to-human-section .button-section .button {
        background-color: #8CBF3C;
        font-size: 16px;
        padding: 15px 30px;
        border-radius: 4px;
        color: #ffffff;
        font-weight: bold;
    }
    .category-heading {
        background-color: #E6E6E6;
        padding: 20px 15px;
        margin-bottom: 35px;
        text-align: center;
    }
    .category-heading .heading {
        margin-bottom: 20px;
        color: #1C3B66;
        font-family: "Lato", Sans-serif;
        font-size: 46px;
        font-weight: 600;
        line-height: 1;
    }
    .category-heading .sub-heading {
        margin-bottom: 0px;
        color: #414142;
        font-size: 21px;
        font-weight: 600;
        line-height: 1;
    }
    .course-list-section {
        margin-bottom: 35px;
    }

    .coursebox .hr-divider span {
        width: 100%;
        display: block;
        border-top: 1px solid #313131;
    }

    .coursebox .course-info {
        color: #4c4c4c;
        font-size: 24px;
        line-height: 1;
    }

    .coursebox .course-info p {
        margin-top: 0;
    }

    .coursebox .flexible-payment {
        color: #5b5b5b;
        font-family: "Lato", Sans-serif;
        font-size: 18px;
        font-weight: 400;
    }

    @media(max-width:768px) {
        .talk-to-human-section .button-section {
            text-align: center;
            margin-bottom: 15px;
            margin-top: 15px;
        }

        .talk-to-human-section .text-section {
            line-height: 30px;
        }

        .talk-to-human-section .button-section button {
            font-size: 14px;
        }

        .talk-to-human-section {
            font-size: 20px;
        }

        .btn-apply-now.form-control {
            margin-bottom: 15px;
        }

        .coursebox img {
            max-height: unset;
        }

        .category-blocks .category-block {
            padding: 20px 0px;
        }

        .category-blocks {
            margin-top: 15px;
        }
    }

    @media(max-width:630px) {
        .category-blocks .category-column {
            width: 100%;
        }

        .category-heading .heading {
            font-size: 26px;
        }

        .category-heading .sub-heading {
            font-size: 18px;
        }
        h3 {
            margin-bottom: 10px;
        }
    }
    .border-box.coursebox {
        height: auto;
        max-height: 650px;
    }
</style>


@endsection

@section('content')

@section('title')
    Online Courses
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('courses') !!}
@stop
        
<section id="slider" class="hidden-sm hidden-xs course_banner_img">
    <img src="{{ url('assets/themes/taxfaculty/img/2022_Course-Page_Header-Banner_1536x349-(02).jpg') }}" alt="Videos"  style="width: 100%;" class="course_banner_img">

    <section class="search-box" style="padding: 10px;">
        <div class="row">
            <div class="col-md-12 search-text">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="text-center">
                        <br>
                        {!! Form::open(['method' => 'post', 'route' => 'courses.search']) !!}}
                        <div class="form-group @if ($errors->has('title')) has-error @endif">
                            {!! Form::input('text', 'title', (isset($title) ? $title : ""), ['class' => 'form-control','placeholder' => 'What do you want to learn?', 'style'=>'text-align:center','id' => 'title' ]) !!}
                            @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
                        </div>
                        <div class="form-group">
                            <button onclick="spin(this)" style="color: #173175" class="btn-block btn btn-default"><i class="fa fa-search"></i> FIND MY COURSE</button>
                        {!! Form::close() !!}
                        </div>  
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </section>
</section>

  
<!-- <section class="alternate" style="background-image: url('/assets/frontend/images/demo/wall2.jpg');"> -->
<div class="container">

    <div class="category-blocks">
        <div class="row">
            <div class="col-xs-6 col-md-4 category-column">
                <div class="category-block">
                    <a href="#" data-scroll="qualifications">
                        <img src="{{ url('assets/themes/taxfaculty/img/1_Occupational_Certificates_(Leading_to_SAIT)_v03.jpg') }}
                        ">
                    </a>
                </div>
            </div>
            <div class="col-xs-6 col-md-4 category-column">
                <div class="category-block">
                    <a href="#" data-scroll="professional">
                        <img src="{{ url('assets/themes/taxfaculty/img/2_Professional_Certificates_(Three_to_Twelve)_v03.jpg') }}">
                    </a>
                </div>
            </div>
            <div class="col-xs-6 col-md-4 category-column">
                <div class="category-block">
                    <a href="#" data-scroll="short_courses">
                        <img src="{{ url('assets/themes/taxfaculty/img/3_Short_Courses_(one_to_three)_v03.jpg') }}">
                    </a>
                </div>
            </div>
        </div>
    </div>

    <h3 class="title">COURSES 2022</h3>
    <div class="row">
        <div class="col-md-12">
            <div class="talk-to-human-section">
                <div class="row">
                    <div class="col-sm-8">
                        <div class="text-section">
                            Request for one of our consultants to call you back
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="button-section">
                            <a href="javascript:void(0);" class="button open-tth-popup" data-course="">Talk to a Human</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="category-heading" id="qualifications">
                <h2 class="heading">Occupational Certificates</h2>
                <h3 class="sub-heading">leading to a SAIT designation</h3>
            </div>
        </div>
    </div>

    <div class="row course-list-section">
        <div class="col-md-12 course_container">
            @foreach($qualifications as $course)
                @include('courses.partials.course')
            @endforeach
        </div>
        {{-- <div class="col-md-12 course_container">
            {!! $courses->render() !!}
        </div> --}}
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="category-heading" id="professional">
                <h2 class="heading">Professional Certificates</h2>
                <h3 class="sub-heading">Three to twelve-month programmes</h3>
            </div>
        </div>
    </div>

    <div class="row course-list-section">
        <div class="col-md-12 course_container">
            @foreach($professional as $course)
                @include('courses.partials.course')
            @endforeach
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="category-heading" id="short_courses">
                <h2 class="heading">Short Courses</h2>
                <h3 class="sub-heading">One to three-month programmes</h3>
            </div>
        </div>
    </div>

    <div class="row course-list-section">
        <div class="col-md-12 course_container">
            @foreach($short_courses as $course)
                @include('courses.partials.course')
            @endforeach
        </div>
        {{-- <div class="col-md-12 course_container">
            {!! $courses->render() !!}
        </div> --}}
    </div>

    @include('courses.partials.talk_to_human')
</div>
@include('courses.partials.login_popup')
@include('courses.partials.course_brochure_popup')
{{-- </section> --}}
@endsection

@section('scripts')
<script src="/assets/frontend/js/intlTelInput.js"></script>
<script>
    $(document).on('click','.download_brochure',function(){
        var CId= $(this).data('cid');
        $('#course_brochure_popup').find('input[name=course_id]').val(CId)
        $('#course_brochure_popup').modal('show');
    })
    $(document).ready(function(){
        // var max=50;
        // $('.border-box').each(function(){
        //     if($(this).height()>max){
        //         max=$(this).height();
        //     }
        // })

        // $('.border-box').each(function(){
        //     $(this).css('min-height',max+'px'); 
        // })

        if($(window).width() > 991) {
          var max=50;
          $('.w_title').each(function(){
              if($(this).height()>max){
                  max=$(this).height();
              }
          })
          $('.w_title').height(max);
        }

        $(window).resize(function () {
          if($(window).width() > 991) {
            var max=50;
            $('.w_title').each(function(){
                if($(this).height()>max){
                    max=$(this).height();
                }
            })
            $('.w_title').height(max);
          } else {
            $('.w_title').css('height', 'auto');
          }
        });

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

        // Open popup for talk to human
        $('.open-tth-popup').on('click', function(){
            var course = $(this).data('course');
            $('#talk_to_human_popup input[name=course_id]').val(course);
            $('#talk_to_human_popup').modal('show');
        });
        var input = document.querySelectorAll(".cell");
        input.forEach(myFunction)
        
        
        function myFunction(item, index) {
          window.intlTelInput(item, {
                // allowDropdown: false,
                autoHideDialCode: false,
                autoPlaceholder: "off",
                // dropdownContainer: document.body,
                // excludeCountries: ["us"],
                initialCountry: "za",
                formatOnDisplay: true,
                // geoIpLookup: function(callback) {
                //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                //     var countryCode = (resp && resp.country) ? resp.country : "";
                //     callback(countryCode);
                //   });
                // },
                hiddenInput: "full_number",
                 initialCountry: "za",
                // localizedCountries: { 'de': 'Deutschland' },
                nationalMode: false,
                // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
                // placeholderNumberType: "MOBILE",
                // preferredCountries: ['cn', 'jp'],
                separateDialCode: true,
                utilsScript: "/assets/frontend/js/utils.js",
                });
        }


    })
</script>

@if (Session::has('is_error'))
<script>
    $(document).ready(function(){
        setTimeout(function(){
    $("#{{Session::get('is_error')}}").modal('show');
}, 1000);
<?php 
session()->forget('is_error')
?>
    });
</script>

@endif
    @if (Session::has('course'))
        
        <script>
            $(document).ready(function(){
                setTimeout(function(){
                    $('#course_brochure_popup_login').modal('show');
                }, 1000);
                setTimeout(function() {
                    window.location = "{{ route('courses.download_brochure', [Session::get('course')]) }}";
                }, 2000);
            });
        </script>
        <?php 
            session()->forget('course')
        ?>
    @endif
@stop