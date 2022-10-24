@extends('app')

@section('title')
    Events Calendar 2017 - 2018
@stop

@section('intro')
    View our latest and upcoming events
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('events') !!}
@stop

@section('styles')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
    <style>
        p, pre, ul, ol, dl, dd, blockquote, address, table, fieldset, form {
            margin-bottom: 0px;
        }
        .fc-day-grid-event>.fc-content {
            /*white-space: normal!important;*/
        }
    </style>
@endsection


@section('content')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    {!! $calendar->calendar() !!}
                </div>
                <div class="col-md-3">
                    <div class="border-box" style="background-color: #fafafa; border: 1px solid #eee;">
                        <h5>Colour Code Guide</h5>
                        <hr>
                        <span style="background-color: #800000; padding: 0px 10px; border-radius: 50%; margin-right: 12px;"></span><strong>Seminar</strong>
                        <br>
                        <span style="background-color: green; padding: 0px 10px; border-radius: 50%; margin-right: 12px;"></span><strong>Online Webinar</strong>
                    </div>
                    <br>
                    <h5 class="text-center">CPD Subscription Packages</h5>
                    <hr>

                    <div class="owl-carousel owl-padding-10 buttons-autohide controlls-over" data-plugin-options='{"singleItem": false, "items":"1", "autoPlay": 4000, "navigation": true, "pagination": false}'>
                        <div class="img-hover">
                            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);">
                                <h4 style="font-size: 20px!important;">
                                    <span style="font-size: 16px">From</span> <sup>R</sup>250.00<em>/PM</em>
                                </h4>

                                <h5 style="text-transform: uppercase;
                                font-weight: 500;
                                margin: 0;
                                font-size: 10px;
                                color: #547698;
                                letter-spacing: 2px;">BUSINESS ACCOUNTANT IN PRACTICE</h5>
                                <hr>
                                <p>CPD Subscription Package</p>
                                <hr>

                                <a href="/profession/business-accountant-in-practice" class="btn btn-default">Read More</a>
                                <a href="/cpd" class="btn btn-primary">Subscribe</a>

                            </div>
                        </div>

                        <div class="img-hover">
                            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);">
                                <h4 style="font-size: 20px!important;">
                                    <span style="font-size: 16px">From</span> <sup>R</sup>250.00<em>/PM</em>
                                </h4>

                                <h5 style="text-transform: uppercase;
                                font-weight: 500;
                                margin: 0;
                                font-size: 10px;
                                color: #547698;
                                letter-spacing: 2px;">CERTIFIED BOOKKEEPER</h5>
                                <hr>
                                <p>CPD Subscription Package</p>
                                <hr>

                                <a href="/profession/certified-bookkeeper" class="btn btn-default">Read More</a>
                                <a href="/cpd" class="btn btn-primary">Subscribe</a>

                            </div>
                        </div>

                        <div class="img-hover">
                            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);">
                                <h4 style="font-size: 20px!important;">
                                    <span style="font-size: 16px">From</span> <sup>R</sup>250.00<em>/PM</em>
                                </h4>
                                <h5 style="text-transform: uppercase;
                                font-weight: 500;
                                margin: 0;
                                font-size: 10px;
                                color: #547698;
                                letter-spacing: 2px;">CHARTERED ACCOUNTANT</h5>
                                <hr>

                                <p>CPD Subscription Package</p>
                                <hr>

                                <a href="/profession/chartered-accountant" class="btn btn-default">Read More</a>
                                <a href="/cpd" class="btn btn-primary">Subscribe</a>

                            </div>
                        </div>

                        <div class="img-hover">
                            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);">
                                <h4 style="font-size: 20px!important;">
                                    <span style="font-size: 16px">From</span> <sup>R</sup>250.00<em>/PM</em>
                                </h4>
                                <h5 style="text-transform: uppercase;
                                font-weight: 500;
                                margin: 0;
                                font-size: 10px;
                                color: #547698;
                                letter-spacing: 2px;">COMPANY SECRETARY</h5>

                                <hr>
                                <p>CPD Subscription Package</p>
                                <hr>

                                <a href="/profession/company-secretary" class="btn btn-default">Read More</a>
                                <a href="/cpd" class="btn btn-primary">Subscribe</a>

                            </div>
                        </div>

                        <div class="img-hover">
                            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);">
                                <h4 style="font-size: 20px!important;">
                                    <span style="font-size: 16px">From</span> <sup>R</sup>250.00<em>/PM</em>
                                </h4>
                                <h5 style="text-transform: uppercase;
                                font-weight: 500;
                                margin: 0;
                                font-size: 10px;
                                color: #547698;
                                letter-spacing: 2px;">PROFESSIONAL ACCOUNTANT</h5>
                                <hr>
                                <p>CPD Subscription Package</p>
                                <hr>

                                <a href="/profession/professional-accountant" class="btn btn-default">Read More</a>
                                <a href="/cpd" class="btn btn-primary">Subscribe</a>

                            </div>
                        </div>

                        <div class="img-hover">
                            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);">
                                <h4 style="font-size: 20px!important;">
                                    <span style="font-size: 16px">From</span> <sup>R</sup>250.00<em>/PM</em>
                                </h4>
                                <h5 style="text-transform: uppercase;
                                font-weight: 500;
                                margin: 0;
                                font-size: 10px;
                                color: #547698;
                                letter-spacing: 2px;">TAX PRACTITIONER</h5>
                                <hr>
                                <p>CPD Subscription Package</p>
                                <hr>

                                <a href="/profession/tax-practitioner" class="btn btn-default">Read More</a>
                                <a href="/cpd" class="btn btn-primary">Subscribe</a>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
    {!! $calendar->script() !!}
@stop