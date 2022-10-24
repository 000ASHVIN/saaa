@extends('app')

@section('content')

@section('title')
    Additional benefits of subscribing
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('rewards') !!}
@stop

@section('styles')
    <style>
        .verticalLine {
            border-right: thick solid #e3e3e3;
        }
    </style>
@endsection

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="heading-title heading-dotted text-center">
                        <h4>Additional benefits of subscribing with {{ config('app.name') }}. <br> <span>CPD subscribers gain access to the following rewards:</span></h4>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="col-md-9 col-sm-9 col-lg-9">
                @if(count($sponsorList))
                @foreach($sponsorList as $s)
                
                <div class="row" style="background-color: #e3e3e357; padding-top: 15px; padding-bottom: 15px; margin-bottom: 15px">
                    <div class="col-md-2">
                   
                    
                        <img src="{{ asset('storage/'. $s->logo) }}" width="100%" style="margin-bottom: 0px" class="thumbnail" alt="SAIBA">
                    </div>  
                    <div class="col-md-10" style="text-align: left">
                        <h4>{{ $s->title }}</h4>
                        <p>{!! $s->short_description !!}</p>  
                        <a href="{{ route('rewards.show', $s->slug) }}" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
                    </div>
                </div>
                @endforeach  
                @endif 
            </div>

            <div class="col-md-3">
                <div class="owl-carousel buttons-autohide controlls-over" data-plugin-options='{"singleItem": false, "items":"1", "autoPlay": 4000, "navigation": true, "pagination": false}'>
                    <div class="img-hover">
                        <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);">
                            <p> Become a CPD subscriber from R 250 per month to access these rewards</p>
                            <hr>

                            <a href="/subscription_plans" class="btn btn-default btn-block">Read More</a>

                        </div>
                    </div>
                </div>
                <hr>
                <div class="border-box">
                    @include('partials.newsletter_form')
                </div>
            </div>
        </div>
    </div>
</section>

<!-- <section>
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="row">
                    <div class="heading-title heading-dotted text-center">
                        <h4>Additional benefits of subscribing with SA Accounting Academy. <br> <span>CPD subscribers gain access to the following rewards:</span></h4>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="col-md-9 col-sm-9 col-lg-9">

                @if(in_array('ednvest',$sponsor))
                <div class="row" style="background-color: #e3e3e357; padding-top: 15px; padding-bottom: 15px; margin-bottom: 15px">
                    <div class="col-md-2">
                        <img src="/assets/frontend/images/sponsors/EdNVest logo.png" width="100%" style="margin-bottom: 0px" class="thumbnail" alt="SAIBA">
                    </div>
                    <div class="col-md-10" style="text-align: left">
                        <h4>EdNVest</h4>
                        <p>EdNVest is a team of people who are committed to empowering and creating unrealized value for the people of South Africa in a meaningful and easily accessible way</p>
                         <a href="{{ route('rewards.EdNVest') }}" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
                    </div>
                </div>
                @endif 

                @if(in_array('infodocs',$sponsor))
                <div class="row" style="background-color: #e3e3e357; padding-top: 15px; padding-bottom: 15px; margin-bottom: 15px">
                    <div class="col-md-2"> 
                        <img src="/assets/frontend/images/sponsors/infodocs-partner-logo-square.png" width="100%" style="margin-bottom: 0px" class="thumbnail" alt="SAIBA">
                    </div>
                    <div class="col-md-10" style="text-align: left">
                        <h4>InfoDocs</h4>
                        <p>InfoDocs Company Secretarial Software</p>
                         <a href="{{ route('rewards.InfoDocs') }}" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
                    </div>
                </div>
                @endif 


                @if(in_array('saiba',$sponsor))
                <div class="row" style="background-color: #e3e3e357; padding-top: 15px; padding-bottom: 15px; margin-bottom: 15px">
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
                @endif

                @if(in_array('draftworx',$sponsor))
                <div class="row">
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
                @endif

               

                @if(in_array('quickbooks',$sponsor))
                <div class="row" style="background-color: #e3e3e357; padding-top: 15px; padding-bottom: 15px; margin-top: 15px; margin-bottom: 15px" >
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
                @endif

                @if(in_array('taxshop',$sponsor))
                <div class="row"">
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
                @endif

                @if(in_array('acts',$sponsor))
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
                @endif
            </div>

            <div class="col-md-3">
                <div class="owl-carousel buttons-autohide controlls-over" data-plugin-options='{"singleItem": false, "items":"1", "autoPlay": 4000, "navigation": true, "pagination": false}'>
                    <div class="img-hover">
                        <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);">
                            <p> Become a CPD subscriber from R 250 per month to access these rewards</p>
                            <hr>

                            <a href="/subscription_plans" class="btn btn-default btn-block">Read More</a>

                        </div>
                    </div>
                </div>
                <hr>
                <div class="border-box">
                    @include('partials.newsletter_form')
                </div>
            </div>
        </div>
    </div>
</section> -->

@endsection

@section('scripts')
    <script>
        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
    </script>
@endsection
