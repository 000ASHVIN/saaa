@extends('admin.layouts.master')
@section('title', $member->first_name . ' ' . $member->last_name)
@section('description', 'User Profile')

@section('css')
    <link href="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <style>
        .daterangepicker{
            z-index:99999!important;
        }
    </style>
@stop

@section('content')

    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            @include('admin.members.includes.nav')
            <div class="col-md-9 col-sm-9 nopadding">
                <div class="row">
                    <div class="col-md-12">

                        <order-template :events="{{ $events->toJson() }}" :products="{{ $products->toJson() }}" inline-template>
                            {!! Form::open(['method' => 'post', 'route' => ['order_event_for_user', $member->id]]) !!}
                            <fieldset>
                                <legend>
                                    Select Event
                                </legend>
                                <label for="event_id" style="width: 100%">
                                    <select v-on:change="changeEventSelection" v-model="selectedEventId" name="event_id"
                                            id="event_id" style="width: 100%">
                                        <option v-for="event in events" value="@{{ event.id }}">@{{ event.name }}</option>
                                    </select>
                                </label>
                            </fieldset>

                            <fieldset>
                                <legend>Select Venue</legend>
                                <label for="venue_id" style="width: 100%!important;">
                                    <select v-on:change="changeVenueSelection" v-model="selectedVenueId" name="venue_id"
                                            id="venue_id" style="width: 100%!important;">
                                        <option v-for="venue in venues"
                                                value="@{{ venue.id }}">@{{ venue.name }}</option>
                                    </select>
                                </label>
                            </fieldset>

                            <fieldset>
                                <legend>Select Pricing</legend>
                                <label for="pricing_id" style="width: 100%!important;">
                                    <select v-model="selectedPricingId" name="pricing_id" id="pricing_id" style="width: 100%!important;">
                                        <option v-for="price in pricings" value="@{{ price.id }}">@{{ price.name }} - @{{ price.price | currency 'R' }} - with Subscriber Discount Price @{{ price.price- price.subscription_discount | currency 'R' }}</option>
                                    </select>
                                </label>
                            </fieldset>

                            <fieldset>
                                <legend>Venue Date</legend>
                                <label for="date_id" style="width: 100%!important;">
                                    <select v-model="selectedDateId" name="date_id" id="date_id" style="width: 100%!important;">
                                        <option v-for="date in dates" value="@{{ date.id }}">@{{ date.date }}</option>
                                    </select>
                                </label>
                            </fieldset>

                            <fieldset v-if="selectedVenue.type != 'online'">
                                <legend>Additional Extras</legend>
                                <div class="checkbox clip-check check-primary" v-for="extra in selectedEvent.extras">
                                    <input type="checkbox" id="@{{ extra.id }}" value="@{{ extra.id }}" name="extras[]" v-model="selectedExtraId">
                                    <label for="@{{ extra.id }}">
                                        <strong>@{{ extra.name }}</strong> <span>@{{ extra.name }} - @{{ extra.price | currency 'R' }}</span>
                                    </label>
                                </div>
                            </fieldset>

                            <fieldset v-if="selectedVenue.type != 'online'">
                                <legend>Dietary Requirements</legend>
                                <select name="dietary" id="dietary" class="form-control">
                                    @foreach($dietaries as $dietary)
                                        <option value="{{ $dietary->id }}">{{ $dietary->name }} - R{{ number_format($dietary->price, 2) }}</option>
                                    @endforeach
                                </select>
                            </fieldset>

                            <div v-if="selectedEvent.promo_codes.length > 0" >
                               

                                <div class="panel panel-default" >

                                    <div class="panel-heading">
                                        Claim Your Discount
                                    </div>
                        
                                    <div class="panel-body">
                                        @if(array_flatten(App\AppEvents\PromoCode::sessionCodes(), @$promoCode->code ))
                                            <h5 class="no-margin-bottom">Your discount has been applied</h5>
                                        @else
                                            <h5 v-if="couponApplied" class="no-margin-bottom">Your discount has been applied</h5>
                                            <div v-else>
                                            <h5>Claim your discount now!</h5>
                                            <input class="form-control" id="code" type="text" name="code" v-model="Couponcode">
                                            <input name="type" type="hidden" value="event">
                                            <input name="course_id" type="hidden" value="@{{{ selectedEvent.id }}}">
                                            <input name="event_name" type="hidden" value="@{{{ selectedEvent.name }}}">
                                            <button type="button"  @click.prevent="applyCouponCode"  class="btn btn-primary">
                                                <i class="fa fa-lock"></i> Apply Coupon
                                            </button>
                                            </div>
                                        @endif
                                    </div>
                                
                                
                            </div>
                            </div>
                            <div v-if="selectedEventId">{!! Form::submit('Generate PO', ['class' => 'btn btn-primary']) !!}</div>
                            {!! Form::close() !!}

                            <div class="col-md-12">

                                <div class="row">
                                    <hr>
                                    <div v-if="selectedOption == 'event'">

                                    </div>
                                </div>
                            </div>
                        </order-template>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="/assets/themes/saaa/js/app.js"></script>
    <script src="/assets/admin/assets/js/profile.js"></script>
    <script src="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.js"></script>
    <script src="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            Profile.init();
        });
    </script>
    <script src="/assets/admin/assets/js/bootstrap-confirm-delete.js"></script>
    @include('admin.members.includes.spin')
@stop