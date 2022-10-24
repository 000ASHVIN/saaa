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
                <fieldset>
                    <legend>Create subscription Invoice</legend>
                    <form action="/admin/members/invoices/create" method="POST">
                        {!! csrf_field() !!}
                        <input type="hidden" name="user_id" value="{{ $member->id }}"></input>
                        <div class="form-group">
                            <label>Subscription Plan</label>
                            <select name="plan_id" style="width: 100%!important;" class="form-control">
                                <option selected="selected" value="">Please Select...</option>
                                @foreach($subscription_plans as $plan)
                                    <option value="{{ $plan->id }}">{{ $plan->name }} "R{{$plan->price}} / {{ $plan->interval }}"</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Invoice Date</label>
                            <input type="text" class="form-control datepicker" name="date"></input>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Create subscription Invoice" class="btn btn-success btn-block"></input>
                        </div>
                    </form>
                </fieldset>

                <hr>

                <fieldset>
                    <legend>CREATE COURSE INVOICE</legend>
                    <form action="/admin/members/courses/invoices/create" method="POST">
                        {!! csrf_field() !!}
                        <input type="hidden" name="user_id" value="{{ $member->id }}"></input>
                        <div class="form-group{{ $errors->has('course') ? ' has-error' : '' }}">
                            <label>Courses</label>
                            <select name="course" style="width: 100%!important;" class="form-control course-invoice">
                                <option selected="selected" value="">Please Select...</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" data-monthly="{{ $course->monthly_enrollment_fee }}" data-yearly="{{ $course->yearly_enrollment_fee }}" data-vat="{{ $course->exclude_vat }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                            <small class="text-danger">{{ $errors->first('course') }}</small>
                        </div>
                        <div class="form-group{{ $errors->has('enrollment_option') ? ' has-error' : '' }}">
                            {!! Form::label('enrollment_option', 'Enrollment Option') !!}
                            {!! Form::select('enrollment_option', ['monthly' => 'Monthly', 'yearly' =>'Yearly'], null, ['id' => 'enrollment_option', 'class' => 'form-control course-enrollment-option', 'placeholder' => 'Please Select....']) !!}
                            <small class="text-danger">{{ $errors->first('enrollment_option') }}</small>
                        </div>
                        <div class="form-group">
                            <label>Price:</label>
                            <span class="course-price" style="font-weight: bold"></span>
                        </div>
                        <div class="form-group">
                            <label>Charge VAT:</label>
                            <span class="course-vat">
                                <span class="label label-success yes" style="display: none">Yes</span>
                                <span class="label label-info no" style="display: none">No</span>
                            </span>
                        </div>
                        <div class="form-group{{ $errors->has('course') ? ' has-error' : '' }}">
                            <label>Invoice Date</label>
                            <input type="text" class="form-control datepicker" name="date"></input>
                            <small class="text-danger">{{ $errors->first('course') }}</small>
                        </div>
                        <div class="form-group">
                            {!! Form::label('description', 'Invoice Description') !!}
                            {!! Form::input('text', 'description', null, ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Create Course Invoice" class="btn btn-success btn-block"></input>
                        </div>
                    </form>
                </fieldset>

                <hr>

                <fieldset>
                    <legend>Create custom Invoice</legend>
                    <form action="/admin/members/invoices/CombinedInvoice" method="POST">
                        {!! csrf_field() !!}
                        <input type="hidden" name="user_id" value="{{ $member->id }}"></input>

                        <div class="form-group">
                            <label>Please select Item</label>
                            <select class="form-control" name="option_id" style="padding: 0px">
                                <option selected="selected" value="">Please Select...</option>
                                @if(count($items))
                                    @foreach($items as $item)
                                        <option value="{{ $item->id }}" style="text-transform: capitalize">{{ $item->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            {!! Form::label('description', 'Invoice Description') !!}
                            {!! Form::input('text', 'description', null, ['class' => 'form-control', 'placeholder' => 'CPD Subscription Jan - Feb 2016', 'required' => 'required']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('price', 'Price') !!}
                            {!! Form::input('text', 'price', null, ['class' => 'form-control', 'placeholder' => 'Enter Price "1600"']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('type', 'Type') !!}
                            {!! Form::select('type', [
                                null => 'Please Select',
                                'subscription' => 'CPD subscription package',
                                'store' => 'Store',
                                'event' => 'Event',
                                'course' => 'Course'
                            ], null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('include_vat', 'Should this invoice charge VAT?') !!}
                            {!! Form::select('include_vat', [
                                null => 'Please Select',
                                0 => 'No',
                                1 => 'Yes'
                            ], null, ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            <input type="submit" value="Create custom Invoice" class="btn btn-warning pull-info btn-block"></input>
                        </div>
                    </form>
                </fieldset>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script src="/assets/admin/assets/js/profile.js"></script>
    <script src="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.js"></script>
    <script src="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            Profile.init();
        });

        $(document).ready(function() {
            $('.course-invoice').change(function() {
                
                var vat = $(this).find(':selected').data('vat')

                if(vat == 0) {
                    $('.course-vat .yes').show()
                    $('.course-vat .no').hide()
                } else {
                    $('.course-vat .yes').hide()
                    $('.course-vat .no').show()
                }
                $('.course-price').html(0)
                $('.course-enrollment-option').val('').change()
            });
            $('.course-enrollment-option').change(function() {
                var price = 0;
                if($(this).val() == 'yearly') {
                    price = $('.course-invoice').find(':selected').data('yearly');
                    price = 'R'+price+' / Year';
                } else if($(this).val() == 'monthly') {
                    price = $('.course-invoice').find(':selected').data('monthly');
                    price = 'R'+price+' / Month';
                }
                
                $('.course-price').html(price)
            });
        });
    </script>
    <script src="/assets/admin/assets/js/bootstrap-confirm-delete.js"></script>
    @include('admin.members.includes.spin')
@stop