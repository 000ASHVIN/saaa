@extends('app')

@section('title', 'Signup for CPD Subscription')

@section('content')
    <section id="slider" class="hidden-sm hidden-xs">
        <center >
            <div data-target="#need_help_subscription" data-toggle="modal" target="_blank" style="background-image: url('/assets/themes/taxfaculty/img/Black_Friday_Web_Banner_(2021).jpg'); height: 320px; background-color: #000000; position:relative; top: 0px;    background-repeat: no-repeat;background-size: 100% 100%;cursor: pointer;">
                {{--  <h4 style="color: red; line-height: 30px; font-size: 30px">Black Friday 50% discount</h4>
                <h5 style="color: #ffffff; line-height: 30px;">Find great deals and save up to 50% this Friday 29 November.</h5>  --}}
                {{--  <div class="countdown bordered-squared theme-style" data-from="November 1, 2018 00:00:00"></div>  --}}
                {{--  <a href="#" data-target="#need_help_subscription" data-toggle="modal" target="_blank" style="margin-bottom: 10px; background-color: red"; class="btn btn-red">Need Help ?</a>
                <p style="font-weight: bold">Limited stock available!</p>  --}}

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
                @if (Route::getFacadeRoot()->current()->uri() == 'subscriptions/2020/BlackFriday' || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2021/BlackFriday' || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2020/one-day-only' || Route::getFacadeRoot()->current()->uri() == 'BlackFriday')
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
    <script>
        $(document).ready(function(){
            var max=50;
            $('.price-clean p').each(function(){
                if($(this).height()>max){
                    max=$(this).height();
                }
            })
    
            $('.price-clean p').each(function(){
                $(this).css('min-height',max+'px'); 
            })
            var maxs=50;
             $('.price-clean h5').each(function(){
                if($(this).height()>maxs){
                    maxs=$(this).height();
                }
            })
    
            $('.price-clean h5').each(function(){
                $(this).css('min-height',maxs+'px'); 
            })
        })
    </script>
@endsection