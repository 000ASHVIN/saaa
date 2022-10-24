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

                        <course-order-template :courses="{{ $course->toJson() }}"  inline-template>
                             {!! Form::open(['method' => 'post', 'route' => ['order_course_for_user', $member->id]]) !!}
                            <fieldset>
                                <legend>
                                    Select Course
                                </legend>
                                <label for="course_id" style="width: 100%">
                                    <select v-on:change="changeCourseselection" v-model="selectedCourseId" name="course_id"
                                            id="course_id" style="width: 100%">
                                            @foreach($course as $c)	
										
                                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                                            @endforeach
                                    </select>
                                </label>
                            </fieldset>


                            <fieldset>
                                <legend>Select Pricing</legend>
                                <label for="plan_type" style="width: 100%!important;">
                                    <select v-model="selectedPricingId" name="plan_type" id="plan_type" style="width: 100%!important;">
                                        <option value="monthly" >Monthly Price</option>
                                        <option value="yearly" >Yearly Price</option>
                                    </select>
                                </label>
                            </fieldset>

                            <fieldset>
                                <legend>
                                    Payment Method
                                </legend>
                                <label for="plan_id" style="width: 100%">
                                    <select class="form-control" name="payment_method" v-model="payment_method">
                                        <option selected value="">Please Select...</option>
                                        <option value="debit_order">Debit Order</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="eft">EFT</option>
                                    </select>
                                </label>
                            </fieldset>

                            <fieldset v-if="selectedPricingId == 'yearly' && selectedEvent.type_of_course=='semester' && selectedEvent.no_of_semesters > 0">
                                <legend>
                                    Payment Full
                                </legend>
                                <label for="plan_id" style="width: 100%">
                                    <select class="form-control" name="course_type" v-model="course_type">
                                        <option selected value="">Please Select...</option>
                                        <option value="partially">1st Semester</option>
                                        <option value="full">Full Semester</option>
                                    </select>
                                </label>
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
                                        <input name="coupon_code" type="hidden" value="@{{{ Couponcode }}}">
                                            <h5 v-if="couponApplied" class="no-margin-bottom">Your discount has been applied</h5>
                                            <div v-else>
                                            <h5>Claim your discount now!</h5>
                                            <input class="form-control" id="code" type="text" name="code" v-model="Couponcode">
                                            <input name="type" type="hidden" value="course">
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

                            <div >{!! Form::submit('Generate Order', ['class' => 'btn btn-primary']) !!}</div>
                            {!! Form::close() !!}

                            <div class="col-md-12">

                                <div class="row">
                                    <hr>
                                    <div v-if="selectedOption == 'event'">

                                    </div>
                                </div>
                            </div>
                        </course-order-template>
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