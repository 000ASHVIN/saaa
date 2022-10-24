@extends('app')

@section('content')

@section('styles')
<link href="/assets/frontend/css/intlTelInput.css" rel="stylesheet" type="text/css" />
<link href="/assets/frontend/css/demo.css" rel="stylesheet" type="text/css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet" />
<style>
    .iti__selected-flag {
        padding: 5px 6px 0 8px;
    }
    .iti__flag-container{
        top: 25px !important;
    }
    .select2-container--default {
        width: 100% !important;
    }
    .other_industry {
        margin-top: 5px;
    }
</style>
@stop
 

<?php 
$request = Input::all();
$plan = null;
if(isset($_COOKIE['plan']))
{
    $plan = \App\Subscriptions\Models\Plan::where('id',$_COOKIE['plan'])->first();
}



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

                            <div class="panel panel-default" style="margin-bottom: 0px">
                                <div class="panel-body">
                                    <p class="text-center">
                                        Once you have created a user profile you will be able to sign up for a CPD subscription package or a Course, book webinars and purchase videos from our Webinars on-demand section.

                                    </p>
                                </div>
                            </div>
                        </div>

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
                                                    class="form-group @if ($errors->has('first_name')) has-error @endif">
                                                    <div class="pull-left">{!! Form::label('first_name', 'First Name')
                                                        !!}</div>
                                                    <small class="pull-right">* Required</small>
                                                    {!! Form::input('text', 'first_name', null, ['class' =>
                                                    'form-control']) !!}
                                                    @if ($errors->has('first_name'))
                                                    <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
                                                </div>
                                                <div
                                                    class="form-group @if ($errors->has('last_name')) has-error @endif">
                                                    <div class="pull-left">{!! Form::label('last_name', 'Last Name') !!}
                                                    </div>
                                                    <small class="pull-right">* Required</small>
                                                    {!! Form::input('text', 'last_name', null, ['class' =>
                                                    'form-control']) !!}
                                                    @if ($errors->has('last_name'))
                                                    <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
                                                </div>

                                                <div class="form-group @if ($errors->has('email')) has-error @endif">
                                                    <div class="pull-left"> {!! Form::label('email', 'Email Address')
                                                        !!}</div>
                                                    <small class="pull-right">* Required</small>
                                                    {!! Form::input('email', 'email', (isset($_COOKIE['email']))?$_COOKIE['email']:"", ['class' => 'form-control',
                                                    'id' => 'email']) !!}
                                                    @if ($errors->has('email'))
                                                    <p class="help-block">{{ $errors->first('email') }}</p> @endif
                                                </div>


                                                <div class="form-group @if ($errors->has('cell')) has-error @endif">
                                                    <div class="pull-left">{!! Form::label('cell', 'Cell') !!}</div>
                                                    <small class="pull-right">* Required</small>
                                                   
                                                    {!! Form::input('tel','cell', null , ['class' => 'form-control','id' => 'cell']) !!}
                           
                                                    <!-- {!! Form::input('text', 'cell', null, ['class' => 'form-control'])
                                                    !!} -->
                                                    @if ($errors->has('cell'))
                                                    <p class="help-block">{{ $errors->first('cell') }}</p> @endif
                                                </div>






                                                <div class="form-group @if ($errors->has('body')) has-error @endif">


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

                                                        @if ($errors->has('body'))
                                                        <p class="help-block">{{ $errors->first('body') }}</p> @endif



                                                        <div v-show="bodies !== '17' && bodies !== ''">
                                                            <div
                                                                class="form-group @if ($errors->has('membership_number')) has-error @endif">
                                                                {!! Form::label('membership_number', 'Membership
                                                                Number') !!}
                                                                {!! Form::input('text', 'membership_number', null,
                                                                ['class' => 'form-control', 'id' =>
                                                                'membership_number']) !!}
                                                                @if ($errors->has('membership_number'))
                                                                <p class="help-block">
                                                                    {{ $errors->first('membership_number') }}</p> @endif
                                                            </div>
                                                        </div>

                                                        <div v-show="bodies == 'other'">
                                                            <div
                                                                class="form-group @if ($errors->has('specified_body')) has-error @endif">
                                                                {!! Form::label('specified_body', 'Please Specify') !!}
                                                                {!! Form::input('text', 'specified_body', null, ['class'
                                                                => 'form-control', 'id' => 'specified_body']) !!}
                                                                @if ($errors->has('specified_body'))
                                                                <p class="help-block">
                                                                    {{ $errors->first('specified_body') }}</p> @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <span id="tax-practitioner-box">

                                                    <label class="switch switch switch-round">
                                                        <span> Are you a tax practitioner?</span>
                                                        <input type="checkbox" name="taxpractitioner" id="tax-practitioner-available" {{ old('taxpractitioner') ? 'checked' : ''}}>
                                                        <span class="switch-label" data-on="YES"
                                                            data-off="NO"></span>

                                                    </label>
                                                </span>
                                                

                                                <div class="form-group industry-box @if ($errors->has('industry')) has-error @endif">
                                                    <div class="row" style="margin-bottom: 0px !important">
                                                        <div class="col-xs-10" style="margin-bottom: 0px !important">
                                                            <div class="pull-left">{!! Form::label('industry', 'Please indicate the Industry / Sector that you work in:') !!}</div>
                                                        </div>
                                                        <div class="col-xs-2" style="margin-bottom: 0px !important">
                                                            <small class="pull-right">* Required</small>
                                                        </div>
                                                    </div>
                                                    
                                                    {!! Form::select('industry', $industry , null, ['id' => 'industry', 'class' => 'form-control', 'placeholder' => 'Select your industry/sector']) !!}
                                                    {!! Form::input('text', 'other_industry', null, ['class' => 'form-control other_industry', 'style' => "display: none;"]) !!}
                                                    
                                                    @if ($errors->has('industry'))
                                                    <p class="help-block">{{ $errors->first('industry') }}</p> @endif
                                                </div>

                                                <div class="practitioner-container" style="display: none">
                                                    <div class="form-group interest-box @if ($errors->has('interest')) has-error @endif">
                                                        <div class="pull-left">{!! Form::label('interest', 'Please indicate your area of practice/work/interest:') !!}</div>
                                                        {{-- <small class="pull-right">* Required</small> --}}
                                                       
                                                        {!! Form::select('interest', $interest, null, ['id' => 'interest', 'class' => 'form-control interest-select', 'name' => "interest[]", 'multiple' => "multiple"]) !!}
                                                        
                                                        @if ($errors->has('interest'))
                                                        <p class="help-block">{{ $errors->first('interest') }}</p> @endif
                                                    </div>
    
                                                    <div class="form-group employment-box @if ($errors->has('employment')) has-error @endif">
                                                        <div class="pull-left">{!! Form::label('employment', 'Which of the options below best describes your current employment/work?') !!}</div>
                                                        
                                                        {!! Form::select('employment', $employment  , null, ['id' => 'employment', 'class' => 'form-control', 'placeholder' => 'Select your current employment/work']) !!}
                                                        
                                                        @if ($errors->has('employment'))
                                                        <p class="help-block">{{ $errors->first('employment') }}</p> @endif
                                                    </div>

                                                </div>

                                                <div class="form-group @if ($errors->has('password')) has-error @endif">
                                                    <div class="pull-left">{!! Form::label('password', 'Password') !!}
                                                    </div>
                                                    <small class="pull-right">* Required</small>
                                                    {!! Form::input('password', 'password', null, ['class' =>
                                                    'form-control']) !!}
                                                    @if ($errors->has('password'))
                                                    <p class="help-block">{{ $errors->first('password') }}</p> @endif
                                                </div>
                                                <div
                                                    class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
                                                    <div class="pull-left">{!! Form::label('password_confirmation',
                                                        'Password Confirmation') !!}</div>
                                                    <small class="pull-right">* Required</small>
                                                    {!! Form::input('password', 'password_confirmation', null, ['class'
                                                    => 'form-control']) !!}
                                                    @if ($errors->has('password_confirmation'))
                                                    <p class="help-block">{{ $errors->first('password_confirmation') }}
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
                                                Create Account
                                            </button>

                                        </div>
                                    </div>
                                </div>





                            </div>


                            <div class="clearfix"></div>
                        </div>
                        {!! Form::close() !!}

                        @include('auth.registration.includes.existing_account')
                    </div>
                </div>
                <div class="col-md-6">
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



                </div>


                <div class="col-md-5">
                        <div class="box-static box-border-top padding-30">
                            <div class="box-title margin-bottom-30">
                                <h1 class="size-20 mb-30">New Member ?</h1>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <p>After filling in your information a profile will be created.</p>
                                    <h2 class="weight-300 letter-spacing-1 size-13" style="margin-bottom: 10px;"><span>Join more than {{ \App\Users\User::where('deleted_at', null)->count() }} practitioners</span></h2>
                                    <ul class="list-unstyled list-icons">
                                        <li><i class="fa fa-check text-success"></i> Personal online learning profile.</li>
                                        <li><i class="fa fa-check text-success"></i> Firm wide training and monitoring.</li>
                                        <li><i class="fa fa-check text-success"></i> Keep track of your CPD hours.</li>
                                        <li><i class="fa fa-check text-success"></i> Print your CPD certificate.</li>
                                        <li><i class="fa fa-check text-success"></i> Attend or download webinars.</li>
                                        <li><i class="fa fa-check text-success"></i> Monthly Tax Update.</li>
                                        <li><i class="fa fa-check text-success"></i> Access to technical query helpline. </li>
                                    </ul>
                                    {{--  <a href="/auth/register" class="btn btn-primary">Register Now</a>  --}}
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.interest-select').select2();
        // $('#employment').select2();
        if($('#industry').val() == 'Other') {
            $('.other_industry').show();
        } else {
            $('.other_industry').hide();
        }
        $('#industry').change(function() {
            if($(this).val() == 'Other') {
                $('.other_industry').show();
            } else {
                $('.other_industry').hide();
            }
        })
    });
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
            initialCountry: "za",
            // dropdownContainer: document.body,
            // excludeCountries: ["us"],
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
            if($('#tax-practitioner-available').is(":checked")) {
                $('.practitioner-container').show()
                $('.industry-box').hide()
            }
            $("#tax-practitioner-box").unbind('click');
            $('#tax-practitioner-box').off().click(function() {
                $permission = $('#tax-practitioner-available').is(":checked")

                if(!$permission) {
                    $('.practitioner-container').show()
                    $('.industry-box').hide()
                    $('#tax-practitioner-available').prop('checked', true)
                } else {
                    $('.industry-box').show()
                    $('.practitioner-container').hide()
                    $('#tax-practitioner-available').prop('checked', false)
                }
            });
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
@endsection