@extends('app')

@section('title')
    Account Overview
@stop

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li class="active">Rewards</li>
                    </ol>
                </li>
            </ol>
        </li>
    </ol>
@stop

@section('styles')
    <style type="text/css">
        .bootstrap-dialog-message form, .bootstrap-dialog-message .form-group {
            margin-bottom: 0;
        }
    </style>
@endsection

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-8">
                <div class="owl-carousel buttons-autohide controlls-over hidden-sm hidden-xs hidden-md"
                     data-plugin-options='{"singleItem": false, "items": "1", "autoPlay": true, "navigation": true, "pagination": false}'>

                    <blockquote class="text-center" style="background-color: rgb(250, 250, 250); border: 5px solid rgba(0,0,0,0.1); margin-bottom: 0px; margin-top: 0px; padding: 10px">
                        <div class="heading-title heading-dotted text-center" style="margin-bottom: 5px">
                            <h3 style="background-color: #323264; color: white; margin-bottom: 10px">Claim my Rewards</h3>
                        </div>
                        <p>Additional benefits of subscribing with SA Accounting Academy. CPD subscribers gain access to the following rewards:</p>
                    </blockquote>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <img src="/assets/frontend/images/sponsors/saiba.jpg" width="100%" style="margin-bottom: 0px" class="thumbnail" alt="SAIBA">
                    </div>
                    <div class="col-md-10" style="text-align: left">
                        <h4>SAIBA - Southern African Institute for Business Accountants</h4>
                        <p><small>Your gateway to the accounting profession. Join. Earn. Share.</small></p>
                        <p><strong>Reward:</strong> 50% discount on a designation fees. Find out more</p>
                        <a href="{{ route('rewards.saiba') }}" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
                    </div>
                </div>

                <div class="row" style="background-color: #e3e3e357; padding-top: 15px; padding-bottom: 15px; margin-top: 15px; margin-bottom: 15px">
                    <div class="col-md-2">
                        <img src="/assets/frontend/images/sponsors/draftworx.jpg" width="100%" style="margin-bottom: 0px" class="thumbnail" alt="SAIBA">
                    </div>
                    <div class="col-md-10" style="text-align: left">
                        <h4>Draftworx Financials Statements & Working Papers</h4>
                        <p><small>Comprehensive Financial and Audit Solution without equal</small></p>
                        <p><strong>Reward:</strong> Up to 15% discount. Find out more.</p>
                        <a href="{{ route('rewards.draftworx') }}" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <img src="/assets/frontend/images/sponsors/bluestar.jpg" width="100%" style="margin-bottom: 0px" class="thumbnail" alt="SAIBA">
                    </div>
                    <div class="col-md-10" style="text-align: left">
                        <h4>First for BlueStar</h4>
                        <p><small>Professional Financial Advisory Services covering Insurance, Financial Planning, Retirement, Investments and Wealth</small></p>
                        <p><strong>Reward:</strong> Professional Indemnity Insurance from R 600 per annum and reduced premiums. Find out more.</p>
                        <a href="{{ route('rewards.bluestar') }}" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
                    </div>
                </div>

                <div class="row" >
                    <div class="col-md-2">
                        <img src="/assets/frontend/images/sponsors/quickbooks.jpg" width="100%" style="margin-bottom: 0px" class="thumbnail" alt="SAIBA">
                    </div>
                    <div class="col-md-10" style="text-align: left">
                        <h4>QuickBooks for Accountants</h4>
                        <p><small>QuickBooks Cloud Accounting Platform: The one place to grow and manage your entire practice.</small></p>
                        <p><strong>Reward:</strong> Free sign up and certification.</p>
                        <a href="{{ route('rewards.quickbooks') }}" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
                    </div>
                </div>

                <div class="row" style="background-color: #e3e3e357; padding-top: 15px; padding-bottom: 15px; margin-top: 15px; margin-bottom: 15px">
                    <div class="col-md-2">
                        <img src="/assets/frontend/images/sponsors/acts.jpg" width="100%" style="margin-bottom: 0px" class="thumbnail" alt="SAIBA">
                    </div>
                    <div class="col-md-10" style="text-align: left">
                        <h4>Acts Online</h4>
                        <p><small>Provides legislation, including amendments and regulations, in an intuitive, online format.</small></p>
                        <p><strong>Reward:</strong> Up to 25% discount. Find out more.</p>
                        <a href="{{ route('rewards.acts') }}" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
                    </div>
                </div>

                <div class="row" style="background-color: #e3e3e357; padding-top: 15px; padding-bottom: 15px; margin-top: 15px; margin-bottom: 15px">
                    <div class="col-md-2">
                        <img src="/assets/frontend/images/sponsors/tax-shop.png" width="100%" style="margin-bottom: 0px" class="thumbnail" alt="SAIBA">
                    </div>
                    <div class="col-md-10" style="text-align: left">
                        <h4>The Tax Shop Accountants - Accounting & Tax Franchise</h4>
                        <p><small>Join the largest accounting and tax franchise in Southern Africa.</small></p>
                        <p><strong>Reward:</strong> Up to 20% discount. Find out more.</p>
                        <a href="{{ route('rewards.taxshop') }}" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop