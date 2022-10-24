@extends('app')

@section('title', 'Signup for CPD Subscription')

@section('content')
    <section style="background-image: url('/assets/frontend/images/demo/wall2.jpg'); padding-bottom: 10px">
        <div class="container">

            <div class="col-md-8 col-md-offset-2">
                <div class="heading-title heading-dotted text-center">
                    <h2 style="background-color: #173175; color: white">Subscription Packages</h2>
                    <p class="font-lato size-19">Please select your <strong>preferred</strong> profession from the list below</p>
                </div>

                <div class="form-group">
                    <select class="form-control" name="profession" id="profession" v-model="profession" >
                        <option value="" selected>Please select..</option>
                        @foreach($professions as $profession)
                            <option value="{!! $profession->title !!}">{!! $profession->title !!}</option>
                        @endforeach
                        <option value="Tax Practitioner">Other</option>
                    </select>
                    <i class="fancy-arrow" data-target="#profession"></i>
                </div>
            </div>
        </div>

        <div class="container">
            <br>
            @foreach($professions as $profession)
                <div v-if="profession == '{!! $profession->title !!}'">
                    <div class="row">
                        <app-subscriptions-screen :subscriptions="{{(auth()->user()?auth()->user()->subscription('cpd')->plan:auth()->user())}}" :plans="{{ $profession->plans }}" :profession="{{ $profession }}" :user="{{ auth()->user() }}" inline-template>
                            <div id="app-register-screen" class="container app-screen">
                                <div v-if="plans.length > 1 && plansAreLoaded && ! forms.subscription.plan">
                                    @include('subscriptions.partials.plans.selector')
                                </div>

                                <div class="col-md-8 col-md-offset-2" v-if="selectedPlan">
                                    @include('subscriptions.partials.plans.selected')
                                    @include('subscriptions.partials.plans.features')
                                    @include('subscriptions.partials.billing_options')
                                    @include('subscriptions.partials.billing_information')
                                    @include('subscriptions.partials.pi')
                                    @include('subscriptions.partials.terms')
                                </div>
                            </div>
                        </app-subscriptions-screen>
                    </div>
                </div>
            @endforeach
        </div>

        <br>
        <a href="/dashboard" class="btn btn-xlg btn-primary size-10 fullwidth nomargin bopadding noradius">
            <span class="font-lato size-30">Skip this step for now.</span>
            <span class="block font-lato">Remember, you can always sign up for CPD from your dashboard.</span>
        </a>

    </Section>
@endsection