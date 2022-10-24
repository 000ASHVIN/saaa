@extends('app')

@section('meta_tags')
    <title>SAAA | SA Accounting Academy | CPD Provider</title>
    <meta name="description" content="The SA Accounting Academy (SAAA) offers Continuing Professional Development (CPD) training for accountants, auditors, bookkeepers, company secretaries and tax practitioners. We offer a range of live seminars and conferences and online webinars, seminar recordings, certificate courses and DVDs on both technical and business-related topics.">
    <meta name="Author" content="SA Accounting Academy"/>
@endsection

@section('styles')
    <style>
        .heading-arrow-top:after {
            content: ' ';
            position: absolute;
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-bottom: 20px solid #800000;
            left: 50%;
            margin-left: -20px;
            top: -20px;
        }

        .heading-arrow-bottom:after {
            border-bottom: 20px solid #800000;
        }
    </style>
@endsection


@section('content')
    {{--<section class="info-bar alternate wow bounceInLeft animation-visible animated hidden-sm hidden-xs"--}}
    {{--data-wow-delay="0.4s">--}}
    {{--<div class="container">--}}
    {{--<div class="row">--}}
    {{--@foreach($professions->take(6) as $profession)--}}
    {{--<div class="col-md-2 text-center">--}}
    {{--<a href="{{ route('profession.show', $profession->slug) }}">--}}
    {{--<i style="float: initial; margin-right: 0px;" class="main-color glyphicon glyphicon-plus-sign"></i>--}}
    {{--<h4 style="font-size: 13px">{!! str_limit($profession->title, 20) !!}</h4>--}}
    {{--@if(count($profession->plans))--}}
    {{--<small>From <sup>R</sup>{!! $profession->plans()->min('price') !!}</small>--}}
    {{--@else--}}
    {{--<small>From <sup>R</sup>Coming Soon!</small>--}}
    {{--@endif--}}
    {{--</a>--}}
    {{--</div>--}}
    {{--@endforeach--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</section>--}}


    <section class="alternate">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <header class="margin-bottom-40">
                        <h1 class="weight-300">CPD Subscription Features </h1>
                        <h2 class="weight-300 letter-spacing-1 size-13"><span>Join more than {{ $users -1, 2 }} Accountants, Auditors and Tax Practitioners</span></h2>
                    </header>

                    <ul class="list-unstyled list-icons">
                        <li><i class="fa fa-check text-success"></i> Core and elective CPD topics</li>
                        <li><i class="fa fa-check text-success"></i> Watch webinars at your convenience</li>
                        <li><i class="fa fa-check text-success"></i> CPD Tracker / Logbook</li>
                        <li><i class="fa fa-check text-success"></i> Printable CPD Certificates</li>
                        <li><i class="fa fa-check text-success"></i> Searchable Technical Resource Centre</li>
                        <li><i class="fa fa-check text-success"></i> Access to experts</li>
                        <li><i class="fa fa-check text-success"></i> Reference guides </li>
                        <li><i class="fa fa-check text-success"></i> Interactive sessions </li>
                        <li><i class="fa fa-check text-success"></i> Online Q&A </li>
                        <li><i class="fa fa-check text-success"></i> Practical examples </li>
                        <li><i class="fa fa-check text-success"></i> Affordable </li>
                        <li><i class="fa fa-check text-success"></i> Accredited </li>
                    </ul>

                    <a href="/cpd" class="btn btn-primary"><i class="fa fa-lock"></i> Read More <Now></Now></a>
                </div>

                <div class="col-sm-6">
                    <div class="owl-carousel buttons-autohide controlls-over"
                         data-plugin-options='{"items": 1, "autoHeight": true, "navigation": true, "pagination": true, "autoPlay": 10000, "transitionStyle":"backSlide"}'>
                        <div>
                            <img class="img-responsive" src="/assets/frontend/images/demo/desktop_slider-4.png" alt="">
                        </div>
                        <div>
                            <img class="img-responsive" src="/assets/frontend/images/demo/desktop_slider-3.png" alt="">
                        </div>
                        <div>
                            <img class="img-responsive" src="/assets/frontend/images/demo/desktop_slider-6.png" alt="">
                        </div>
                        <div>
                            <img class="img-responsive" src="/assets/frontend/images/demo/desktop_slider-5.png" alt="">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="callout-dark heading-arrow-top">
        <a href="/auth/register" class="btn btn-xlg btn-primary size-10 fullwidth nomargin bopadding noradius ">
            <span class="font-lato size-30"><span style="font-size: 20px" class="countTo" data-speed="3000">{{ $users -1, 2  }}</span> <span style="font-size: 20px">Accountants, Auditors and Tax Practitioners are already part of our network of subscribers</span></span>
            <span class="block font-lato">Why don't you <span style="text-decoration: underline;">join</span> the network and be number <span class="countTo" data-speed="3000">{{ $users - 1 +1 }}</span> ?</span>
        </a>
    </div>

    <section>
        <div class="row">
            <div class="col-md-12">
                <header class="margin-bottom-40 text-center">
                    <h1 class="weight-300">Our Partners</h1>
                    <h2 class="weight-300 letter-spacing-1 size-13"><span>We have teamed up with..</span></h2>
                </header>
            </div>
        </div>
        <div class="container">
            <ul class="row clients-dotted list-inline">
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="/assets/frontend/images/sponsors/saiba.jpg" alt="SAIBA" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="/assets/frontend/images/sponsors/draftworx.jpg" alt="Draftworx" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="/assets/frontend/images/sponsors/bluestar.jpg" alt="BlueStar" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="/assets/frontend/images/sponsors/quickbooks.jpg" alt="Quickbooks" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="/assets/frontend/images/sponsors/acts.jpg" alt="Acts Online" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="https://imageshack.com/a/img924/3893/lOmls8.jpg" alt="The SAIT" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="https://imageshack.com/a/img923/9456/CCsy59.jpg" alt="UNISA" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="https://imageshack.com/a/img921/8710/3Jv5Z3.jpg" alt="The Tax Shop" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="https://imageshack.com/a/img923/1850/DqUSr2.jpg" alt="The Tax Faculty" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="https://imageshack.com/a/img924/382/QmBAkg.jpg" alt="ICAEW" />
                </li>
            </ul>
        </div>
    </section>



    <section class="section-sm alternate">
        <div class="container">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <header class="margin-bottom-40 text-center">
                            <h1 class="weight-300">What our subscribers saying</h1>
                            <h2 class="weight-300 letter-spacing-1 size-13"><span>Feedback provided by our CPD subscribers &copy;</span></h2>
                        </header>
                    </div>
                    <div class="col-md-4">
                        <div class="testimonial testimonial-bordered p-15">
                            <div class="testimonial-content" style="padding-left: 0px">
                                <p>When a client asks a difficult question I just google SAAA and get the answers</p>
                                <cite>
                                    VW
                                    <span>- Nic CA, RA, CFO, CEO, PM</span>
                                </cite>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="testimonial testimonial-bordered p-15">
                            <div class="testimonial-content" style="padding-left: 0px">
                                <p>With SAAA I expand my skill sets and are then able to launch new product lines in my firm</p>
                                <cite>
                                    VW
                                    <span>- Nic CA, RA, CFO, CEO, PM</span>
                                </cite>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="testimonial testimonial-bordered p-15">
                            <div class="testimonial-content" style="padding-left: 0px">
                                <p>No other provider gives me the same benefits or value for money </p>
                                <cite>
                                    VW
                                    <span>- Nic CA, RA, CFO, CEO, PM</span>
                                </cite>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{--@include('partials.uce')--}}
@endsection