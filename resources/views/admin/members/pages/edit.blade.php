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
                {!! Form::model($member, ['Method' => 'Post', 'route' => ['members_update_profile', $member->id]]) !!}

                <fieldset>
                    <legend>Personal Information</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('first_name', 'First Name') !!}
                                {!! Form::input('text', 'first_name', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('last_name', 'Last Name') !!}
                                {!! Form::input('text', 'last_name', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('gender', 'Gender') !!}
                                {!! Form::select('gender', [
                                    'm' => 'Male',
                                    'f' => 'Female'
                                ], $member->profile->gender, ['class' => 'form-control', 'style' => 'width:100%']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('id_number', 'ID Number') !!}
                                {!! Form::input('text', 'id_number', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Additional Billing Information</legend>
                    <div class="form-group @if ($errors->has('billing_email_address')) has-error @endif">
                        <div class="pull-left"> {!! Form::label('billing_email_address', 'Billing Email Address') !!}</div>
                        <div class="pull-right"><small><strong><i class="fa fa-info-circle"></i> <i>This email address will be cc'd when sending account invoices.</i></strong></small></div>
                        {!! Form::input('text', 'billing_email_address', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('billing_email_address')) <p class="help-block">{{ $errors->first('billing_email_address') }}</p> @endif
                    </div>

                    <div class="form-group">
                        {!! Form::label('payment_arrangement', 'Payment Arrangement') !!}
                        {!! Form::select('payment_arrangement', [
                            '' => 'Please Select',
                            true => 'Yes',
                            false => 'No',
                        ],null, ['class' => 'form-control']) !!}
                    </div>

                </fieldset>

                <fieldset>
                    <legend>Contact Information</legend>
                    <div class="row">
                        <div class="col-md-6">
                            @if(auth()->user()->hasRole('super'))
                                <div class="form-group">
                                    {!! Form::label('email', 'Email') !!}
                                    <span class="pull-right"><small><i><strong>Change this by sending an email to IT</strong></i></small></span>
                                    {!! Form::input('text', 'email', null, ['class' => 'form-control']) !!}
                                </div>
                            @else
                                <div class="form-group">
                                    {!! Form::label('email', 'Email') !!}
                                    <span class="pull-right"><small><i><strong>Change this by sending an email to IT</strong></i></small></span>
                                    {!! Form::input('text', 'email', null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                </div>
                            @endif

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('cell', 'Cellphone') !!}
                                {!! Form::input('text', 'cell', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Additional Information</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('company', 'Company Name') !!}
                                {!! Form::input('text', 'company', $member->profile->company, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('website', 'Website') !!}
                                {!! Form::input('text', 'website', $member->profile->website, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('position', 'Current Position') !!}
                                {!! Form::input('text', 'position', $member->profile->position, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('tax', 'Tax number') !!}
                                {!! Form::input('text', 'tax', $member->profile->tax, ['class' => 'form-control']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('body', 'Professional Body') !!}
                                {!! Form::select('body', [
                                null => 'No Professional Body',
                                'SAIBA' => 'The Southern African Institute for Business Accountants',
                                'SAIT' => 'The South African Institute of Tax Practitioners',
                                ] ,$member->profile->body, ['class' => 'form-control', 'style' => 'width:100%']) !!}
                            </div>
                        </div>
                        @role('super')
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('roles', 'Member role') !!}
                                {!! Form::select('roles[]', $roles, $member->roles()->lists('id')->toArray(), ['class' => 'custom-select form-control', 'style' => 'width:100%', 'multiple' => 'multiple']) !!}
                            </div>
                        </div>
                        @endrole
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('cpd_with_sait', 'Subscription with Sait') !!}
                                {!! Form::select('cpd_with_sait', [
                                null => 'Please Select',
                                '1' => 'Active CPD Subscription with SAIT',
                                '0' => 'Not Active CPD Subscription with SAIT',
                                ] ,$member->cpd_with_sait, ['class' => 'form-control', 'style' => 'width:100%']) !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('debt_arrangement', 'Account debt arrangement') !!}
                                {!! Form::select('debt_arrangement', [
                                null => 'Please Select',
                                '1' => 'Yes',
                                '0' => 'No',
                                ] ,null, ['class' => 'form-control', 'style' => 'width:100%']) !!}
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Professional Body</legend>
                    <div class="row">
                        <div class="col-md-6">
                            @if(auth()->user()->hasRole('super'))

                                <div class="form-group @if ($errors->has('body_id')) has-error @endif">
                                    <strong><small class="pull-right"><i>Change this by sending an email to IT</i></small></strong>
                                    {!! Form::label('body_id', 'Professional Body') !!}
                                    <select name="body_id" id="body_id" class="form-control">
                                        <option value="null">Please Select</option>
                                        @foreach($bodies as $body)
                                            @if($member->body)
                                                <option value="{{ $body->id }}" {{ ($body->id == $member->body->id ? "selected" : "") }}>{{ $body->title }}</option>
                                            @else
                                                <option value="{{ $body->id }}">{{ $body->title }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if ($errors->has('body_id')) <p class="help-block">{{ $errors->first('body_id') }}</p> @endif
                                </div>
                            @else
                                <div class="form-group @if ($errors->has('body_id')) has-error @endif">
                                    <strong><small class="pull-right"><i>Change this by sending an email to IT</i></small></strong>
                                    {!! Form::label('body_id', 'Professional Body') !!}
                                    <select name="body_id" id="body_id" class="form-control" disabled="true">
                                        <option value="null">Please Select</option>
                                        @foreach($bodies as $body)
                                            @if($member->body)
                                                <option value="{{ $body->id }}" {{ ($body->id == $member->body->id ? "selected" : "") }}>{{ $body->title }}</option>
                                            @else
                                                <option value="{{ $body->id }}">{{ $body->title }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if ($errors->has('body_id')) <p class="help-block">{{ $errors->first('body_id') }}</p> @endif
                                </div>

                            @endif
                        </div>

                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('membership_verified')) has-error @endif">
                                <strong><a href="{{ route('resend_membership_confirm', $member->id) }}"><small class="pull-right"><i class="fa fa-envelope"></i> <i>Resend verification email</i></small></a></strong>
                                {!! Form::label('membership_verified', 'Membership Status') !!}
                                {!! Form::select('membership_verified', [
                                    true => 'Membership Verified',
                                    false => 'Membership not Verified',
                                ],null, ['class' => 'form-control disabled', 'disabled' => true]) !!}
                                @if ($errors->has('membership_verified'))
                                    <p class="help-block">{{ $errors->first('membership_verified') }}</p> @endif
                            </div>
                        </div>

                    </div>
                </fieldset>

                {{-- <fieldset>
                    <legend>Demographics</legend>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('industry')) has-error @endif"> 
                                {!! Form::label('industry', 'Please indicate the Industry / Sector that you work in:') !!}
                                {!! Form::select('industry', $industry,  null, ['class' => 'form-control', 'placeholder' => 'Select your industry/sector']) !!}
                                {!! Form::input('text', 'other_industry', null, ['class' => 'form-control other_industry', 'placeholder' => 'Other', 'style' => "display: none;"]) !!}
                                @if ($errors->has('industry')) <p class="help-block">{{ $errors->first('industry') }}</p> @endif
                            </div>
                        </div>

                    </div>
                </fieldset> --}}

                @include('admin.members.includes.settings')

                <div class="form-group">
                    <button class="btn btn-primary" type="submit">
                        Update Profile
                        <i class="fa fa-arrow-circle-right"></i>
                    </button>
                    @role('super')
                    <a href="{{ route('remove_roles_from_user', $member->id) }}" class="btn btn-danger pull-right"><i class="fa fa-ban"></i> Remove all roles from user</a>
                    @endrole
                </div>


                {!! Form::close() !!}
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
    </script>
    <script src="/assets/admin/assets/js/bootstrap-confirm-delete.js"></script>

    <script type="text/javascript">
        $('.custom-select').select2();
    </script>

    <script>
        // $(document).ready(function() {
        //     // $('#employment').select2();
        //     if($('#industry').val() == 'Other') {
        //         $('.other_industry').show();
        //     } else {
        //         $('.other_industry').hide();
        //     }
        //     $('#industry').change(function() {
        //         if($(this).val() == 'Other') {
        //             $('.other_industry').show();
        //         } else {
        //             $('.other_industry').hide();
        //         }
        //     })
        // });
    </script>
    @include('admin.members.includes.spin')
@stop