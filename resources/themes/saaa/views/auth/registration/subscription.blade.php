@extends('app')

@section('content')
    <section style="background-image: url('/assets/frontend/images/demo/wall2.jpg');">
        <app-subscription-register-screen inline-template>
            <div id="app-register-screen" class="container app-screen">

                <!-- Subscription Plan Selector -->
                <div class="col-md-12" v-if="plans.length > 1 && plansAreLoaded && ! forms.registration.plan">
                    @include('auth.registration.subscription.plans.selector')
                </div>

                <!-- User Information -->
                <div class="col-md-8 col-md-offset-2" v-if="selectedPlan">

                    <!-- The Selected Plan -->
                    <div class="row" v-if="plans.length > 1">
                        @include('auth.registration.subscription.plans.selected')
                    </div>

                    <!-- Plan Features if Selected Plan is Custom -->
                    <div class="row" v-if="selectedPlan.is_custom">
                        @include('auth.registration.subscription.plans.features')
                    </div>

                    <!-- Basic Information -->
                    <div class="row">
                        @include('auth.registration.subscription.basics')
                    </div>

                    <!-- Interested in PI Insurance -->
                    <div class="row">
                        @include('auth.registration.subscription.pi_cover')
                    </div>

                    <!-- Billing Options -->
                    <div class="row" v-if=" ! freePlanIsSelected">
                        @include('auth.registration.subscription.billing_options')
                    </div>

                    <!-- Billing Information -->
                    <div class="row" v-if=" ! freePlanIsSelected">
                        @include('auth.registration.subscription.billing')
                    </div>

                </div>
            </div>
        </app-subscription-register-screen>
    </section>
@endsection