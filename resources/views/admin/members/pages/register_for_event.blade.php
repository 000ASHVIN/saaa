<?php
    $events = App\AppEvents\Event::with(['venues', 'venues.dates', 'extras'])->get();
?>

@extends('admin.layouts.master')
@section('title', $member->first_name . ' ' . $member->last_name)
@section('description', 'User Profile')

@section('css')
    <link href="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .daterangepicker{
            z-index:99999!important;
        }
        .event_search {
            width: 100%;
            position: absolute;
        }
        .event_search {
            width: 100%;
            position: absolute;
        }
        .event-box {
            padding: 20px;
            border: 1px solid #e6e8e8;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .add-event {
            text-align: right;
            margin-bottom: 5px;
        }
    </style>
@stop

@section('content')
    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            @include('admin.members.includes.nav')
            <div class="col-md-9 col-sm-9 nopadding">
                {{-- {!! Former::open()->route('register_for_event', ['memberId' => $member->id])->method('POST') !!} --}}
                @if(\Session::has('error'))
                    <p class="alert alert-warning">{!! Session::get('error') !!}</p>
                @endif
                @if(\Session::has('success'))
                    <p class="alert alert-success">{!! Session::get('success') !!}</p>
                @endif
                {!! Form::open(['onsubmit' => 'return false']) !!}
                <import-members-register-for-event :events="{{ $events->toJson() }}" :user_id="{{ $member->id }}" inline-template>
                    <div class="add-event">
                        <span v-on:click="addEvent" class="btn btn-info add_event_btn">Add Event</span>
                    </div>
                    <div class="event-box" v-for="registerEvent in registerEvents">
                    <fieldset>
                        <legend>
                            Select Event
                        </legend>
                        <label for="event_id" style="width: 100%;position: relative;" class="event_label">
                            <select v-on:change="changeEventSelection(registerEvent.id)" v-model="registerEvent.selectedEventId" name="" class="old_event" style="width: 100%; display: none">
                                <option v-for="event in events" value="@{{ event.id }}">@{{ event.name }}</option>
                            </select>
                            <select name="event_id" class="event_id" style="width: 100%">
                                <option value="0">Select ...</option>
                                <option v-for="event in events" value="@{{ event.id }}">@{{ event.name }}</option>
                            </select>
                        </label>
                    </fieldset>

                    <fieldset>
                        <legend>Select Venue</legend>
                        <label for="venue_id" style="width: 100%!important;">
                            <select v-on:change="changeVenueSelection(registerEvent.id)" v-model="registerEvent.selectedVenueId" name="venue_id"
                                    id="venue_id" style="width: 100%!important;">
                                    <option v-for="venue in registerEvent.venues"
                                        value="@{{ venue.id }}">@{{ venue.name }}</option>
                            </select>
                        </label>
                    </fieldset>

                    <fieldset>
                        <legend>Select Pricing</legend>
                        <label for="pricing_id" style="width: 100%!important;">
                            <select v-model="registerEvent.selectedPricingId" name="pricing_id" id="pricing_id" style="width: 100%!important;">
                                <option v-for="price in registerEvent.pricings" value="@{{ price.id }}">@{{ price.name }} - @{{ price.price | currency 'R' }} -  with Subscriber Discount Price @{{ price.price- price.subscription_discount | currency 'R' }}</option>
                            </select>
                        </label>
                    </fieldset>

                    <fieldset>
                        <legend>Venue Date</legend>
                        <label for="date_id" style="width: 100%!important;">
                            <select v-model="registerEvent.selectedDateId" name="date_id" id="date_id" style="width: 100%!important;">
                                <option v-for="date in registerEvent.dates"
                                        value="@{{ date.id }}">@{{ date.date }}</option>
                            </select>
                        </label>
                    </fieldset>

                    <fieldset v-if="registerEvent.venueType != 'online' && registerEvent.venueType != ''">
                        <legend>Additional Extras</legend>
                        <div class="checkbox clip-check check-primary" v-for="extra in registerEvent.extras">
                            <input type="checkbox" id="@{{ extra.id }}" value="@{{ extra.id }}" name="extras" v-model="registerEvent.selectedExtraId[extra.id]">
                            <label for="@{{ extra.id }}">
                                <strong>@{{ extra.name }}</strong> <span>@{{ extra.name }} - @{{ extra.price | currency 'R' }}</span>
                            </label>
                        </div>
                    </fieldset>

                    <fieldset v-if="registerEvent.venueType != 'online' && registerEvent.venueType != ''">
                        <legend>Dietary Requirements</legend>
                        <select name="dietary" id="dietary" class="form-control" v-model="registerEvent.dietary">
                            @foreach($dietaries as $dietary)
                                <option value="{{ $dietary->id }}">{{ $dietary->name }}</option>
                            @endforeach
                        </select>
                    </fieldset>
                    </div>

                    <fieldset>
                        <legend>Generate Invoice</legend>
                        <p>Kindly note that if the below option has been selected this will generate an invoice for this user and event.</p>
                        @if(auth()->user()->hasRole('super'))
                            <div class="checkbox clip-check check-primary">
                                <input type="checkbox" id="checkbox1" value="1" name="generate_invoice" v-model="generate_invoice">
                                <label for="checkbox1">
                                    Generate Invoice for this ticket.
                                </label>
                            </div>
                        @else
                            {!! Form::hidden('generate_invoice', true, ["v-model" => "generate_invoice"]) !!}
                        @endif
                    </fieldset>
                    <button class="btn btn-primary" v-on:click="registerData" v-bind:disabled="registerEvents[0].selectedDateId == ''"><i class="fa fa-spinner fa-spin" style="margin-right: 3px" v-if="busy"></i>Register</button>
                </import-members-register-for-event>
                {{-- {!! Former::submit('Register')->addClass('btn-primary') !!} --}}
                {!! Former::close() !!}
            </div>
        </div>
    </div>
@stop

@section('scripts')
    {{-- <script src="/js/app.js"></script> --}}
    <script src="/assets/themes/saaa/js/app.js"></script>
    <script src="/assets/admin/assets/js/profile.js"></script>
    <script src="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.js"></script>
    <script src="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            Profile.init();
            $('.event_id').select2();
            $('.event_id').change(function(){
                var optionVal = $(this).val();
                $(this).prev().val(optionVal)[0].dispatchEvent(new Event('change'));
            })
        });

        $('.add_event_btn').click(function() {
            $('.event_id').select2();
            $('.event_id').change(function(){
                var optionVal = $(this).val();
                $(this).prev().val(optionVal)[0].dispatchEvent(new Event('change'));
            })
        });
        $('html,body').animate({scrollTop:0},800);
    </script>
    <script src="/assets/admin/assets/js/bootstrap-confirm-delete.js"></script>
@stop