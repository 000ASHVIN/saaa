@extends('app')

@section('title', 'Signup for CPD Subscription')

@section('content')
    <section id="slider" class="hidden-sm hidden-xs">
        <center style="background-image: url('https://imageshack.com/a/img923/8140/VeRFYZ.jpg')">
            <div style="background-image: url('https://imageshack.com/a/img923/8140/VeRFYZ.jpg'); height: 420px; background-color: #000000; position:relative; top: 55px;">
                <h4 style="color: red; line-height: 30px; font-size: 30px">Cyber Monday COUNT DOWN</h4>
                <h5 style="color: #ffffff; line-height: 30px;">Find great deals and save up to 50% this Monday 26 November</h5>
                <div class="countdown bordered-squared theme-style" data-from="November 27, 2018 00:00:00"></div>
                <a href="#" data-target="#need_help_subscription" data-toggle="modal" target="_blank" style="margin-bottom: 10px; background-color: red"; class="btn btn-red">Need Help ?</a>
                <p style="font-weight: bold">Limited stock available!</p>

            </div>
        </center>
    </section>

    <section style="background:url('/assets/frontend/images/demo/wall2.jpg')">
        <div class="container">
            <div class="col-md-12">
                <div class="heading-title heading-dotted text-center">
                    <h3 style="background-color: #800000; color: white">Technical Resource Centre & <span style="color: white">CPD Subscription</span></h3>
                </div>
            </div>
        </div>

        <app-subscriptions-screen :plans="{{ $plans }}" :user="{{ (auth()->user() ? auth()->user()->load(['cards', 'subscriptions']) : auth()->user()) }}" inline-template>
            <div id="app-register-screen" class="container app-screen">
                @if (Route::getFacadeRoot()->current()->uri() == 'subscriptions/2017/BlackFriday' || Route::getFacadeRoot()->current()->uri() == 'BlackFriday')
                    <input style="display: none" type="checkbox" checked v-model="forms.subscription.bf" value="bf">
                @endif

                {{-- Subscription Plan Selector --}}
                <div class="col-md-12" v-if="plans.length > 1 && plansAreLoaded && ! forms.subscription.plan">
                    @include('subscriptions.partials.plans.selector')
                </div>

                {{-- Plan is Selected --}}
                <div class="col-md-8 col-md-offset-2" v-if="selectedPlan">

                    {{-- Selected Plan --}}
                    @include('subscriptions.partials.plans.selected')

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
    </Section>



    @include('subscriptions.2017.include.help')
    @include('includes.login')
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