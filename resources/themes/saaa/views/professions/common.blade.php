<div id="app-register-screen" class=" app-screen">
           
    {{-- Subscription Plan Selector --}}
    <div class="col-md-12" v-if="plans.length > 0 && plansAreLoaded && ! forms.subscription.plan">

        @include('subscriptions.partials.plans.draftselector')
 

    </div>

    {{-- Plan is Selected --}}
    <div class="col-md-8 col-md-offset-2" v-if="selectedPlan">

        {{-- Selected Plan --}}
        @include('subscriptions.partials.plans.selected')

        {{-- Donations --}}
        @include('includes.donation', ['vif' => "donations && selectedPlan && selectedPlanIsYearly", 'vmodel' => "forms.subscription.donations", 'vonchange' => 'getEftLink'])

        {{-- Plan features --}}
        @include('subscriptions.partials.plans.features')

        {{-- Billing Options --}}
        @include('subscriptions.partials.billing_options')

        {{-- Billing Options Details --}}
        @include('subscriptions.partials.billing_information')

        {{-- Terms and Conditions and Complete Subscription Signup --}}
        @include('subscriptions.partials.terms')

    </div>
<div class="text-center ">
    <p>Not sure which subscription plan is for you?</p>
    <a href="#" data-target="#need_help_subscription" data-toggle="modal" target="_blank" style="margin-bottom: 10px; background-color: #162e6e;" class="btn btn-primary btn-lg ">Need Help ?</a>   
    </div>
 </div>


<br>
<br>