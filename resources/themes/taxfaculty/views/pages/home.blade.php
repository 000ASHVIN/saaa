@extends('app')

@section('meta_tags')
    <title>{{ config('app.name') }}</title>
    <meta name="description" content="The {{ config('app.name') }} offers tax education and training to accountants, auditors, bookkeepers, company secretaries and tax practitioners. We offer a range of live seminars and conferences and online webinars, seminar recordings, certificate courses and DVDs on both technical and business-related topics.">
    <meta name="Author" content="{{ config('app.name') }}"/>
@endsection

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets\themes\taxfaculty\css\owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets\themes\taxfaculty\css\owl.theme.default.min.css')}}">
    <style>
        .heading-arrow-top:after {
            content: ' ';
            position: absolute;
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-bottom: 20px solid #173175;
            left: 50%;
            margin-left: -20px;
            top: -20px;
        }

        .heading-arrow-bottom:after {
            border-bottom: none;
        }
        @media screen and (min-width: 768px) {
            ul.clients-dotted>li {
                display: flex;
                justify-content: center;
            }
            ul.clients-dotted>li img {
                align-self: center;
            }
        }
        li.no-left-box-border:before{
            content:'';
            border-left:none !important;
        }
    </style>
@endsection


@section('content')
@if(isset($acts) || isset($faqs) || isset($articles) || isset($webinars) || isset($events) || isset($tickets))
    <section class="alternate">
        <div class="container">

        <div class="col-md-12">

            <div class="row mix-grid">
                <ul class="nav nav-pills mix-filter" style="text-transform: capitalize;">
                    @if (count($acts))<li data-filter="acts" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Acts ({{ $actsCount }})</a></li>@endif
                    @if (count($faqs))<li data-filter="faq" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> FAQ's ({{ $faqsCount }})</a></li>@endif
                    @if (count($articles))<li data-filter="articles" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Articles ({{ $articlesCount }})</a></li>@endif
                    @if (count($webinars))<li data-filter="webinars" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Webinars on-demand ({{ $webinarsCount }})</a></li>@endif
                    @if (count($events))<li data-filter="events" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Upcoming Events ({{ $eventsCount }})</a></li>@endif
                    @if (count($tickets))<li data-filter="tickets" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Tickets ({{ $ticketsCount }})</a></li>@endif
                </ul>

                <div class="divider" style="margin: 0px"></div>

                <div class="toggle toggle-transparent toggle-bordered-simple" id="records">
                @if(count($allRecords))
                    @foreach($allRecords as $item)
                        @if($item->search_type == 'articles')
                            <div class="toggle articles mix filter_result" data-date="{{ date_format($item->created_at, 'Y-m-d') }}">
                                @include('resource_centre.includes.item', ['created' => date_format($item->created_at, 'd F Y'), 'link' => route('news.show', $item->slug), 'title' => $item->title, 'description' => str_limit(strip_tags($item->description), 250)])
                            </div>
                        @endif

                        @if($item->search_type == 'faqs')
                            <div class="toggle faq mix filter_result" data-date="{{ date_format($item->created_at, 'Y-m-d') }}">
                                @include('resource_centre.includes.item', ['created' => date_format($item->created_at, 'd F Y'), 'link' => route('resource_centre.technical_faqs.index'), 'title' => $item->question, 'description' => str_limit(strip_tags($item->answer), 250)])
                            </div>
                        @endif

                        @if($item->search_type == 'acts')
                            <div class="toggle acts mix filter_result" data-date="{{ date_format($item->created_at, 'Y-m-d') }}">
                                @include('resource_centre.includes.item', ['created' => date_format($item->created_at, 'd F Y'), 'link' => route('resource_centre.acts.show', $item->slug), 'title' => $item->name, 'description' => str_limit(strip_tags($item->description), 250)])
                            </div>
                        @endif

                        @if($item->search_type == 'webinars')
                            <div class="toggle webinars mix filter_result" data-date="{{ date_format($item->created_at, 'Y-m-d') }}">
                                @include('resource_centre.includes.item', ['created' => date_format($item->created_at, 'd F Y'), 'link' => route('webinars_on_demand.show', $item->slug), 'title' => $item->title, 'description' => str_limit(strip_tags($item->description), 250)])
                            </div>
                        @endif

                        @if($item->search_type == 'events')
                            <div class="toggle events mix filter_result" data-date="{{ date_format($item->created_at, 'Y-m-d') }}">
                                @include('resource_centre.includes.item', ['created' => date_format($item->created_at, 'd F Y'), 'link' => ($item->is_redirect ? $item->redirect_url : route('events.show', $item->slug)), 'title' => $item->name, 'description' => str_limit(strip_tags($item->description), 250)])
                            </div>
                        @endif

                        @if($item->search_type == 'tickets')
                            <div class="toggle tickets mix filter_result" data-date="{{ date_format($item->created_at, 'Y-m-d') }}">
                                @include('resource_centre.includes.item', ['created' => date_format($item->created_at, 'd F Y'), 'link' => route('resource_centre.legislation.show', $item->thread_id), 'title' => $item->subject, 'description' => str_limit(strip_tags($item->description), 250)])
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="toggle tickets mix filter_result">
                            <h2>No data found</h2>
                    </div>
                    <hr>
                @endif
                </div>  
            </div>

            @if($actsCount > 5 || $articlesCount > 5 || $faqsCount > 5 || $eventsCount > 5 || $webinarsCount > 5  || $ticketsCount > 5)
            <div class="row">
            <div class="col-md-10">
                <!-- <a href="{{ route('home') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back Home</a> -->
            </div>
            <div class="col-md-2">
                {!! Form::open(['method' => 'post', 'route' => 'resource_centre.search']) !!}
                    <input type="hidden" value="{{ request()->search }}" name="search">
                    <button type="submit" name="submit" value="all_records" class="btn btn-success">All Records <i class="fa fa-arrow-right"></i></button>
                {!! Form::close() !!}
            </div>
            </div>
            @endif
        </div>  

        </div>
    </section>
@endif

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="tax-offrings">
                        <h2 class="text-center">UPSKILL WITH OUR FUTURE-PROOF TAX OFFERINGS</h2>
            
                        <div class="tax-content">
                            <div class="content-box text-center box-1">
                                <img src="{{ Theme::asset('img/logos/office-of-the-tax-ombud-logo.jpg') }}" alt="courses" style="width: 100%">
                                <h5>Accredited online courses in taxation</h5>
                                <p>Ensure your skills are on par with your ambition, find the perfect course here</p>
                                <a href="" class="right-arrow"><i class="fa fa-arrow-right" style="color: white;"></i></a>
                            </div>
                            <div class="content-box text-center box-2">
                                <img src="{{ Theme::asset('img/logos/office-of-the-tax-ombud-logo.jpg') }}" alt="courses" style="width: 100%">
                                <h5>Individual and practitice solutions</h5>
                                <p>Access to professional and technical content ensuring you remain professionally competent </p>
                                <a href="" class="right-arrow"><i class="fa fa-arrow-right" style="color: white;"></i></a>
                            </div>
                            <div class="content-box text-center box-3">
                                <img src="{{ Theme::asset('img/logos/office-of-the-tax-ombud-logo.jpg') }}" alt="courses" style="width: 100%">
                                <h5>Webinars on-demand</h5>
                                <p>Missed a recent webinar? You can catch-up here, available anywhere, anytime</p>
                                <a href="" class="right-arrow"><i class="fa fa-arrow-right" style="color: white;"></i></a>
                            </div>
                            <div class="content-box text-center box-4">
                                <img src="{{ Theme::asset('img/logos/office-of-the-tax-ombud-logo.jpg') }}" alt="courses" style="width: 100%">
                                <h5>Upcoming events</h5>
                                <p>Tax and accounting topics presented by leading tax experts, book here for our interactive sessions and gain CPD points</p>
                                <a href="" class="right-arrow"><i class="fa fa-arrow-right" style="color: white;"></i></a>
                            </div>
                            <div class="content-box text-center box-5">
                                <img src="{{ Theme::asset('img/logos/office-of-the-tax-ombud-logo.jpg') }}" alt="courses" style="width: 100%">
                                <h5>Tax resource centre and library</h5>
                                <p>Our experts research your technical problems so you don’t have to</p>
                                <a href="" class="right-arrow"><i class="fa fa-arrow-right" style="color: white;"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="alternate">
        <div class="container">
            <div class="popular-title">
                <h2 class="text-center">POPULAR RIGHT NOW</h2>
            </div>
            
            <div class="popular-carousel-section">
                <div class="popular-carousel owl-carousel owl-theme owl-loaded">
                    <div class="owl-stage-outer">
                        <div class="owl-stage">
                            
                            @for($i = 1; $i <= 8; $i++)
                            <div class="owl-item">
                                <div class="card">
                                    <div class="overlay_image">
                                        <a href="#">
                                            <img src="{{ asset('assets/themes/taxfaculty/img/courseImg.jpg') }}" alt="">
                                        </a>
                                    </div>
                                    
                                    <div class="card-body text-left">
                                      <h5 class="card-title"><b>POPIA – Key Implications for Tax Practitioners</b></h5>
                                      <p class="card-text">R475.00 | 2 CPD-hours</p>

                                      <a href="#" class="btn btn-primary">Read more</a>
                                    </div>
    
                                </div>
                            </div>
                            @endfor
                            
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section>
        <div class="container">
            <div class="our-partners">
                <h2 class="text-center">OUR PARTNERS</h2>
                <div>
                    <div class="partners-carousel owl-carousel owl-theme owl-loaded">
                        <div class="owl-stage-outer">
                            <div class="owl-stage">
                                <div class="owl-item">
                                    <div class="owl-image">
                                        <img class="img-fluid" width="95%" src="{{ Theme::asset('img/logos/fpi_logo.jpg') }}" alt="Wits" />
                                    </div>
                                </div>
                                <div class="owl-item">
                                    <div class="owl-image">
                                        <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/saaa.png') }}" alt="SAIBA" />
                                    </div>
                                </div>
                                <div class="owl-item">
                                    <div class="owl-image">
                                        <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/bdo.png') }}" alt="Draftworx" />
                                    </div>
                                </div>
                                <div class="owl-item">
                                    <div class="owl-image">
                                        <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/fnb.png') }}" alt="BlueStar" />
                                    </div>
                                </div>
                                {{-- <div class="owl-item">
                                    <div class="owl-image">
                                        <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/biz.png') }}" alt="Quickbooks" />
                                </div> --}}
                                <div class="owl-item">
                                    <div class="owl-image">
                                        <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/mazaars-new.png') }}" alt="Acts Online" />
                                    </div>
                                </div>
                                {{-- <div class="owl-item">
                                    <div class="owl-image">
                                        <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/icba.png') }}" alt="UNISA" />
                                    </div>
                                </div> --}}
                                <div class="owl-item">
                                    <div class="owl-image">
                                        <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/sage.jpg') }}" alt="The Tax Shop" />
                                    </div>
                                </div>
                                <div class="owl-item">
                                    <div class="owl-image">
                                        <img class="img-fluid"   width="70%" src="{{ Theme::asset('img/logos/wits.png') }}" alt="Wits" />
                                    </div>
                                </div>
                                <div class="owl-item">
                                    <div class="owl-image">
                                        <img class="img-fluid" width="95%" src="{{ Theme::asset('img/logos/BOWMANS.png') }}" alt="Wits" />
                                    </div>
                                </div>
                                <div class="owl-item">
                                    <div class="owl-image">
                                        <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/old_mutual_logo.png') }}" alt="The Tax Shop" />
                                    </div>
                                </div>
                                <div class="owl-item">
                                    <div class="owl-image">
                                        <img class="img-fluid"   width="70%" src="{{ Theme::asset('img/logos/CWA Adapt IT-Stacked.png') }}" alt="Wits" />
                                    </div>
                                </div>
                                {{-- <div class="owl-item">
                                    <div class="owl-image">
                                        <img class="img-fluid"  src="{{ Theme::asset('img/logos/New Logo.png') }}" alt="Wits" />
                                </div> --}}
                                <div class="owl-item">
                                    <div class="owl-image">
                                        <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/New SAIT (1).jpg') }}" alt="The SAIT" />
                                    </div>
                                </div>
                                <div class="owl-item">
                                    <div class="owl-image">
                                        <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/WW_Logo_CMYK.jpg') }}" alt="Wits" />
                                    </div>
                                </div>
                                <div class="owl-item">
                                    <div class="owl-image">
                                        <img class="img-fluid" width="95%" src="{{ Theme::asset('img/logos/FISA.jpg') }}" alt="Fisa" />
                                    </div>
                                </div>
                                <div class="owl-item">
                                    <div class="owl-image">
                                        <img class="img-fluid" width="95%" src="{{ Theme::asset('img/logos/PwC.jpeg') }}" alt="PwC" />
                                    </div>
                                </div>
                                <div class="owl-item">
                                    <div class="owl-image">
                                        <img class="img-fluid" width="95%" src="{{ Theme::asset('img/logos/office-of-the-tax-ombud-logo.jpg') }}" alt="office-of-the-tax-ombud-logo" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- <div class="callout-dark heading-arrow-top">
        <a href="/auth/register" class="btn btn-xlg btn-primary size-10 fullwidth nomargin bopadding noradius ">
            <span class="font-lato size-30"><span style="font-size: 20px" class="countTo" data-speed="3000">{{ $users -1, 2  }}</span> <span style="font-size: 20px">Tax Practitioners, Accountants and Auditors are already part of our network of subscribers</span></span>
            <span class="block font-lato">Why don't you <span style="text-decoration: underline;">join</span> the network and be number <span class="countTo" data-speed="3000">{{ $users - 1 +1 }}</span> ?</span>
        </a>
    </div>

    <section>
        <div class="row">
            <div class="col-md-12">
                <header class="margin-bottom-40 text-center">
                    <h1 class="weight-300">Network of Support and Collaboration</h1>
                    {{-- <h2 class="weight-300 letter-spacing-1 size-13"><span>We have teamed up with..</span></h2> --}}
                    <h2 class="weight-300 letter-spacing-1 size-12"><span>Advancing learning, fiscal citizenship and nation-building</span></h2>
                </header>
            </div>
        </div>
        <div class="container">
            <ul class="row clients-dotted list-inline">
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" style="height: 157px;" width="95%" src="{{ Theme::asset('img/logos/fpi_logo.jpg') }}" alt="Wits" />
                </li>
                <li class="col-md-5th col-sm-5th col-6" style="height: 242px;">
                    <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/saiba.png') }}" alt="SAIBA" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/New SAIT (1).jpg') }}" alt="The SAIT" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="95%" src="{{ Theme::asset('img/logos/FISA.jpg') }}" alt="Fisa" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="95%" src="{{ Theme::asset('img/logos/office-of-the-tax-ombud-logo.jpg') }}" alt="office-of-the-tax-ombud-logo" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="95%" src="{{ Theme::asset('img/logos/PwC.jpeg') }}" alt="PwC" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="95%" src="{{ Theme::asset('img/logos/cliffe dekker hofmeyr.png') }}" alt="CLIFFE DEKKER HOFMEYR" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" style="height: 157px;" width="70%" src="{{ Theme::asset('img/logos/WW_Logo_CMYK.jpg') }}" alt="Wits" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/bdo.png') }}" alt="Draftworx" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/mazaars-new.png') }}" alt="Acts Online" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/TradeTax.png') }}" alt="TRADETAX PLUS" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/fnb.png') }}" alt="BlueStar" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" style="height: 157px;" width="95%" src="{{ Theme::asset('img/logos/BOWMANS.png') }}" alt="Wits" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="95%" src="{{ Theme::asset('img/logos/Wealth-Logo-Green.png') }}" alt="OLD MUTUAL WEALTH" />
                </li>
                <li class="col-md-5th col-sm-5th col-6"></li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/saaa.png') }}" alt="SAIBA" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid"   width="70%" src="{{ Theme::asset('img/logos/Probeta.png') }}" alt="Wits" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/sage.jpg') }}" alt="The Tax Shop" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="95%" src="{{ Theme::asset('img/logos/Greatsoft.jpeg') }}" alt="GREATSOFT" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="95%" src="{{ Theme::asset('img/logos/Draftworx.jpeg') }}" alt="DRAFT WORX" />
                </li>
                <li class="col-md-5th col-sm-5th col-6"></li>
                <li class="col-md-5th col-sm-5th col-6 no-left-box-border"></li>
                <li class="col-md-5th col-sm-5th col-6" style="border-right: 1px dashed rgba(0,0,0,0.3);">
                    <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/caseware.png') }}" alt="The Tax Shop" />
                </li>
                {{-- <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/biz.png') }}" alt="Quickbooks" />
                </li> --}}
                {{-- <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="{{ Theme::asset('img/logos/icba.png') }}" alt="UNISA" />
                </li> --}}
                {{-- <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid"  style="height: 157px;"  width="70%" src="{{ Theme::asset('img/logos/CWA Adapt IT-Stacked.png') }}" alt="Wits" />
                </li> --}}
                {{-- <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" style="height: 157px;"  src="{{ Theme::asset('img/logos/New Logo.png') }}" alt="Wits" />
                </li> --}}
            </ul>
        </div>
    </section> -->
@endsection

@section('scripts')
    <script src="{{ asset('assets\themes\taxfaculty\js\owl.carousel.min.js')}}"></script>
    <script>
        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
        $(document).ready(function(){
            
	       // setTimeout(function(){ $('.custom_filter').first().click();}, 3000);

            var filter = $("ul.mix-filter li:first").data('filter');
            if(filter != null){
                $('.filter_result').hide();
                $('.filter_result.' + filter).show();
            }

            $('.custom_filter').on('click', function(){
                $('.custom_filter').removeClass('active');
                if(!$(this).hasClass('active')) {
                    var filter = $(this).data('filter');
                    $('.filter_result').hide();
                    $('.filter_result.' + filter).show();
                }
            })
            
            function setLiHeight() {
                if($(window).width() > 768) {
                    var height = 0;
                    $('ul.clients-dotted>li').each(function() {
                        var liHeight = $(this).height();
                        if(liHeight > height) {
                            height = liHeight;
                        }
                    })
                    $('ul.clients-dotted>li').height(height);
                } else {
                    $('ul.clients-dotted>li').css('height', 'unset');
                }
            }
            setLiHeight();
            $(window).resize(() => {
                setLiHeight();
            })

            @if(isset($allRecords) && count($allRecords))
                var divList = $(".filter_result");
                divList.sort(function(a, b) {
                    return new Date($(b).data("date")) - new Date($(a).data("date"));
                });
                $("#records").html(divList);
            @endif

            $(".popular-carousel").owlCarousel({
                loop:true,
                margin:20,
                // responsiveClass:true,
                nav:true,
                navText : ['<div class="owl-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>','<div class="owl-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>'],
                autoplay: true,
                autoPlaySpeed: 10000,
                responsive:{
                    0:{
                        items:1,
                        loop:true
                    },
                    768:{
                        items:3,
                        loop:true
                    },
                    992: {
                        items:4,
                        loop:true
                    }
                }
            });

            $(".partners-carousel").owlCarousel({
                loop:true,
                margin:20,
                // responsiveClass:true,
                nav:true,
                autoplay: true,
                autoPlaySpeed: 10000,
                responsive:{
                    0:{
                        items:1,
                        loop:true
                    },
                    768:{
                        items:3,
                        loop:true
                    },
                    992: {
                        items:5,
                        loop:true
                    }
                }
            });
        });

    </script>
@stop