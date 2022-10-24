@extends('app')

@section('content')

@section('title')
    {!! $profession->title !!}
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('profession', $profession->title) !!}
@stop

@section('styles')
    <style>
        .curtian {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: #173175;
            opacity: 0.5;
            z-index: 10;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
            z-index: 10;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endsection
<?php 
$staff = collect();
$company = collect();
if(auth()->check()){ 
    if(auth()->user()->company_admin()){
       $company[] =  auth()->user()->company;
       $staff =  auth()->user()->company->staff;
    }
}

?>

    <section style="background-image: url('/assets/frontend/images/demo/wall2.jpg'); padding-bottom: 10px; padding-top: 30px">

        <div class="container text-center">
            <h4><span>{{ ucwords($profession->title) }}</span> <br> <span style="font-size: 14px">Technical Resource Centre & CPD Subscription</span></h4>
            <hr>

        </div>

        <app-subscriptions-screen  :subscriptions="{{(auth()->user()?auth()->user()->subscription('cpd')->plan:auth()->user())}}" :staff="{{ $staff }}" :companys="{{ $company }}" :plans="{{ $profession->plans }}" :profession="{{ $profession }}"
                                      :user="{{ (auth()->user() ? auth()->user()->load('cards') : auth()->user()) }}" 
                                      :donations="{{ env('DONATIONS_AMOUNT') }}" :bank_codes="{{ json_encode(getBankBranches()) }}" 
                                      inline-template>
            @if ($profession->slug != 'practice-management')
                <div id="app-register-screen" class="container app-screen">

                    {{-- Subscription Plan Selector --}}
                    <div class="col-md-12" v-if="plans.length > 0 && plansAreLoaded && ! forms.subscription.plan">
                        @include('subscriptions.partials.plans.selector')
                    </div>

                    {{-- Plan is Selected --}}
                    <div class="col-md-8 col-md-offset-2" v-if="selectedPlan">

                        {{-- Selected Plan --}}
                        @include('subscriptions.partials.plans.selected')

                        {{-- Donations --}}
                        @include('includes.donation', ['vif' => "donations && selectedPlan && selectedPlanIsYearly", 'vmodel' => "forms.subscription.donations", 'vonchange' => 'getEftLink' ])

                        {{-- Plan features --}}
                        @include('subscriptions.partials.plans.features')

                        {{-- Billing Options --}}
                        @include('subscriptions.partials.billing_options')

                        {{-- Billing Options Details --}}
                        @include('subscriptions.partials.billing_information')

                        {{-- Interested in PI --}}
                        @include('subscriptions.partials.pi')

                        {{-- Terms and Conditions and Complete Subscription Signup --}}
                        @include('subscriptions.partials.terms')
                    </div>
                </div>

                <br>
                <br>
            </app-subscriptions-screen>
        @else
            <div class="text-center">
                <a href="/auth/register" class="btn btn-primary">Sign Up Now</a>
                <br>
                <br>
            </div>
        @endif
    </section>


@if ($profession->slug != 'practice-management')
    <div class="callout-dark heading-arrow-bottom">
        <a class="btn btn-primary size-10 fullwidth " style="background-color: #adb1b2; border-color: transparent;">
            <span style="font-size: 20px">Subscription Plan and Resource Centre access</span>
        </a>
    </div>

    <section style="padding-top: 30px; padding-bottom: 30px">
        <div class="container">
            {!! $profession->features !!}
        </div>
    </section>

    <section class="alternate" style="padding-top: 30px; padding-bottom: 30px">
        <div class="container">
            <div class="heading-title heading-dotted">
                <h4><span>What is included in your subscription plan</span></h4>
            </div>

            {!! $profession->description !!}
        </div>
    </section>

    @if ($profession->slug != 'monthly-legislation-update')
        <section style="padding-top: 30px; padding-bottom: 30px">
            <div class="container">
                <div class="heading-title heading-dotted">
                    <h4><span>Technical Resource Centre  </span></h4>
                </div>

                {!! $profession->resource_center !!}
            </div>
        </section>
    @endif
    
@else

    <div class="callout-dark heading-arrow-bottom">
        <a  class="btn btn-primary size-10 fullwidth " style="background-color: #adb1b2; border-color: transparent;">
            <span style="font-size: 20px">Free CPD Subscription </span>
        </a>
    </div>

    <section style="padding-top: 30px; padding-bottom: 30px">
        <div class="container">
            {!! $profession->features !!}
        </div>
    </section>
    
    <div class="callout-dark heading-arrow-bottom">
        <a  class="btn btn-primary size-10 fullwidth " style="background-color: #adb1b2; border-color: transparent;">
            <span style="font-size: 20px">CPD Subscription content</span>
        </a>
    </div>

    <section>
        <div class="container">
            {!! $profession->description !!}
        </div>
    </section>
@endif

<section class="alternate">
    <div class="container">
        <div class="row">
            <div class="heading-title heading-dotted" style="margin-bottom: 10px">
                <h4><span>Upcoming Events</span></h4>
            </div>
            {{-- <p>Get up to 50% discount on seminars and courses and these exclusive rewards as additional benefits for subscribing with us.</p> --}}
            <br>

            <div class="row">
                <div class="col-md-12">
                    @if(count($events))
                        @foreach($events as $event)
                            @include('events.includes.event_single_block')
                        @endforeach
                        {{-- {!! $events->render() !!} --}}
                    @else
                        <div class="event-container-box clearfix">
                            <h4>No Events was found</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="alternate">
    <div class="container">
        <div class="row">
            <div class="heading-title heading-dotted" style="margin-bottom: 10px">
                <h4><span>Additional benefits of subscribing</span></h4>
            </div>
            <p>Get up to 50% discount on seminars and courses and these exclusive rewards as additional benefits for subscribing with us.</p>
            <br>
            <br>

            @if ($profession->sponsors)
                <?php 
                    $count = 1; 
                ?>
                @foreach ($profession->sponsors as $sponsor)
                    <div class="row"  
                        @if ($count++ % 2 == 0)
                            style="background-color: #e3e3e357; padding-top: 15px; padding-bottom: 15px; margin-top: 15px; margin-bottom: 15px"   
                        @endif
                        >
                        <div class="col-md-2">
                            <img src="{{ asset('storage/'. $sponsor->logo) }}" width="100%" style="margin-bottom: 0px" class="thumbnail" alt="SAIBA">
                        </div>
                        <div class="col-md-10" style="text-align: left">
                            <h4>{{ $sponsor->title }}</h4>
                            <p><small>{{ $sponsor->short_description }}</small></p>
                            <p><strong>Reward:</strong> Up to 15% discount. Find out more.</p>
                            <a href="{{ route('rewards.show', $sponsor->slug) }}" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

@endsection