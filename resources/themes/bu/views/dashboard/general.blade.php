@extends('app')

@section('title')
    Self Service
@stop

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li class="active">Self Service</li>
                    </ol>
                </li>
            </ol>
        </li>
    </ol>
@stop

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-8">

                @if(! $user->subscribed('cpd') && count($user->tickets) < 1)
                    <div class="owl-carousel buttons-autohide controlls-over hidden-sm hidden-xs hidden-md"
                         data-plugin-options='{"singleItem": false, "items": "1", "autoPlay": true, "navigation": true, "pagination": false}'>

                        <a href="/subscriptions" class="no-hover">
                            <blockquote class="text-center" style="background-color: rgb(250, 250, 250); border: 5px solid rgba(0,0,0,0.1); margin-bottom: 0px; margin-top: 0px; padding: 10px">
                                <div class="heading-title heading-dotted text-center" style="margin-bottom: 5px">
                                    <h3 style="background-color: #323264; color: white; margin-bottom: 10px">2018 CPD Subscription Packages</h3>
                                </div>
                                <p>Embracing lifelong learning - through CPD</p>
                                <p>
                                    Business Accountant in Practice, Certified Bookkeeper, Chartered Accountant,
                                    Company Secretary, Professional Accountant, Tax Practitioner
                                </p>
                            </blockquote>
                        </a>
                    </div>
                @else
                    @if($user->nextEvent() && \Carbon\Carbon::now()->startOfDay()->diffInDays($user->nextEvent()->start_date) > 1)
                        <div class="owl-carousel buttons-autohide controlls-over hidden-sm hidden-xs hidden-md"
                             data-plugin-options='{"singleItem": false, "items": "1", "autoPlay": true, "navigation": true, "pagination": false}'>

                                <a href="
                                    @if($user->nextEvent()->invoice && $user->nextEvent()->invoice->status == 'paid')
                                        {{ route('dashboard.tickets.links-and-resources', $user->nextEvent()->id) }}
                                    @elseif(! $user->nextEvent()->invoice)
                                        {{ route('dashboard.tickets.links-and-resources', $user->nextEvent()->id) }}
                                    @else
                                        {{ route('invoices.settle', $user->nextEvent()->invoice->id) }}
                                    @endif
                                " class="no-hover">
                                <blockquote class="text-center" style="background-color: #ffffff; border: 5px solid rgba(0,0,0,0.1); margin-bottom: 0px; margin-top: 0px;
                                padding: 10px; background-image: url('/assets/frontend/images/demo/wall2.jpg'); background-size: cover;">
                                    <div class="heading-title heading-dotted text-center" style="margin-bottom: 5px">
                                        <h3 style="background-color: #800000; color: white; margin-bottom: 10px">
                                            <i class="fa fa-ticket"></i> Your next event is in  {{ \Carbon\Carbon::now()->startOfDay()->diffInDays($user->nextEvent()->start_date) }}
                                            @if(\Carbon\Carbon::now()->startOfDay()->diffInDays($user->nextEvent()->start_date) > 1) days @else day @endif
                                        </h3>
                                    </div>

                                    <p>
                                        {{ $user->nextEvent()->event->name }} <br>
                                        <hr>
                                        Start date: {{ date_format($user->nextEvent()->start_date, 'd F Y') }}
                                        <br>
                                        Venue: {{ str_replace('-', ' ', ucfirst($user->nextEvent()->pricing->venue->type)) }}
                                        <br>
                                        Invoice Status:
                                        @if($user->nextEvent()->invoice && $user->nextEvent()->invoice->status == 'paid')
                                            Paid
                                        @elseif(! $user->nextEvent()->invoice)
                                            Paid
                                        @else
                                            Unpaid
                                        @endif
                                    </p>

                                    <hr>

                                    <button class="btn btn-primary">View details</button>

                                </blockquote>
                            </a>
                        </div>
                    @elseif(count($events))
                        <div class="owl-carousel buttons-autohide controlls-over hidden-sm hidden-xs hidden-md"
                             data-plugin-options='{"singleItem": false, "items": "1", "autoPlay": true, "navigation": true, "pagination": false}'>
                            <a href="{{ route('events.show', $events->first()->slug) }}" class="no-hover">
                                <blockquote class="text-center" style="background-color: #ffffff; border: 5px solid rgba(0,0,0,0.1); margin-bottom: 0px; margin-top: 0px;
                                    padding: 10px; background-image: url('/assets/frontend/images/demo/wall2.jpg'); background-size: cover;">
                                    <div class="heading-title heading-dotted text-center" style="margin-bottom: 5px">
                                        <h3 style="background-color: #800000; color: white; margin-bottom: 10px">
                                           <i class="fa fa-ticket"></i> Upcoming event in {{ date_format($events->first()->start_date, 'F') }}</h3>
                                    </div>
                                    <p>
                                        {{ ucfirst($events->first()->name) }}
                                        <hr>
                                        <p>{{ $events->first()->short_description }}</p>
                                        Start Date: {{ date_format($events->first()->start_date, 'd F Y') }} <br>
                                        Venue:
                                            @if($events->first()->type == 'seminar')
                                               <strong>Seminar & Webinar</strong>
                                            @else
                                                {{ ucfirst($events->first()->type) }}
                                            @endif
                                    </p>

                                    <hr>
                                    <button class="btn btn-primary">Book your seat now</button>
                                </blockquote>
                            </a>
                        </div>
                    @else

                    @endif
                @endif
                <div class="row">
                    <div class="col-md-4">
                        <div class="box-flip box-color box-icon box-icon-center box-icon-round box-icon-large text-center">
                            <div class="front">
                                <div class="box1" style="background-color:#707071;">
                                    <div class="box-icon-title">
                                        <i class="fa fa-plus"></i>
                                        <h2>My CPD</h2>
                                    </div>
                                    <p>Log your CPD Hours and upload your verifiable Certificate</p>
                                </div>
                            </div>

                            <div class="back">
                                <div class="box2">
                                    <h4>My CPD</h4>
                                    <hr/>
                                    <p>Log your CPD Hours and upload your verifiable Certificate</p>
                                    <a href="{{ route('dashboard.cpd') }}" class="btn btn-default">Click Here</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="box-flip box-color box-icon box-icon-center box-icon-round box-icon-large text-center">
                            <div class="front">
                                <div class="box1" style="background-color:rgb(50, 50, 100);">
                                    <div class="box-icon-title">
                                        <i class="fa fa-ticket"></i>
                                        <h2>My Events</h2>
                                    </div>
                                    <p>Access all of your upcoming events and attend webinars.</p>
                                </div>
                            </div>

                            <div class="back">
                                <div class="box2">
                                    <h4>My Events</h4>
                                    <hr/>
                                    <p>Access all of your upcoming events and attend webinars.</p>
                                    <a href="{{ route('dashboard.events') }}" class="btn btn-default">Click Here</a>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4">
                        <div class="box-flip box-color box-icon box-icon-center box-icon-round box-icon-large text-center">
                            <div class="front">
                                <div class="box1" style="background-color:#707071;">
                                    <div class="box-icon-title">
                                        <i class="fa fa-shopping-cart"></i>
                                        <h2>My Products</h2>
                                    </div>
                                    <p>Access your products that you have bought from our store.</p>
                                </div>
                            </div>

                            <div class="back">
                                <div class="box2">
                                    <h4>My Products</h4>
                                    <hr/>
                                    <p>Access your products that you have bought from our store.</p>
                                    <a href="{!! route('dashboard.products') !!}" class="btn btn-default">Click Here</a>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <br>

                <div class="row">
                    <div class="col-md-4">
                        <div class="box-flip box-color box-icon box-icon-center box-icon-round box-icon-large text-center">
                            <div class="front">
                                <div class="box1" style="background-color:rgb(50, 50, 100);">
                                    <div class="box-icon-title">
                                        <i class="fa fa-money"></i>
                                        <h2>Payment Details</h2>
                                    </div>
                                    <p>Update your payment details with Credit Card / Debit Order</p>
                                </div>
                            </div>

                            <div class="back">
                                <div class="box2">
                                    <h4>Payment Details</h4>
                                    <hr/>
                                    <p>Update your payment details with Credit Card / Debit Order</p>
                                    <a href="{!! route('dashboard.billing.index') !!}" class="btn btn-default">Click
                                        Here</a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="box-flip box-color box-icon box-icon-center box-icon-round box-icon-large text-center">
                            <div class="front">
                                <div class="box1" style="background-color:#707071;">
                                    <div class="box-icon-title">
                                        <i class="fa fa-file-pdf-o"></i>
                                        <h2>My Invoices <sup><span
                                                        class="label {{ ($user->overdueInvoices()->count() >0 )? "label-warning" : "label-success" }} rounded"
                                                        style="padding-top: 7px; padding-bottom: 7px">{{ $user->overdueInvoices()->count() }}</span></sup>
                                        </h2>
                                    </div>
                                    <p>View your latest Invoice and settle previous outstanding.</p>
                                </div>
                            </div>

                            <div class="back">
                                <div class="box2">
                                    <h4>My Invoices</h4>
                                    <hr/>
                                    <p>View your latest Invoice and settle previous outstanding.</p>
                                    <a href="{!! route('dashboard.invoices') !!}" class="btn btn-default">Click Here</a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="box-flip box-color box-icon box-icon-center box-icon-round box-icon-large text-center">
                            <div class="front">
                                <div class="box1" style="background-color:rgb(50, 50, 100);">
                                    <div class="box-icon-title">
                                        <i class="fa fa-calculator"></i>
                                        <h2>Account Statement</h2>
                                    </div>
                                    <p>View your total account balance by using your statement.</p>
                                </div>
                            </div>

                            <div class="back">
                                <div class="box2">
                                    <h4>Account Statement</h4>
                                    <hr/>
                                    <p>View your total account balance by using your statement.</p>
                                    <a href="{{ route('dashboard.statement') }}" class="btn btn-default">Click Here</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4">
                        <div class="box-flip box-color box-icon box-icon-center box-icon-round box-icon-large text-center">
                            <div class="front">
                                <div class="box1" style="background-color:green;">
                                    <div class="box-icon-title">
                                        <i class="fa fa-question"></i>
                                        <h2>General Questions</h2>
                                    </div>
                                    <p>Access our FAQ for all of your general questions and answers</p>
                                </div>
                            </div>

                            <div class="back">
                                <div class="box2">
                                    <h4>General Questions</h4>
                                    <hr/>
                                    <p>Access our FAQ for all of your general questions and answers</p>
                                    <a href="{{ route('faq') }}" class="btn btn-default">Click Here</a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="box-flip box-color box-icon box-icon-center box-icon-round box-icon-large text-center">
                            <div class="front">
                                <div class="box1" style="background-color:rgb(50, 50, 100);">
                                    <div class="box-icon-title">
                                        <i class="fa fa-pencil"></i>
                                        <h2>Edit Profile</h2>
                                    </div>
                                    <p>Update your personal details / Update your addresses.</p>
                                </div>
                            </div>

                            <div class="back">
                                <div class="box2">
                                    <h4>Edit Profile</h4>
                                    <hr/>
                                    <p>Update your personal details / Update your addresses.</p>
                                    <a href="{!! route('dashboard.edit') !!}" class="btn btn-default">Click Here</a>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="box-flip box-color box-icon box-icon-center box-icon-round box-icon-large text-center">
                            <div class="front">
                                <div class="box1" style="background-color:green;">
                                    <div class="box-icon-title">
                                        <i class="fa fa-sign-out"></i>
                                        <h2>Logout</h2>
                                    </div>
                                    <p>Click here to log out of your profile.</p>
                                </div>
                            </div>

                            <div class="back">
                                <div class="box2">
                                    <h4>Logout</h4>
                                    <hr/>
                                    <p>Click here to log out of your profile.</p>
                                    <a href="/auth/logout" class="btn btn-default">Click Here</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#cpd_date').datepicker();
        });
    </script>
@endsection