@extends('app')

@section('title', 'Signup for CPD Subscription')

@section('content')

<section id="slider" class="hidden-sm hidden-xs">
    <center  style="    ">
        <div  data-target="#need_help_subscription" data-toggle="modal"  style="background-image: url({{ url('assets/themes/taxfaculty/img/web_banner1.jpg') }});background-position: center;
        background-repeat: no-repeat;
        background-size: cover; height: 280px; cursor: pointer; position:relative;">
            

        </div>
    </center>
</section>

    <section style="background:url('/assets/frontend/images/demo/wall2.jpg')">
        <div class="container">
            <div class="col-md-12">
                <div class="heading-title heading-dotted text-center">
                    <h3 style="background-color: #173175; color: white">Technical Resource Centre & <span style="color: white">CPD Subscription</span></h3>
                </div>
            </div>
        </div>

        <app-subscriptions-screen  :subscriptions="{{(auth()->user()?auth()->user()->subscription('cpd')->plan:auth()->user())}}"  :plans="{{ $plans }}" :user="{{ (auth()->user() ? auth()->user()->load(['cards', 'subscriptions']) : auth()->user()) }}" inline-template>
            <div id="app-register-screen" class="container app-screen">
                @if (Route::getFacadeRoot()->current()->uri() == 'subscriptions/2020/BlackFriday' || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2020/one-day-only' ||Route::getFacadeRoot()->current()->uri() == 'subscriptions/2019/One-Day-Only'  || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2020/One-Day-Only'|| Route::getFacadeRoot()->current()->uri() == 'BlackFriday')
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
                    @include('subscriptions.partials.one_day_billing_options')

                    {{-- Billing Options Details --}}
                    @include('subscriptions.partials.billing_information')

       
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