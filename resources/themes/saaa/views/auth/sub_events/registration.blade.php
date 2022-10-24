@extends('app')

@section('content')

@section('styles')
<link href="/assets/frontend/css/intlTelInput.css" rel="stylesheet" type="text/css" />
<link href="/assets/frontend/css/demo.css" rel="stylesheet" type="text/css" />
<style>
    .iti__selected-flag {
        padding: 5px 6px 0 8px;
    }
    .iti__flag-container {
        top: 25px !important;
    }
    .login-form {
        padding-top: 15px;
    }
    @media screen and (min-width: 992px) {
        .section-two {
            padding-top: 46px;
        }
    }
</style>
@stop
 

<?php 
$request = Input::all();
$plan = null;
if(isset($_COOKIE['plan']))
{
    // $plan = \App\Subscriptions\Models\Plan::where('id',$_COOKIE['plan'])->first();
}

$planType = '';
if (request()->has('subscription'))
    $planType = 'subscription';
// elseif(request()->has('profession'))
//     $planType = 'profession'; 

?>
<section class="alternate" style="background-image: url('/assets/frontend/images/demo/wall2.jpg');">
    <div id="registration_page">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="wizard">
                        <div class="wizard-inner">
                            <div class="connecting-line"></div>

                             {{-- <div class="custom-bg margin-bottom-20" id="registration_steps">
                                <ul class="process-steps nav nav-tabs nav-justified" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#step1" data-toggle="tab">1</a>
                                        <h5>Personal Information</h5>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#step2" data-toggle="tab">2</a>
                                        <h5>Address Details</h5>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#step3" data-toggle="tab">3</a>
                                        <h5>Professional Body</h5>
                                    </li>
                                    <li role="presentation" class="disabled">
                                        <a href="#complete" data-toggle="tab">4</a>
                                        <h5>Terms & Conditions</h5>
                                    </li>
                                </ul>
                            </div>  --}}

                            {{-- <div class="panel panel-default" style="margin-bottom: 0px">
                                <div class="panel-body">
                                    <p class="text-center">
                                        Once you have created a user profile you will gain access to our free Monthly
                                        Practice Management Series <br> and be able to book for events and signup for
                                        CDP
                                        Subscription Packages.
                                    </p>
                                </div>
                            </div> --}}
                            <span style="display: flex;">
                                <span style="margin-right: 5px;">Do you Already have a profile?</span>
                                <label class="switch switch switch-round">
                                    <input type="checkbox" name="login-switch" id="switch-to-login" value="no">
                                    <span class="switch-label login-switch-toggle" data-on="YES"
                                        data-off="NO"></span>

                                </label>
                            </span>
                        </div>

                        <div class="login-form" style="display: none">
                            <div class="box-static box-border-top padding-30 margin-bottom-30">
                                <div class="box-title margin-bottom-30">
                                    <!-- <h2 class="size-20">I'm a returning member</h2> -->
                                    <h2 class="size-20">Sign into your SA Accounting Academy Profile here</h2>
                                </div>
                                {!! Form::open(['id'=>'login_form','onSubmit'=>'return false']) !!}
                                    <auth-login inline-template>
                                        {{-- <div  v-if="createNew">
                                            <div class="panel panel-default" style="margin-bottom: 0px">
                                                <div class="panel-body">
                                                    <!-- <p class="text-center">
                                                    We couldn't found any email record. Please create new account  <a href="/auth/register">Create an account </a>
                                                    </p> -->
                                                    <p class="text-center">
                                                    We couldn't find that email address, please create a new profile below<br>
                                                    <a href="/auth/register">Create an account</a>
                                                    </p>
                                                </div>
            
                                            </div>
                                            <br>
            
                                        </div> --}}

                                        <div class="form-group">
                                            {!! Form::text('email', old('email'), ['class' => 'form-control', 'v-model'=>'forms.login.email', 'placeholder' => 'Email Address']) !!}
                                            </div>
                                            <div class="form-group" v-if="isEmailExist">
                                            {!! Form::password('password', ['id' => 'password', 'v-model'=>'forms.login.password', 'class' => 'form-control', 'placeholder' => 'Enter your password']) !!}
                                        </div>
                                        <div class="hidden">
                                            <input 
                                                type="hidden" 
                                                name="{{ $planType ?? '' }}"
                                                value="{{ isset($selectedPlan) ? $selectedPlan->id : ''}}"
                                            >
                                            <input 
                                                type="hidden" 
                                                name="previousUrl"
                                                value="{{ old('previousUrl') ? old('previousUrl') : URL::previous() }}"
                                            >
                                        </div>
                                        <div class="row"  v-if="isEmailExist">
                                            <div class="col-md-12">
                                                <label class="checkbox pt-20">
                                                    <input type="checkbox" name="remember">
                                                    <i></i> Keep me logged in
                                                </label>
                                            </div>
                                        </div>
                                    
                                        <div class="row"> 
                                            <div class="col-md-12">
                                                <!-- <button class="btn btn-primary  ladda-button" type="button" v-on:click="checkemail()" data-style="zoom-in"><span class="ladda-label">Sign in</span></button> -->
                                                <button v-if="!isEmailExist" class="btn btn-primary  ladda-button" v-on:keyup.enter="checkemail()" v-on:click="checkemail()" data-style="zoom-in"><span class="ladda-label">Continue</span></button>
                                                <button v-if="isEmailExist" class="btn btn-primary ladda-button" v-on:keyup.enter="checkemail()" v-on:click="checkemail()" data-style="zoom-in"><span class="ladda-label">Continue</span></button>
            
            
                                                <a href="/password/email" v-if="isEmailExist" class="btn btn-default" >Reset Password</a>
            
                                            </div>
                                            <div class="col-md-12" v-if="!isEmailExist">
                                            <div style="margin-top: 15px;">
                                                <h6>Press continue so we can check if you already have a profile with us</h6>
                                            </div>
                                            </div>
            
                                        </div>
            
                                        <div class="row">
                                            <div class="col-md-12" style="margin-top: -10px;">
                                                <!-- <a href="#" data-target="#need_help_subscription" data-toggle="modal" target="_blank" style="margin-bottom: 10px;" class="btn btn-default ">Need Help ?</a>    -->
                                                <button class="btn btn-lg btn-default need_help_btn" type="button" data-style="zoom-in"><span class="ladda-label">Need Help?</span></button>
            
                                                <!-- <div id="freshwidget-button" data-html2canvas-ignore="true" class="freshwidget-button fd-btn-right" style="display: none; top: 235px;"><a href="javascript:void(0)" class="freshwidget-theme" style="color: white; background-color: rgb(23, 49, 117); border-color: white;">Need Help?</a></div>-->
                                            </div>
                                        </div>
                                    </auth-login>
                                {!! Form::close() !!}
                            </div>
                        </div>

                        <div class="register-form">
                            {!! Form::open() !!}
                                <div class="tab-content">
                                    <div class="tab-pane active" role="tabpanel" id="step1">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h2 class="panel-title">
                                                    Create Account
                                                </h2>
                                            </div>

                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div
                                                            class="form-group @if ($errors->register->has('first_name')) has-error @endif">
                                                            <div class="pull-left">{!! Form::label('first_name', 'First Name')
                                                                !!}</div>
                                                            <small class="pull-right">* Required</small>
                                                            {!! Form::input('text', 'first_name', null, ['class' =>
                                                            'form-control']) !!}
                                                            @if ($errors->register->has('first_name'))
                                                            <p class="help-block">{{ $errors->register->first('first_name') }}</p> @endif
                                                        </div>
                                                        <div
                                                            class="form-group @if ($errors->register->has('last_name')) has-error @endif">
                                                            <div class="pull-left">{!! Form::label('last_name', 'Last Name') !!}
                                                            </div>
                                                            <small class="pull-right">* Required</small>
                                                            {!! Form::input('text', 'last_name', null, ['class' =>
                                                            'form-control']) !!}
                                                            @if ($errors->register->has('last_name'))
                                                            <p class="help-block">{{ $errors->register->first('last_name') }}</p> @endif
                                                        </div>


                                                        <div class="form-group @if ($errors->register->has('email')) has-error @endif">
                                                            <div class="pull-left"> {!! Form::label('email', 'Email Address')
                                                                !!}</div>
                                                            <small class="pull-right">* Required</small>
                                                            {!! Form::input('email', 'email', (isset($_COOKIE['email']))?$_COOKIE['email']:"", ['class' => 'form-control',
                                                            'id' => 'email']) !!}
                                                            @if ($errors->register->has('email'))
                                                            <p class="help-block">{{ $errors->register->first('email') }}</p> @endif
                                                        </div>


                                                        <div class="form-group @if ($errors->register->has('cell')) has-error @endif">
                                                            <div class="pull-left">{!! Form::label('cell', 'Cell') !!}</div>
                                                            <small class="pull-right">* Required</small>
                                                        
                                                            {!! Form::input('tel','cell', null , ['class' => 'form-control','id' => 'cell']) !!}
                                
                                                            <!-- {!! Form::input('text', 'cell', null, ['class' => 'form-control'])
                                                            !!} -->
                                                            @if ($errors->register->has('cell'))
                                                            <p class="help-block">{{ $errors->register->first('cell') }}</p> @endif
                                                        </div>

        




                                                        <div class="form-group @if ($errors->register->has('body')) has-error @endif">


                                                            <span>

                                                                <label class="switch switch switch-round">
                                                                    <span> Do you belong to a professional body?</span>
                                                                    <input type="checkbox" name="prfbdy" id="switcheroo"
                                                                        v-model="prfbdy">
                                                                    <span class="switch-label" data-on="YES"
                                                                        data-off="NO"></span>

                                                                </label>
                                                            </span>
                                                            <div v-if="prfbdy">
                                                                <select name="body" id="body" v-model="bodies"
                                                                    class="form-control">
                                                                    <option value="" @if(old('body')=='' ) selected @endif
                                                                        selected>Please Select
                                                                    </option>
                                                                    @foreach($bodies as $body)
                                                                    <option value="{{ $body->id }}" @if(old('body')==$body->id)
                                                                        selected @endif>{{ ucfirst($body->title) }}</option>
                                                                    @endforeach
                                                                    <option value="other" @if(old('body')=='other' ) selected
                                                                        @endif>Other
                                                                        (Specify)
                                                                    </option>
                                                                </select>

                                                                @if ($errors->register->has('body'))
                                                                <p class="help-block">{{ $errors->register->first('body') }}</p> @endif



                                                                <div v-show="bodies !== '17' && bodies !== ''">
                                                                    <div
                                                                        class="form-group @if ($errors->register->has('membership_number')) has-error @endif">
                                                                        {!! Form::label('membership_number', 'Membership
                                                                        Number') !!}
                                                                        {!! Form::input('text', 'membership_number', null,
                                                                        ['class' => 'form-control', 'id' =>
                                                                        'membership_number']) !!}
                                                                        @if ($errors->register->has('membership_number'))
                                                                        <p class="help-block">
                                                                            {{ $errors->register->first('membership_number') }}</p> @endif
                                                                    </div>
                                                                </div>

                                                                <div v-show="bodies == 'other'">
                                                                    <div
                                                                        class="form-group @if ($errors->register->has('specified_body')) has-error @endif">
                                                                        {!! Form::label('specified_body', 'Please Specify') !!}
                                                                        {!! Form::input('text', 'specified_body', null, ['class'
                                                                        => 'form-control', 'id' => 'specified_body']) !!}
                                                                        @if ($errors->register->has('specified_body'))
                                                                        <p class="help-block">
                                                                            {{ $errors->register->first('specified_body') }}</p> @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group @if ($errors->register->has('password')) has-error @endif">
                                                            <div class="pull-left">{!! Form::label('password', 'Password') !!}
                                                            </div>
                                                            <small class="pull-right">* Required</small>
                                                            {!! Form::input('password', 'password', null, ['class' =>
                                                            'form-control']) !!}
                                                            @if ($errors->register->has('password'))
                                                            <p class="help-block">{{ $errors->register->first('password') }}</p> @endif
                                                        </div>
                                                        <div
                                                            class="form-group @if ($errors->register->has('password_confirmation')) has-error @endif">
                                                            <div class="pull-left">{!! Form::label('password_confirmation',
                                                                'Password Confirmation') !!}</div>
                                                            <small class="pull-right">* Required</small>
                                                            {!! Form::input('password', 'password_confirmation', null, ['class'
                                                            => 'form-control']) !!}
                                                            @if ($errors->register->has('password_confirmation'))
                                                            <p class="help-block">{{ $errors->register->first('password_confirmation') }}
                                                            </p> @endif
                                                        </div>
                                                        <span id="id_results"></span>
                                                        <input type="hidden" name="verified" id="id_number">
                                                        <input type="hidden" id="date">
                                                        <input type="hidden" id="age">
                                                        <input type="hidden" id="gender">
                                                        <input type="hidden" id="citizen">

                                                        <div class="row" >	
                                                            <div class="col-md-12">	
                                                            @if(env('GOOGLE_RECAPTCHA_KEY'))	
                                                                <div class="g-recaptcha"	
                                                                    data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}">	
                                                                </div>	
                                                            @endif	
                                                            </div>	
                                                        </div>


                                                        <div class="hidden">
                                                            <input 
                                                                type="hidden" 
                                                                name="{{ $planType ?? '' }}"
                                                                value="{{ isset($selectedPlan) ? $selectedPlan->id : ''}}"
                                                            >
                                                            <input 
                                                                type="hidden" 
                                                                name="previousUrl"
                                                                value="{{ old('previousUrl') ? old('previousUrl') : URL::previous() }}"
                                                            >
                                                        </div>

                                                    </div>

                                                </div>
                                            </div>
                                            <div class="panel-footer">
                                                <div class="form-group">
                                                    <span class="help-block" style="display: none;">
                                                        <strong></strong>
                                                    </span>
                                                    <label class="checkbox nomargin">
                                                        <input type="checkbox" name="terms" id="terms_and_conditions">
                                                        <i></i>
                                                        I Accept The <a href="/terms_and_conditions" target="_blank">Terms Of
                                                    Service</a> and <a href="/privacy_policy" target="_blank">Privacy Policy</a>
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <!-- <button type="submit" class="btn btn-primary hidden" id="nextstep"> -->
                                                    <button type="submit" class="btn btn-primary disabled" id="nextstep">
                                                        Continue
                                                    </button>

                                                </div>
                                            </div>
                                        </div>





                                    </div>


                                    <div class="clearfix"></div>
                                </div>
                            {!! Form::close() !!}
                        </div>

                        @include('auth.registration.includes.existing_account')
                    </div>
                </div>
                <div class="col-md-5 section-two">
                    @if($plan)
                    <!-- Registration -> Individual Plan Display Block -->
                    <div class="col-sm-12 col-xs-12 app-plan app-plan-{{ $plan->id }}" style="margin-bottom: 20px;">

                        @if (auth()->user() && auth()->user()->subscribed('cpd'))
                        <div class="ribbon" v-if="user.subscriptions[0].plan_id == $plan->id" style="right: 16px;">
                            <div class="ribbon-inner">Current</div>
                        </div>
                        @endif

                        <div class="price-clean"
                            style="background-color: rgba(255, 255, 255, 0.82);padding: 10px 10px; min-height: 330px">
                            <h4 style="font-size: 40px;">
                                <div v-if="forms.subscription.bf">
                                    <sup>R</sup>{{ $plan->bf_price }}<em>/{{ Ucfirst($plan->interval) }}</em>
                                </div>
                                <div v-else>
                                    <sup>R</sup>{{ $plan->price }}<em>/{{ Ucfirst($plan->interval) }}</em>
                                </div>
                            </h4>
                            <div style="min-height: 44px;">
                                <h5 style="color: #547698; font-size: 13px">{{ $plan->name }}</h5>
                            </div>

                            @if (Request::is('subscriptions/2018/saiba_member'))
                            <div v-if="$plan->alt_text">
                                <p><small>{!! $plan->alt_text !!}</small></p>
                            </div>

                            <hr />
                            <p>{!! $plan->description !!}</p>
                            <hr />

                            <div v-if="$plan->small_text">
                                <p><small>{!! $plan->small_text !!}</small></p>
                                <hr>
                            </div>
                            @else
                            <hr />
                            <p>{!! $plan->description !!}</p>
                            <hr />
                            @endif
                            @if (isset($profession))
                            @if(! Request::is('profession/*') && ! Request::is('subscription_plans') && !
                            Request::is('subscriptions/2018/saiba_member') && !
                            Request::is('subscriptions/2018/saiba_member'))
                            <a href="{{ route('profession.show', @$profession->slug) }}"
                                class="btn btn-3d btn-success {{ (auth()->user() ? : "btn-block") }}"
                                style="font-size: 14px">
                                <center><i class="fa fa-book"></i></center>
                            </a>
                            @endif
                            @endif

                        </div>
                    </div>
                    @endif

                    {{-- <div class="class">
                        hello
                    </div> --}}
                    @if(isset($selectedPlan))
                    <div class="panel panel-default">
                        <div class="panel-heading">Your Plan</div>
                        <div class="panel-body">
                    
                            <!-- Current Plan -->
                            <div class="pull-left" style="line-height: 36px;">
                                You have selected the <strong>{{ $selectedPlan->name }}</strong>( R<span> {{ $selectedPlan->price }} /</span> {{ $selectedPlan->interval == 'month' ? 'Month' : 'Year' }}) plan.</div>
                    
                    
                            <!-- Select Another Plan -->
                            <div class="pull-right" style="line-height: 32px;">
                                <a href="{{ old('previousUrl') ? old('previousUrl') : URL::previous() }}" class="btn btn-primary">
                                    <i class="fa fa-btn fa-arrow-left"></i>Change Plan
                                </a>
                            </div>
                    
                            <div class="clearfix"></div>
                        </div>
                    </div>  
                    @endif
                </div>


                <div class="col-md-5 section-three">
                        <div class="box-static box-border-top padding-30">
                            <div class="box-title margin-bottom-30">
                                <h2 class="size-20">New Member ?</h2>
                            </div>
                            <div class="row">
                               <div class="col-md-12">
                                   <!-- <p>If you don't have a login, please register with us to continue.</p> -->
                                   <p>After filling in your information a profile will be created.</p>
                                   <!-- <h2 class="weight-300 letter-spacing-1 size-13" style="margin-bottom: 10px;"><span>Join more than 7,636.00 accountants</span></h2> -->
                                   <h2 class="weight-300 letter-spacing-1 size-13" style="margin-bottom: 10px;"><span>Join more than {{ @$users }} accountants</span></h2> 

                                   <p>As a free member you get access to:</p>
                                    <ul class="list-unstyled list-icons">
                                        <li><i class="fa fa-check text-success"></i> a selection of free upcoming webinars</li>
                                        <li><i class="fa fa-check text-success"></i> a selection of free webinars on demand</li>
                                        <li><i class="fa fa-check text-success"></i> news articles</li>
                                        <li><i class="fa fa-check text-success"></i> rewards from our partners</li>
                                        <li><i class="fa fa-check text-success"></i> a logbook to keep track of your learning and development</li>
                                        <li><i class="fa fa-check text-success"></i> printing your certificates</li>
                                   </ul>
                                   <!-- <a href="/auth/register" class="btn btn-primary">Register Now</a> -->
                               </div>
                            </div>
                        </div>
                    </div>
                    
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="/assets/frontend/js/rsa_validator.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.3.4/vue.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>
<script src="/assets/frontend/js/intlTelInput.js"></script>

<script>
    function ValidateID(id_number) {
            var sectionTestsSuccessFull = 1;
            var MessageCodeArray = [];
            var MessageDescriptionArray = [];
            var currentTime = new Date();

            /* DO ID LENGTH TEST */
            if (id_number.length == 13) {
                /* SPLIT ID INTO SECTIONS */
                var year = id_number.substr(0, 2);
                var month = id_number.substr(2, 2);
                var day = id_number.substr(4, 2);
                var gender = (id_number.substr(6, 4) * 1);
                var citizen = (id_number.substr(10, 2) * 1);
                var check_sum = (id_number.substr(12, 1) * 1);

                /* DO YEAR TEST */
                var nowYearNotCentury = currentTime.getFullYear() + '';
                nowYearNotCentury = nowYearNotCentury.substr(2, 2);
                if (year <= nowYearNotCentury) {
                    year = '20' + year;
                } else {
                    year = '19' + year;
                }//end if
                if ((year > 1900) && (year < currentTime.getFullYear())) {
                    //correct
                } else {
                    sectionTestsSuccessFull = 0;
                    MessageCodeArray[MessageCodeArray.length] = 1;
                    MessageDescriptionArray[MessageDescriptionArray.length] = 'Year is not valid, ';
                }//end if

                /* DO MONTH TEST */
                if ((month > 0) && (month < 13)) {
                    //correct
                } else {
                    sectionTestsSuccessFull = 0;
                    MessageCodeArray[MessageCodeArray.length] = 2;
                    MessageDescriptionArray[MessageDescriptionArray.length] = 'Month is not valid, ';
                }//end if

                /* DO DAY TEST */
                if ((day > 0) && (day < 32)) {
                    //correct
                } else {
                    sectionTestsSuccessFull = 0;
                    MessageCodeArray[MessageCodeArray.length] = 3;
                    MessageDescriptionArray[MessageDescriptionArray.length] = 'Day is not valid, ';
                }//end if

                /* DO DATE TEST */
                if ((month == 4 || month == 6 || month == 9 || month == 11) && day == 31) {
                    sectionTestsSuccessFull = 0;
                    MessageCodeArray[MessageCodeArray.length] = 4;
                    MessageDescriptionArray[MessageDescriptionArray.length] = 'Date not valid. This month does not have 31 days, ';
                }
                if (month == 2) { // check for february 29th
                    var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
                    if (day > 29 || (day == 29 && !isleap)) {
                        sectionTestsSuccessFull = 0;
                        MessageCodeArray[MessageCodeArray.length] = 5;
                        MessageDescriptionArray[MessageDescriptionArray.length] = 'Date not valid. February does not have ' + day + ' days for year ' + year + ', ';
                    }//end if
                }//end if

                /* DO GENDER TEST */
                if ((gender >= 0) && (gender < 10000)) {
                    //correct
                } else {
                    sectionTestsSuccessFull = 0;
                    MessageCodeArray[MessageCodeArray.length] = 6;
                    MessageDescriptionArray[MessageDescriptionArray.length] = 'Gender is not valid, ';
                }//end if

                /* DO CITIZEN TEST */
                //08 or 09 SA citizen
                //18 or 19 Not SA citizen but with residence permit
                if ((citizen == 8) || (citizen == 9) || (citizen == 18) || (citizen == 19)) {
                    //correct
                } else {
                    sectionTestsSuccessFull = 0;
                    MessageCodeArray[MessageCodeArray.length] = 7;
                    MessageDescriptionArray[MessageDescriptionArray.length] = 'Citizen value is not valid, ';
                }//end if

                /* GET CHECKSUM VALUE */
                var check_sum_odd = 0;
                var check_sum_even = 0;
                var check_sum_even_temp = "";
                var check_sum_value = 0;
                var count = 0;
                // Get ODD Value
                for (count = 0; count < 11; count += 2) {
                    check_sum_odd += (id_number.substr(count, 1) * 1);
                }//end for
                // Get EVEN Value
                for (count = 0; count < 12; count += 2) {
                    check_sum_even_temp = check_sum_even_temp + (id_number.substr(count + 1, 1)) + '';
                }//end for
                check_sum_even_temp = check_sum_even_temp * 2;
                check_sum_even_temp = check_sum_even_temp + '';
                for (count = 0; count < check_sum_even_temp.length; count++) {
                    check_sum_even += (check_sum_even_temp.substr(count, 1)) * 1;
                }//end for
                // GET Checksum Value
                check_sum_value = (check_sum_odd * 1) + (check_sum_even * 1);
                check_sum_value = check_sum_value + 'xxx';
                check_sum_value = (10 - (check_sum_value.substr(1, 1) * 1));
                if (check_sum_value == 10)
                    check_sum_value = 0;

                /* DO CHECKSUM TEST */
                if (check_sum_value == check_sum) {
                    //correct
                } else {
                    sectionTestsSuccessFull = 0;
                    MessageCodeArray[MessageCodeArray.length] = 8;
                    MessageDescriptionArray[MessageDescriptionArray.length] = 'Checksum is not valid, ';
                }//end if

            } else {
                sectionTestsSuccessFull = 0;
                MessageCodeArray[MessageCodeArray.length] = 0;
                MessageDescriptionArray[MessageDescriptionArray.length] = 'IDNo is not the right length, ';
            }//end if

            var returnArray = [sectionTestsSuccessFull, MessageCodeArray, MessageDescriptionArray];
            return sectionTestsSuccessFull;
        }//end function

        $().ready(function () {

            $('#new_id_number').rsa_id_validator({
                displayValid: [true,"true","false"],
                displayValid_id: "valid_id_number",
                displayDate_id: "date",
                displayAge_id: "age",
                displayGender_id: "gender",
                displayCitizenship_id: "citizen"
            });
         
            var input = document.querySelector("#cell");
            window.intlTelInput(input, {
            // allowDropdown: false,
            autoHideDialCode: false,
            autoPlaceholder: "off",
            // dropdownContainer: document.body,
            // excludeCountries: ["us"],
            initialCountry: "za",
            formatOnDisplay: true,
            // geoIpLookup: function(callback) {
            //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
            //     var countryCode = (resp && resp.country) ? resp.country : "";
            //     callback(countryCode);
            //   });
            // },
            hiddenInput: "full_number",
            // initialCountry: "auto",
            // localizedCountries: { 'de': 'Deutschland' },
            nationalMode: false,
            // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
            // placeholderNumberType: "MOBILE",
            // preferredCountries: ['cn', 'jp'],
            separateDialCode: true,
            utilsScript: "/assets/frontend/js/utils.js",
            });

            $('#switcheroo').on('change', function (e) {
                $('#nocitizen').val('');
                $('#idno').val('');
            })

            $('#idno').rsa_id_validator({
                displayValid: [true, "true", "false"],
                displayValid_id: "id_number",
                displayDate_id: "date",
                displayAge_id: "age",
                displayGender_id: "gender",
                displayCitizenship_id: "citizen"
            });

            $('#idno').on('blur', function (e) {
                if (!e.target.value) {
                    return;
                }

                if (!ValidateID(e.target.value)) {
                    swal({
                        type: 'error',
                        title: 'Invalid ID Number',
                        text: 'The ID Number you have provided seems te be invalid, please use a valid South African ID Number'
                    })
                }
            })

            setInterval(function()  {
                if (
                    ($('#email').val().length > 0) &&
                    ($('#first_name').val().length > 0) &&
                    ($('#last_name').val().length > 0) &&
                    ($('#cell').val().length > 0) &&
                    ($('#password').val().length > 0) &&
                    ($('#password_confirmation').val().length > 0) && ($('#password').val() == $('#password_confirmation').val())
                ) {
                    
                    // $('#nextstep').removeClass('hidden')
                    $('#nextstep').removeClass('disabled')
                    $('#dummybutton').addClass('hidden')
                    $("#terms_and_conditions").selected(true);
                } else {
                    
                    // $('#nextstep').addClass('hidden')
                    $('#nextstep').addClass('disabled')
                    $('#dummybutton').removeClass('hidden')
                }
                
            }, 1000)

        });
</script>

<script>
    jQuery(document).ready(function ($) {
            $('#nextstep').on('click', function (e) {

                if (($('#password').val().length > 0) && ($('#password_confirmation').val().length > 0)) {
                    if ($('#password').val() != $('#password_confirmation').val()) {
                        swal({
                            type: 'error',
                            title: 'Password Mismatch',
                            text: 'Your password and password confirmation does not match, Please try again'
                        })
                    } else {
                        var host = "{{URL::to('/')}}"
                        var email = $('#email').val();

                        if ($('#idno').val()) {
                            var number = $('#idno').val()
                        } else if ($('#nocitizen').val()) {
                            var number = $('#nocitizen').val();
                        } else {
                            var number = $('#idno').val();
                        }

                        $.ajax({
                            type: "POST",
                            url: host + '/verify/email_id',
                            data: {email: email, number: number, _token: "{{ csrf_token() }}"},
                            success: function (object) {
                                if (object['status'] == 'error') {
                                    $('#existing_Account_Modal').modal({
                                        show: 'true'
                                    });
                                } else {
                                    $('#hidden_next').click();
                                }
                            }
                        });
                    }
                }
            });

          /*  setInterval(function() {
                if (
                    ($('#address_line_one').val().length > 0) &&
                    ($('#address_line_two').val().length > 0) &&
                    ($('#province').val().length > 0) &&
                    ($('#country').val().length > 0) &&
                    ($('#city').val().length > 0) &&
                    ($('#area_code').val().length > 0)
                ) {
                    $('#nextstep2').removeClass('hidden')
                    $('#dummybutton2').addClass('hidden')
                } else {
                    $('#nextstep2').addClass('hidden')
                    $('#dummybutton2').removeClass('hidden')
                }
            }, 500)

            setInterval(function() {
                if (
                    ($('#body').val().length > 0)
                ) {

                    if ($('#body').val() == 'other') {
                        if ($('#specified_body').val().length > 0) {
                            $('#nextstep3').removeClass('hidden')
                            $('#dummybutton3').addClass('hidden')
                        } else {
                            $('#nextstep3').addClass('hidden')
                            $('#dummybutton3').removeClass('hidden')
                        }
                    } else {
                        $('#nextstep3').removeClass('hidden')
                        $('#dummybutton3').addClass('hidden')
                    }
                } else {
                    $('#nextstep3').addClass('hidden')
                    $('#dummybutton3').removeClass('hidden')
                }
            }, 500)

            setInterval(function() {
                if (
                    ($('input[name="terms"]:checked').length > 0)
                ) {
                    $('#nextstep4').removeClass('hidden')
                    $('#dummybutton4').addClass('hidden')
                } else {
                    $('#nextstep4').addClass('hidden')
                    $('#dummybutton4').removeClass('hidden')
                }
            }, 500)*/
        });
</script>

<script>
    function loginRegisterToggle() {
        var checkbox = $('#switch-to-login');
        if(checkbox.val() == 'no') {
            checkbox.val('yes')
            // checkbox.prop('checked', true)
            $('.login-form').show()
            $('.register-form').hide()
            $('.section-three').hide()
        } else {
            checkbox.val('no')
            // checkbox.prop('checked', false)
            $('.login-form').hide()
            $('.register-form').show()
            $('.section-three').show()
        }
    }
    $(document).ready(function() {
        // loginRegisterToggle();
        $('.login-switch-toggle').click(function() {
            loginRegisterToggle();
        })
        $('#login_form').attr('action', "{{url('/')}}"+"/auth/login")
    })
</script>
@endsection