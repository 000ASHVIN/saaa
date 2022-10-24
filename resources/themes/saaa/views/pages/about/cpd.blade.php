@extends('app')

@section('content')

@section('title')
    Technical Resource Centre & CPD Subscription
@stop

@section('intro')

@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('CPD') !!}
@stop
<section>
    <div class="container">
        <div class="heading-title heading-dotted" style="margin-bottom: 10px">
            <h4><span>Engage your clients with confidence. Anywhere. Anytime.</span></h4>
        </div>
        <p>
            Large accounting firms have a competitive advantage with access to the best technical resources. Do you have what it take to be successful in 2019?
        </p>
        <p>
            Our Technical Resource Centre & CPD Subscription offers small and medium practices (SMP) with access to the most essential technical resources to be successful as an SMP.
        </p>

        <br>
        <br>

        <div class="heading-title heading-dotted" style="margin-bottom: 10px">
            <h4><span>Access options & CPD Subscription Plans</span></h4>
        </div>

        <div class="row mega-price-table">

            <div class="col-md-4 col-sm-6 hidden-sm hidden-xs-down pricing-desc">

                <ul class="list-unstyled">
                    <li><strong>Benefits</strong> <br><br></li>
                    <li>Monthly Practice Management Series</li>
                    <li class="alternate">Monthly Compliance and Legislation Updates</li>
                    <li>5 core CPD topics, 20 verifiable hours</li>
                    <li class="alternate">Up to 10 elective topics</li>
                    <li>Access to Technical Resource Centre</li>
                    <li> -  Ask the opinion of our <a href="/presenters">technical experts</a></li>
                    <li> - Acts Online</li>
                    <li> - On Demand Webinars</li>
                    <li class="alternate">50% discount on seminars</li>
                    <li>25% discount on courses</li>
                </ul>

            </div>

            <div class="col-md-2 col-sm-6 block">
                <div class="pricing">

                    <!-- option list -->
                    <ul class="pricing-table list-unstyled">
                        <li style="background-color: #173175; color: white">
                           <strong> Basic <br> R0/month</strong> <br>
                        </li>
                        <li style="background-color: #c3c0c0;"><i class="fa fa-check"></i></li>
                        <li></li>
                        <li style="background-color: #c3c0c0;"></li>
                        <li></li>
                        <li style="background-color: #c3c0c0;"></li>
                        <li></li>
                        <li style="background-color: #c3c0c0;"></li>
                        <li></li>
                        <li style="background-color: #c3c0c0;"></li>
                        <li></li>
                    </ul>
                    <!-- /option list -->

                    <!-- button -->
                    <div class="btn-group dropup fullwidth">
                        <button type="button" class="btn btn-primary dropdown-toggle fullwidth text-left"
                                data-toggle="dropdown">
                            <i class="fa fa-shopping-cart"></i>
                            Tell me more
                            <span class="caret float-right"></span>
                        </button>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="/profession/practice-management">Basic</a></li>
                        </ul>
                    </div><!-- /button -->

                </div>
            </div>

            <div class="col-md-2 col-sm-6 block">
                <div class="pricing">

                    <!-- option list -->
                    <ul class="pricing-table list-unstyled">
                        <li style="background-color: #173175; color: white">
                            <strong>Essential <br> R250/month </strong><br>
                        </li>
                        <li style="background-color: #c3c0c0;"><i class="fa fa-check"></i></li>
                        <li><i class="fa fa-check"></i></li>
                        <li style="background-color: #c3c0c0;"></li>
                        <li></li>
                        <li style="background-color: #c3c0c0;"></li>
                        <li></li>
                        <li style="background-color: #c3c0c0;"></li>
                        <li></li>
                        <li style="background-color: #c3c0c0;"></li>
                        <li></li>
                    </ul>
                    <!-- /option list -->

                    <!-- button -->
                    <div class="btn-group dropup fullwidth">
                        <button type="button" class="btn btn-primary dropdown-toggle fullwidth text-left"
                                data-toggle="dropdown">
                            <i class="fa fa-shopping-cart"></i>
                            Tell me more
                            <span class="caret float-right"></span>
                        </button>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="/profession/monthly-legislation-update">Essential</a></li>
                        </ul>
                    </div>
                    <!-- /button -->
                </div>
            </div>

            <div class="col-md-2 col-sm-6 block">
                <div class="pricing">

                    <!-- option list -->
                    <ul class="pricing-table list-unstyled">
                        <li style="background-color: #173175; color: white">
                            <strong>Professional from R300/month</strong>
                        </li>
                        <li style="background-color: #c3c0c0; color: white"><i class="fa fa-check"></i></li>
                        <li><i class="fa fa-check"></i></li>
                        <li style="background-color: #c3c0c0; color: white"><i class="fa fa-check"></i></li>
                        <li><i class="fa fa-check"></i></li>
                        <li style="background-color: #c3c0c0; color: white"><i class="fa fa-check"></i></li>
                        <li><i class="fa fa-check"></i></li>
                        <li style="background-color: #c3c0c0; color: white"><i class="fa fa-check"></i></li>
                        <li><i class="fa fa-check"></i></li>
                        <li style="background-color: #c3c0c0; color: white"><i class="fa fa-check"></i></li>
                        <li><i class="fa fa-check"></i></li>
                    </ul>
                    <!-- /option list -->

                    <!-- button -->
                    <div class="btn-group dropup fullwidth">
                        <button type="button" class="btn btn-primary dropdown-toggle fullwidth text-left"
                                data-toggle="dropdown">
                            <i class="fa fa-shopping-cart"></i>
                            Tell me more
                            <span class="caret float-right"></span>
                        </button>

                        <ul class="dropdown-menu" role="menu" style="width: 130%;">
                            <li><a href="/profession/business-accountant-in-practice">Business Accountant in Practice</a></li>
                            <li><a href="/profession/certified-bookkeeper">Bookkeeper</a></li>
                            <li><a href="/profession/chartered-accountant">Chartered Accountant</a></li>
                            <li><a href="/profession/professional-accountant">Professional Accountant</a></li>
                            <li><a href="/profession/tax-practitioner">Tax Practitioner</a></li>
                        </ul>

                    </div><!-- /button -->

                </div>
            </div>

            <div class="col-md-2 col-sm-6 block">
                <div class="pricing">

                    <!-- option list -->
                    <ul class="pricing-table list-unstyled">
                        <li style="background-color: #173175; color: white">
                            <strong>Specialist <br> R450/month</strong>
                        </li>
                        <li style="background-color: #c3c0c0; color: white"><i class="fa fa-check"></i></li>
                        <li><i class="fa fa-check"></i></li>
                        <li style="background-color: #c3c0c0; color: white"><i class="fa fa-check"></i></li>
                        <li><i class="fa fa-check"></i></li>
                        <li style="background-color: #c3c0c0; color: white"><i class="fa fa-check"></i></li>
                        <li><i class="fa fa-check"></i></li>
                        <li style="background-color: #c3c0c0; color: white"><i class="fa fa-check"></i></li>
                        <li><i class="fa fa-check"></i></li>
                        <li style="background-color: #c3c0c0; color: white"><i class="fa fa-check"></i></li>
                        <li><i class="fa fa-check"></i></li>
                    </ul>
                    <!-- /option list -->

                    <!-- button -->
                    <div class="btn-group dropup fullwidth">
                        <button type="button" class="btn btn-primary dropdown-toggle fullwidth text-left"
                                data-toggle="dropdown">
                            <i class="fa fa-shopping-cart"></i>
                            Tell me more
                            <span class="caret float-right"></span>
                        </button>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="/profession/build-your-own">Build Your Own</a></li>
                        </ul>

                    </div><!-- /button -->

                </div>
            </div>

        </div>

        <br>
        <br>

        <p>
            All Webinars and workshops comply with Continuing Professional Development (IFAC International Education Standard 7) as required by SAICA, SAIPA, SAIBA, SAIT, CIMA, ACCA and ICBA and IAC.
        </p>
    </div>

    {{--<div class="heading-title heading-dotted">--}}
    {{--<h4><span>What do I get as CPD Subscriber?</span></h4>--}}
    {{--</div>--}}

    {{--<ul>--}}
    {{--<li>Access to a selected number of face-to-face seminars and webinars</li>--}}
    {{--<li>12 months’ unlimited access to all event, webinar and conference recordings</li>--}}
    {{--<li>Online profile to manage all your CPD events</li>--}}
    {{--<li>Automated management of CPD points and CPD certificates</li>--}}
    {{--<li>Topics that focus on both compliance and the performance of your accounting firm</li>--}}
    {{--<li>Personal Indemnity insurance option at a reduced rate</li>--}}
    {{--</ul>--}}

    {{--<hr>--}}

    {{--<p><strong>Can I join the CPD subscription package after the start of the year?</strong></p>--}}
    {{--<p>--}}
    {{--Yes, you can. If you join partway during the year, you will receive all the recordings in the current month--}}
    {{--that you signed up in. Should you wish to claim CPD hours for past events you will need to purchase these--}}
    {{--individually. Kindly note that your CPD package is subject to change to keep updated with current and--}}
    {{--relevant accounting and tax topics as you continue with your CPD Subscription.--}}
    {{--</p>--}}
    {{--<p><strong>When do I get access to my CPD?</strong></p>--}}
    {{--<p>Once you have signed up you will receive access to your personal profile. All the CPD events--}}
    {{--(webinars/seminars) that happened during the month you signed up in will be automatically allocated to your--}}
    {{--profile. You will have instant access to your current CPD events and can start claiming your CPD hours.</p>--}}
    {{--</div>--}}
</section>

@endsection