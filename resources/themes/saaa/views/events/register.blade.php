@extends('app')

@section('title', $event->name)
@section('intro', 'Event Registration')
@section('styles')
    <style type="text/css">
        .no-margin-bottom {
            margin-bottom: 0;
        }
    </style>
@endsection

@section('content')
    <section style="background-image: url('/assets/frontend/images/demo/wall2.jpg'); padding-top: 40px;">

        <app-simple-event-registration-screen :event="{{ $event }}"
                                              :dietary="{{ $dietaryRequirements }}"
                                              :promoCodes="{{ $promoCodes->toJson() }}"
                                              :user="{{ auth()->user()->load('cards') }}"
                                              :donations="{{ env('DONATIONS_AMOUNT') > 0 ? env('DONATIONS_AMOUNT') : 0 }}"
                                              inline-template>
            <div class="row">
                <div id="app-register-screen" class="container app-screen">

                    <div class="row">
                        <div class="col-md-12" v-if="!forms.eventSignup.venue">
                            @include('events.single.venues.selector')
                        </div>

                        @if(count($promoCodes) > 0)
                            <div class="col-md-7 col-md-offset-1" v-if="forms.eventSignup.venue">
                                @else
                                    <div class="col-md-8 col-md-offset-1" v-if="forms.eventSignup.venue">
                                        @endif

                                        {{-- The Selected Venue --}}
                                        <div class="row">
                                            @include('events.single.venues.selected')
                                        </div>


                                        {{-- Pricing Options --}}
                                        <div class="row">
                                            @include('events.single.pricings.options')
                                        </div>

                                        {{-- Donations --}}
                                        <div class="row">
                                            @include('includes.donation', ['vif' => "donations && forms.eventSignup.pricingOption", 'vmodel' => "forms.eventSignup.donations"])
                                        </div>

                                        {{-- Dates --}}
                                        <div class="row" v-if="forms.eventSignup.pricingOption">
                                            @include('events.single.pricings.dates')
                                        </div>

                                        {{-- Extras --}}
                                        <div class="row" v-if="extras.length > 0">
                                            @include('events.extras')
                                        </div>

                                        {{-- Dietary Requirements --}}
                                        <div class="row" v-if="forms.eventSignup.venue.city">
                                            @include('events.dietary-requirements')
                                        </div>

                                        {{-- Billing Options --}}
                                        <div class="row"
                                             v-if="forms.eventSignup.pricingOption && forms.eventSignup.dates.length > 0 && total > 0">
                                            @include('events.billing.billing_options')
                                        </div>

                                        {{-- Billing Information --}}
                                        <div class="row"
                                             v-if="forms.eventSignup.pricingOption && forms.eventSignup.dates.length > 0 && total > 0">
                                            @include('events.billing.billing')
                                        </div>

                                        {{-- Free Registrations --}}
                                        <div class="row"
                                             v-if="forms.eventSignup.pricingOption && forms.eventSignup.dates.length > 0 && total == 0">
                                            @include('events.billing.simple')
                                        </div>

                                    </div>

                                    {{--Promo Codes--}}

                                    <div v-if="forms.eventSignup.venue">
                                        @if(count($promoCodes) > 0)
                                            <div class="col-md-3">

                                                @foreach($promoCodes->take(1) as $promoCode)
                                                    <div class="panel panel-default">

                                                        <div class="panel-heading">
                                                            Claim Your Discount
                                                        </div>

                                                        <div class="panel-body">
                                                            @if(array_flatten(App\AppEvents\PromoCode::sessionCodes(), $promoCode->code ))
                                                                <h5 class="no-margin-bottom">Your discount has been applied</h5>
                                                            @else
                                                                <h5>Claim your discount now!</h5>
                                                                {!! Form::open(['method' => 'post', 'route' => ['check_coupon', $event->id]]) !!}
                                                                {!! Former::text('code','Your Unique Discount Code') !!}
                                                                <input name="type" type="hidden" value="@{{ forms.eventSignup.venue.type }}">
                                                                <input name="event_name" type="hidden" value="@{{ event.slug }}">
                                                                {!! Former::submit('Submit') !!}
                                                                {!! Form::close() !!}
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if($event->registration_instructions && $event->registration_instructions != "")
                                           <div class="col-md-3">
                                               <hr>
                                               <div class="panel panel-warning">

                                                   <div class="panel-heading">
                                                       <i class="fa fa-bullhorn fa-x2" style="font-size: 20px"></i>  <strong>Registration Instructions</strong>
                                                   </div>

                                                   <div class="panel-body">
                                                       {!! $event->registration_instructions !!}
                                                   </div>
                                               </div>
                                           </div>
                                        @endif

                                        @if(auth()->user()->hasCompany() && auth()->user()->hasCompany()->company->admin()->status != 'active')
                                            <div class="col-md-3">
                                                <div class="panel panel-danger" v-if="forms.eventSignup.venue.type == 'online'">

                                                    <div class="panel-heading">
                                                        <i class="fa fa-close fa-x2" style="font-size: 20px"></i>  <strong>Company Account Suspended!</strong>
                                                    </div>

                                                    <div class="panel-body">
                                                        <p>
                                                            Your company account has been suspended due to non payment,
                                                            Please contact {{ ucfirst(auth()->user()->hasCompany()->company->admin()->first_name).' '.ucfirst(auth()->user()->hasCompany()->company->admin()->last_name) }}
                                                            {{ auth()->user()->hasCompany()->company->admin()->cell }} |
                                                            <a href="mailto: {{ auth()->user()->hasCompany()->company->admin()->email }}">{{ auth()->user()->hasCompany()->company->admin()->email }}</a>
                                                        </p>
                                                        <hr>
                                                        <p>
                                                            Should you proceed with this registration you will be charged for this event and no refunds will be processed.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                            </div>
                    </div>

                </div>
            </div>
        </app-simple-event-registration-screen>
    </section>
@stop

@section('scripts')
    <script src="/assets/frontend/plugins/form.masked/jquery.maskedinput.js"></script>
@stop