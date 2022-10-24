@extends('app')

@section('title')
    Fund a Learner
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('fund_a_learner') !!}
@stop

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets\themes\taxfaculty\css\owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets\themes\taxfaculty\css\owl.theme.default.min.css')}}">
    <style>
        .fund_header_section {
            padding: 0px;
        }
        .fund_section {
            padding-bottom: 0px;
            border: none;
        }
        .head_description h1 {
            color: #009cae;
            font-family: "Roboto", Sans-serif;
            font-weight: 600;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 20px;
        }
        .head_description p {
            text-align: center;
        }
        .fund_information {
            background-color: #f6f6f6;
        }
        .fund_information_text h3:first-child {
            /* // margin-top: 32px; */
            margin-bottom: 20px;
        }
        .fund_information_text h3:nth-child(2) {
            margin-bottom: 0px !important;
        }
        .fund_information_text {
            text-align: center;
        }
        .fund_information_text h3 {
            color: #1c3b66;
            font-family: "Lato", Sans-serif;
            line-height: 1.4em;
        }
        .fund_information_text p {
            font-family: "Lato", Sans-serif;
        }
        .fund_information_text a {
            font-family: "Lato", Sans-serif;
            margin-top: 20px;
            /* // margin-bottom: 32px; */
            padding: 6px 30px 0px;
            font-weight: bold;
            border-radius: 0px;
            background-color: #85c52f;
        }
        .fund_course_title h2 {
            text-align: center;
            color: #1c3b66;
            font-family: "Lato", Sans-serif;
            line-height: 1.4em;
        }
        .fund-carousel iframe,
        .fund-carousel-course-second iframe {
            min-height: auto;
            margin-bottom: -5px;
        }
        .fund-carousel h3,
        .fund-carousel-course-second h3 {
            text-align: left;
            color: white;
            font-size: 14px;
            font-weight: 800;
            margin-bottom: 6px;
        }
        .fund-carousel p,
        .fund-carousel-course-second p {
            text-align: left;
            color: white;
        }
        .fund-carousel .card-body {
            background-color: #85c52f;
            padding: 10px;
        }

        /* // for second carousel of fund courses */
        .fund-carousel-course-second .card-body {
            background-color: #069cad;
            padding: 10px;
        }

        .youtube_video {
            position: relative;
        }
        .overlay_image {
            position: absolute !important;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .overlay_image img {
            -webkit-background-size: cover;
            background-size: cover;
            background-position: 50%;
        }
        @media only screen and (min-width: 768px) {
            .overlay_image img {
                height: 100%;
            }
        }
        @media only screen and (max-width: 768px) {
            .fund-carousel-section {
                padding: 0px 50px;
            }
        }
        .overlay_play_icon {
            position: absolute !important;
            width: 100px;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        .overlay_play_icon svg {
            font-size: 100px;
            color: white;
            cursor: pointer;
        }

        .why_taxfaculty_title h3 {
            color: #1c3b66;
            font-family: "Lato", Sans-serif;
            text-align: center;
        }

        .why_taxfaculty_point_info {
            padding: 10px 0px 10px 0px;
            margin-bottom: 20px;
        }
        @media only screen and (min-width: 768px) {
            .point_logo {
                margin-right: 20px;
            }
            .why_taxfaculty_point_info img {
                width: 115px !important;
            }
            .why_taxfaculty_point_info {
                display: flex;
                align-items: center;
            }
            .first_half_points .row {
                display: flex;
                align-items: center;
            }
            .second_half_points .row {
                display: flex;
                align-items: center;
            }
        }

        @media only screen and (max-width: 768px) {
            .why_taxfaculty_points {
                padding: 30px;
            }
            .why_taxfaculty_point_info {
                text-align: center;
                margin-bottom: 0px !important;
            }
            .why_taxfaculty_point_info:first-child img {
                max-height: 250px;
            }
            .why_taxfaculty_point_info img {
                /* // max-height: 250px;
                // margin-bottom: 10px; */
                max-width: 100% !important;
                padding-left: 25%;
                padding-right: 25%;
            }
            .why_taxfaculty_point_info p {
                padding: 10px;
            }
        }
        .fund_more_information_text {
            margin-bottom: 20px;
            text-align: center;
        }
        .fund_information_contact {
            border: 1px solid #cdcccc;
            padding: 30px 0px 30px 0px;
            margin-bottom: 0px !important;
        }
        @media only screen and (min-width: 768px) {
            .fund_information_contact {
                margin-left: 25%;
                margin-right: 25%;
            }
        }
        .fund_information_contact p {
            text-align: center;
        }
        .fund_information_contact strong {
            color: #003865;
        }
        .fund_information {
            padding: 50px 0px 50px 0px;
        }
        .fund_course_donation {
            text-align: center;
        }
        .fund_course_donation a {
            padding: 6px 30px;
            color: white;
            font-weight: bold;
            font-family: var(--e-global-typography-accent-font-family), Sans-serif;
            border-radius: 0px;
        }
        .fund_course_donation.first_course a {
            background-color: #069cad;
        }
        .fund_course_donation.second_course a {
            background-color: #85c52f;
        }
        .fund_course_donation a:hover {
            color: white;
        }
        .fund_course:first-child {
            margin-bottom: 50px;
        }
        .overlay_play_icon {
            opacity: 0.8;
        }
        .overlay_play_icon:hover {
            opacity: 1;
        }

        .owl-carousel {
            overflow: unset;
        }
        .owl-prev {
            width: 15px;
            height: 100px;
            position: absolute;
            top: 40%;
            left: -32px;
            margin-left: -20px;
            display: block !important;
            border:0px solid black;
            padding: 1px 0px 4px 4px !important;
            line-height: 0px;
            background: transparent !important;
        }

        .owl-next {
            width: 15px;
            height: 100px;
            position: absolute;
            top: 40%;
            right: -32px;
            display: block !important;
            border:0px solid black;
            padding: 1px 0px 4px 4px !important;
            line-height: 0px;
            background: transparent !important;
        }
        .owl-prev i, .owl-next i {
            font-size: 50px;
            font-weight: bold;
            color: #54595f;
        }
        .fund-carousel.owl-theme .owl-nav [class*=owl-]:hover {
            background: #85c52f !important;
        }
        .fund-carousel-course-second.owl-theme .owl-nav [class*=owl-]:hover {
            background: #069CAD !important;
        }
        .owl-dots {
            display: none;
        }
    </style>
@endsection


@section('content')
    <section class="fund_header_section">
        <div class="row">
            <img src="{{ asset('assets\themes\taxfaculty\img\fund_a_learner\Web-Banner_Donor-Landing-Page-1920x-1-1024x186.png') }}" alt="" width="100%">
        </div>
    </section>

    <section class="fund_section">
        <div class="container">
            <div class="head_description">
                <h1>FUND A LEARNER</h1>
                <p>As a non-profit organisation, The Tax Faculty’s purpose is to empower and transform people’s lives. Over the past two years, we have successfully empowered over 440 learners in collaboration with public and private partnerships to fund, source and train unemployed youth to become world-class tax professionals. Unfortunately, we currently more than 100 learners who can no longer obtain public funding and require your pledge in order to complete their tax qualification.</p>
            </div>
        </div>
    </section>

    <section class="fund_section">

        <div class="fund_information">
            <div class="container">
                <div class="fund_information_text">
                    <h3>From as little as R100, you or your organisation can change a life.</h3>
                    <h3>Meet our learners who’d appreciate a little help from you. Your pledge will help ignite transformation in the tax profession.</h3>
                </div>
            </div>
        </div>

    </section>

    <section class="fund_section">

        <div class="container">
            <div class="fund_course">
                <div class="fund_course_title">
                    <h2>Diploma: Tax Technician</h2>
                </div>
                <div class="fund-carousel-section">
                    <div class="fund-carousel owl-carousel owl-theme owl-loaded">
                        <div class="owl-stage-outer">
                            <div class="owl-stage">
                                
                                @for($i = 1; $i <= 8; $i++)
                                <div class="owl-item">
                                    <div class="card">
                                        <div class="youtube_video">
                                            <iframe class="card-img-top" src="https://www.youtube.com/embed/tgbNymZ7vqY" allow='autoplay'></iframe>
                                            <div class="overlay_image">
                                                <img src="{{ asset('assets\themes\taxfaculty\img\fund_a_learner\demo_ttf.jpg') }}" alt="">
                                            </div>
                                            <div class="overlay_play_icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><path d="M838 162C746 71 633 25 500 25c-129 0-242 46-337 137C71 254 25 367 25 500s46 246 138 337c91 92 204 142 337 142s246-46 338-142c91-91 137-204 137-337s-46-246-137-338m-30 30c84 87 125 187 125 308s-41 225-125 308c-83 84-187 130-308 130s-221-42-304-130C113 725 67 621 67 500s41-221 129-308c83-84 187-130 304-130 121 0 221 46 308 130M438 392v250l204-125-204-125z" fill="#ffffff"/></svg>
                                            </div>
                                        </div>
                                        
                                        <div class="card-body">
                                          <h3 class="card-title">Joshua<br>BCom: Accounting</h3>
                                          <p class="card-text">I started my career as a trainee accountant and was busy with my third-year articles at Deloitte when I was involved in a car accident that changed my life; I became a quadriplegic and was not able to complete my training. I always had an interest in tax since my years at Deloitte and I’m motivated to complete training after which I would like to explore tax consultancy opportunities.</p>
                                        </div>
        
                                    </div>
                                </div>
                                @endfor
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fund_course_donation first_course">
                    <a href="https://taxfaculty.ac.za/donate" class="btn">DONATE NOW</a>
                </div>
            </div>

            <div class="fund_course">
                <div class="fund_course_title">
                    <h2>Specialist Diploma: Tax Professional</h2>
                </div>
                <div class="fund-carousel-section">
                    <div class="fund-carousel-course-second owl-carousel owl-theme owl-loaded">
                        <div class="owl-stage-outer">
                            <div class="owl-stage">
                                
                                @for($i = 1; $i <= 8; $i++)
                                <div class="owl-item">
                                    <div class="card">
                                        <div class="youtube_video">
                                            <iframe class="card-img-top" src="https://www.youtube.com/embed/tgbNymZ7vqY" allow='autoplay'></iframe>
                                            <div class="overlay_image">
                                                <img src="{{ asset('assets\themes\taxfaculty\img\fund_a_learner\demo_ttf.jpg') }}" alt="">
                                            </div>
                                            <div class="overlay_play_icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><path d="M838 162C746 71 633 25 500 25c-129 0-242 46-337 137C71 254 25 367 25 500s46 246 138 337c91 92 204 142 337 142s246-46 338-142c91-91 137-204 137-337s-46-246-137-338m-30 30c84 87 125 187 125 308s-41 225-125 308c-83 84-187 130-308 130s-221-42-304-130C113 725 67 621 67 500s41-221 129-308c83-84 187-130 304-130 121 0 221 46 308 130M438 392v250l204-125-204-125z" fill="#ffffff"/></svg>
                                            </div>
                                        </div>
                                        
                                        <div class="card-body">
                                          <h3 class="card-title">Joshua<br>BCom: Accounting</h3>
                                          <p class="card-text">I started my career as a trainee accountant and was busy with my third-year articles at Deloitte when I was involved in a car accident that changed my life; I became a quadriplegic and was not able to complete my training. I always had an interest in tax since my years at Deloitte and I’m motivated to complete training after which I would like to explore tax consultancy opportunities.</p>
                                        </div>
        
                                    </div>
                                </div>
                                @endfor
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fund_course_donation second_course">
                    <a href="https://taxfaculty.ac.za/donate" class="btn">DONATE NOW</a>
                </div>
            </div>
        </div>

    </section>

    <section class="fund_section">

        <div class="fund_information">
            <div class="container">
                <div class="fund_information_text">
                    <h3>How can I or my organisation assist?</h3>
                    <p>From as little as R100, you or your organisation can change one of these learner’s lives. The Tax Faculty is a recognised SARS public benefit organisation and a level 1 B-BBEE contributor, therefore, as an added benefit to changing a life, you will receive a section 18A certificate and a B-BBEE letter of confirmation.</p>
                    <a class="btn btn-primary" href="https://taxfaculty.ac.za/donate">MAKE YOUR PLEDGE TODAY</a>
                </div>
            </div>
        </div>

    </section>

    <section class="fund_section">
        <div class="container">
            <div class="why_taxfaculty_title">
                <h3>Why partner with The Tax Faculty?</h3>
            </div>
            <div class="why_taxfaculty_points">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="first_half_points">
                            <div class="row">
                                <div class="why_taxfaculty_point_info">
                                    <div class="point_logo">
                                        <picture>
                                            <source media="(min-width:768px)" srcset="{{ asset('assets\themes\taxfaculty\img\fund_a_learner\SARS-Approved-icon-1-300x210.jpg') }}">
                                            <img src="{{ asset('assets\themes\taxfaculty\img\fund_a_learner\SARS-Approved-icon-1.jpg') }}" style="width: auto" alt="">
                                        </picture>
                                    </div>
                                    <p>SARS-approved public benefit organisation</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="why_taxfaculty_point_info">
                                    <div class="point_logo">
                                        <picture>
                                            <source media="(min-width:768px)" srcset="{{ asset('assets\themes\taxfaculty\img\fund_a_learner\B-BBEE-icon-1-300x300.jpg') }}">
                                            <img src="{{ asset('assets\themes\taxfaculty\img\fund_a_learner\B-BBEE-icon-1.jpg') }}" style="width: auto" alt="">
                                        </picture>
                                    </div>
                                    <p>SARS-approved public benefit organisation</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="why_taxfaculty_point_info">
                                    <div class="point_logo">
                                        <picture>
                                            <source media="(min-width:768px)" srcset="{{ asset('assets\themes\taxfaculty\img\fund_a_learner\Section-18A-icon-1-231x300.jpg') }}">
                                            <img src="{{ asset('assets\themes\taxfaculty\img\fund_a_learner\Section-18A-icon-1.jpg') }}" style="width: auto" alt="">
                                        </picture>
                                    </div>
                                    <p>SARS-approved public benefit organisation</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="second_half_points">
                            <div class="row">
                                <div class="why_taxfaculty_point_info">
                                    <div class="point_logo">
                                        <picture>
                                            <source media="(min-width: 768px)" srcset="{{ asset('assets\themes\taxfaculty\img\fund_a_learner\Innovative-icon-1-300x270.jpg') }}">
                                            <img src="{{ asset('assets\themes\taxfaculty\img\fund_a_learner\Innovative-icon-1.jpg') }}" style="width: auto" alt="">
                                        </picture>
                                    </div>
                                    
                                    <p>Innovative learning methodology simulating reality in a tax practice to prepare future-ready tax professionals</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="why_taxfaculty_point_info">
                                    <div class="point_logo">
                                        <picture>
                                            <source media="(min-width: 768px)" srcset="{{ asset('assets\themes\taxfaculty\img\fund_a_learner\Leading-experts-icon-1-300x146.jpg') }}">
                                            <img src="{{ asset('assets\themes\taxfaculty\img\fund_a_learner\Leading-experts-icon-1.jpg') }}" style="width: auto" alt="">
                                        </picture>
                                    </div>
                                    <p>Innovative learning methodology simulating reality in a tax practice to prepare future-ready tax professionals</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="why_taxfaculty_point_info">
                                    <div class="point_logo">
                                        <picture>
                                            <source media="(min-width: 768px)" srcset="{{ asset('assets\themes\taxfaculty\img\fund_a_learner\NPO-icon-1-300x146.jpg') }}">
                                            <img src="{{ asset('assets\themes\taxfaculty\img\fund_a_learner\NPO-icon-1.jpg') }}" style="width: auto" alt="">
                                        </picture>
                                    </div>
                                    <p>Innovative learning methodology simulating reality in a tax practice to prepare future-ready tax professionals</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="fund_section">

        <div class="fund_information">
            <div class="container">
                <div class="fund_more_information_text">
                    <p>For more information on our donor or sponsorship models and to get your organisation involved in igniting transformation in the tax profession through dedicated tax training and skills development, please contact:</p>
                </div>
                <div class="fund_information_contact">
                    <p><strong>Vanessa Fox</strong></p>
                    <p><strong>Head of Transformation and Development</strong></p>
                    <p><strong>Tel: </strong><a href="tel:+27 (0)71 602 6222">+27 (0)71 602 6222</a></p>
                    <p><strong>Email: </strong><a href="mailto:vfox@taxfaculty.ac.za">vfox@taxfaculty.ac.za</a></p>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('scripts')
<script src="{{ asset('assets\themes\taxfaculty\js\owl.carousel.min.js')}}"></script>
<script>
    $(document).ready(function() {
         
        $(".fund-carousel").owlCarousel({
            loop:true,
            margin:20,
            // responsiveClass:true,
            nav:true,
            navText : ['<div class="owl-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>','<div class="owl-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>'],
            responsive:{
                0:{
                    items:1,
                    loop:false
                },
                768:{
                    items:3,
                    loop:false
                },
                992: {
                    items:4,
                    loop:false
                }
            }
        });

        $(".fund-carousel-course-second").owlCarousel({
            loop:true,
            margin:20,
            // responsiveClass:true,
            nav:true,
            navText : ['<div class="owl-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>','<div class="owl-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>'],
            responsive:{
                0:{
                    items:1,
                    loop:false
                },
                768:{
                    items:3,
                    loop:false
                },
                992: {
                    items:4,
                    loop:false
                }
            }
        });

        $(".overlay_play_icon").click(function(){
            var play = $(this);
            play.css('display','none');
            var image = play.prev();
            image.css('display','none');

            var video = image.prev();
            var src = video.attr('src');
            video.attr('src', src+"?autoplay=1");
        });
        if($(window).width() < 768) {
            var width = $('.why_taxfaculty_point_info:first-child img').width();
            $('.why_taxfaculty_point_info img').width(width);
            $('.why_taxfaculty_point_info img').css('max-height', 'none');

            var ifame_height = $('.youtube_video iframe').height($('.youtube_video img').height());
        }

        if($(window).width() > 768) {
            var i = 1;
            var first_half_heights = new Array;
            $('.first_half_points .row').each(function() {
                first_half_heights[i] = $(this).height()
                i++;
            });
            var j = 1;
            var second_half_heights = new Array;
            $('.second_half_points .row').each(function() {
                second_half_heights[j] = $(this).height()
                j++;
            });
            for(i=1; i <= first_half_heights.length-1; i++) {
                var height = first_half_heights[i];
                if(second_half_heights[i] > height) {
                    height = second_half_heights[i];
                }
                $('.first_half_points .row:nth-child('+i+')').height(height)
                $('.second_half_points .row:nth-child('+i+')').height(height)
            }
        }

    });
</script>
@endsection