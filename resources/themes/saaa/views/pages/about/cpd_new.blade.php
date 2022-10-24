@extends('app')

@section('content')

@section('title')
    CPD Subscription Packages 2018
@stop

@section('intro')
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('CPD') !!}
@stop
<section>
    <div class="container">
        <div class="heading-title heading-dotted">
            <h4><span>CPD Subscriptions 2019</span></h4>
        </div>
        <p>Cost effective, high quality and accessible CPD</p>
        <br>
        <div class="heading-title heading-dotted">
            <h4><span>Get all the answers, when you need them and how you need them.</span></h4>
        </div>
        <p>
            We developed specific CPD subscription package to suite your designation, so whether you are a Business
            Accountant in Practice, Professional Accountant, Chartered Accountant, or any other designation, our CPD
            gives you everything you need to remain up-to-date and compliant at the lowest fees possible.
        </p>

        <br>
        <br>

        <div class="heading-title heading-dotted">
            <h4><span>Compare our full set of features</span></h4>
        </div>

        <div class="row mega-price-table">

            <div class="col-md-4 col-sm-6 hidden-sm hidden-xs-down pricing-desc">

                <ul class="list-unstyled">
                    <li>Features <br><br></li>
                    <li>Monthly Practice Management Series</li>
                    <li class="alternate">Monthly Compliance and Legislation Updates</li>
                    <li>Monthly Compliance and Legislation Updates</li>
                    <li class="alternate">Up to 10 elective topics</li>
                    <li>Access to Technical Resource Centre</li>
                    <li class="alternate">50% discount on seminars</li>
                    <li>50% discount on courses</li>
                </ul>

            </div>

            <div class="col-md-2 col-sm-6 block">
                <div class="pricing">

                    <!-- option list -->
                    <ul class="pricing-table list-unstyled">
                        <li>
                            Starter R0.00 <br><br>
                        </li>
                        <li class="alternate">
                            <i class="fa fa-check"></i>
                        </li>
                        <li></li>
                        <li class="alternate"></li>
                        <li></li>
                        <li class="alternate"></li>
                        <li></li>
                        <li class="alternate"></li>
                    </ul>
                    <!-- /option list -->

                    <!-- button -->
                    <div class="btn-group dropup fullwidth">
                        <button type="button" class="btn btn-primary dropdown-toggle fullwidth text-left"
                                data-toggle="dropdown">
                            <i class="fa fa-shopping-cart"></i>
                            Sign Up
                            <span class="caret float-right"></span>
                        </button>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Starter</a></li>
                        </ul>
                    </div><!-- /button -->

                </div>
            </div>

            <div class="col-md-2 col-sm-6 block">
                <div class="pricing">

                    <!-- option list -->
                    <ul class="pricing-table list-unstyled">
                        <li>
                            Basic R 250 / month <br><br>
                        </li>
                        <li class="alternate">
                            <i class="fa fa-check"></i>
                        </li>
                        <li>
                            <i class="fa fa-check"></i>
                        </li>
                        <li class="alternate"></li>
                        <li></li>
                        <li class="alternate"></li>
                        <li></li>
                        <li class="alternate"></li>
                    </ul>
                    <!-- /option list -->

                    <!-- button -->
                    <div class="btn-group dropup fullwidth">
                        <button type="button" class="btn btn-primary dropdown-toggle fullwidth text-left"
                                data-toggle="dropdown">
                            <i class="fa fa-shopping-cart"></i>
                            Sign Up
                            <span class="caret float-right"></span>
                        </button>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Basic</a></li>
                        </ul>
                    </div>
                    <!-- /button -->
                </div>
            </div>

            <div class="col-md-2 col-sm-6 block">
                <div class="pricing">

                    <!-- option list -->
                    <ul class="pricing-table list-unstyled">
                        <li>
                            Designation From R 445 / month
                        </li>
                        <li class="alternate">
                            <i class="fa fa-check"></i>
                        </li>
                        <li>
                            <i class="fa fa-check"></i>
                        </li>
                        <li class="alternate">
                            <i class="fa fa-check"></i>
                        </li>
                        <li>
                            <i class="fa fa-check"></i>
                        </li>
                        <li class="alternate">
                            <i class="fa fa-check"></i>
                        </li>
                        <li>
                            <i class="fa fa-check"></i>
                        </li>
                        <li class="alternate">
                            <i class="fa fa-check"></i>
                        </li>
                    </ul>
                    <!-- /option list -->

                    <!-- button -->
                    <div class="btn-group dropup fullwidth">
                        <button type="button" class="btn btn-primary dropdown-toggle fullwidth text-left"
                                data-toggle="dropdown">
                            <i class="fa fa-shopping-cart"></i>
                            Sign Up
                            <span class="caret float-right"></span>
                        </button>

                        <ul class="dropdown-menu" role="menu" style="width: 130%;">
                            <li><a href="#">Business Accountant in Practice</a></li>
                            <li><a href="#">Bookkeeper</a></li>
                            <li><a href="#">Chartered Accountant</a></li>
                            <li><a href="#">Professional Accountant</a></li>
                            <li><a href="#">Tax Practitioner</a></li>
                        </ul>

                    </div><!-- /button -->

                </div>
            </div>

            <div class="col-md-2 col-sm-6 block">
                <div class="pricing">

                    <!-- option list -->
                    <ul class="pricing-table list-unstyled">
                        <li>
                            Build your own R 445 / month
                        </li>
                        <li class="alternate">
                            <i class="fa fa-check"></i>
                        </li>
                        <li>
                            <i class="fa fa-check"></i>
                        </li>
                        <li class="alternate">
                            <i class="fa fa-check"></i>
                        </li>
                        <li>
                            <i class="fa fa-check"></i>
                        </li>
                        <li class="alternate">
                            <i class="fa fa-check"></i>
                        </li>
                        <li>
                            <i class="fa fa-check"></i>
                        </li>
                        <li class="alternate">
                            <i class="fa fa-check"></i>
                        </li>
                    </ul>
                    <!-- /option list -->

                    <!-- button -->
                    <div class="btn-group dropup fullwidth">
                        <button type="button" class="btn btn-primary dropdown-toggle fullwidth text-left"
                                data-toggle="dropdown">
                            <i class="fa fa-shopping-cart"></i>
                            Sign Up
                            <span class="caret float-right"></span>
                        </button>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Build Your Own</a></li>
                        </ul>

                    </div><!-- /button -->

                </div>
            </div>

        </div>

        <br>
        <br>

        <p>
            * All our CPD Subscription Package comply with the International Education Standard 7, Continuing
            Professional Development which are suitable for all accountancy professional bodies.
        </p>
        <p>
            We bring you CPD content from leading industry professionals that is high quality CPD in order to assist you
            in offering high quality services.
        </p>
        <p>
            Our CPD is always easily accessible, up-to-date and relevant.
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