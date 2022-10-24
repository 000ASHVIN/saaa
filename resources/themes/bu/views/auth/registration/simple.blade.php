@extends('app')

@section('content')
    <section class="alternate" style="background-image: url('/assets/frontend/images/demo/wall2.jpg');">
        <div id="registration_page">
            <div class="container">
                <div class="row">
                    <div class="wizard">
                        <div class="wizard-inner">
                            <div class="connecting-line"></div>

                            <div class="custom-bg margin-bottom-20" id="registration_steps">
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
                            </div>

                            <div class="panel panel-default" style="margin-bottom: 0px">
                                <div class="panel-body">
                                    <p class="text-center">
                                        Once you have created a user profile you will gain access to our free Monthly
                                        Practice Management Series <br> and be able to book for events and signup for CDP
                                        Subscription Packages.
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
                                            Personal Information
                                            <span class="pull-right clearfix"><i class="fa fa-user"></i></span>
                                        </h2>
                                    </div>

                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                                                    <div class="pull-left">{!! Form::label('first_name', 'First Name') !!}</div>
                                                    <small class="pull-right">* Required</small>
                                                    {!! Form::input('text', 'first_name', null, ['class' => 'form-control']) !!}
                                                    @if ($errors->has('first_name'))
                                                        <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
                                                </div>

                                                <div class="form-group @if ($errors->has('id_number')) has-error @endif">
                                                    <span class="pull-right">

                                                        <label class="switch switch switch-round">
                                                            <input type="checkbox" name="nocitizen" id="switcheroo"
                                                                   v-model="nocitizen">
                                                            <span class="switch-label" data-on="YES"
                                                                  data-off="NO"></span>
                                                            <span> Not a South African citizen?</span>
                                                        </label>
                                                    </span>

                                                    <div v-show="nocitizen == true">
                                                        <span class="pull-left">{!! Form::label('id_number', 'Your Passport Number') !!}</span>
                                                        {!! Form::input('text', 'id_number', null, ['class' => 'form-control', 'id' => 'nocitizen']) !!}
                                                        @if ($errors->has('id_number'))<p
                                                                class="help-block">{{ $errors->first('id_number') }}</p> @endif
                                                    </div>

                                                    <div v-show="! nocitizen">
                                                        <span class="pull-left">{!! Form::label('id_number', 'South African ID Number') !!}</span>
                                                        {!! Form::input('text', 'id_number', null, ['class' => 'form-control idno', 'id' => 'idno', 'min' => '13']) !!}
                                                        @if ($errors->has('id_number'))<p
                                                                class="help-block">{{ $errors->first('id_number') }}</p> @endif
                                                    </div>

                                                </div>

                                                <span id="id_results"></span>
                                                <input type="hidden" name="verified" id="id_number">
                                                <input type="hidden" id="date">
                                                <input type="hidden" id="age">
                                                <input type="hidden" id="gender">
                                                <input type="hidden" id="citizen">

                                                <div class="form-group @if ($errors->has('email')) has-error @endif">
                                                    <div class="pull-left"> {!! Form::label('email', 'Email Address') !!}</div>
                                                    <small class="pull-right">* Required</small>
                                                    {!! Form::input('email', 'email', null, ['class' => 'form-control', 'id' => 'email']) !!}
                                                    @if ($errors->has('email'))
                                                        <p class="help-block">{{ $errors->first('email') }}</p> @endif
                                                </div>

                                                <div class="form-group @if ($errors->has('password')) has-error @endif">
                                                    <div class="pull-left">{!! Form::label('password', 'Password') !!}</div>
                                                    <small class="pull-right">* Required</small>
                                                    {!! Form::input('password', 'password', null, ['class' => 'form-control']) !!}
                                                    @if ($errors->has('password'))
                                                        <p class="help-block">{{ $errors->first('password') }}</p> @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group @if ($errors->has('last_name')) has-error @endif">
                                                    <div class="pull-left">{!! Form::label('last_name', 'Last Name') !!}</div>
                                                    <small class="pull-right">* Required</small>
                                                    {!! Form::input('text', 'last_name', null, ['class' => 'form-control']) !!}
                                                    @if ($errors->has('last_name'))
                                                        <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
                                                </div>

                                                <div class="form-group @if ($errors->has('cell')) has-error @endif">
                                                    <div class="pull-left">{!! Form::label('cell', 'Cell') !!}</div>
                                                    <small class="pull-right">* Required</small>
                                                    {!! Form::input('text', 'cell', null, ['class' => 'form-control']) !!}
                                                    @if ($errors->has('cell'))
                                                        <p class="help-block">{{ $errors->first('cell') }}</p> @endif
                                                </div>

                                                <div class="form-group @if ($errors->has('alternative_cell')) has-error @endif">
                                                    <div class="pull-left">{!! Form::label('alternative_cell', 'Alternative Contact Number') !!}</div>
                                                    <small class="pull-right">* Required</small>
                                                    {!! Form::input('text', 'alternative_cell', null, ['class' => 'form-control']) !!}
                                                    @if ($errors->has('alternative_cell'))
                                                        <p class="help-block">{{ $errors->first('alternative_cell') }}</p> @endif
                                                </div>

                                                <div class="form-group @if ($errors->has('password_confirmation')) has-error @endif">
                                                    <div class="pull-left">{!! Form::label('password_confirmation', 'Password Confirmation') !!}</div>
                                                    <small class="pull-right">* Required</small>
                                                    {!! Form::input('password', 'password_confirmation', null, ['class' => 'form-control']) !!}
                                                    @if ($errors->has('password_confirmation'))
                                                        <p class="help-block">{{ $errors->first('password_confirmation') }}</p> @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-inline pull-right">
                                    <li>
                                        <a href="#" class="btn btn-primary disabled" id="dummybutton">Proceed to next
                                            Step</a>
                                        <button type="button" class="btn btn-primary hidden" id="nextstep">Proceed to
                                            next Step
                                        </button>
                                    </li>
                                </ul>

                                <button type="button" class="btn btn-primary next-step hidden"
                                        id="hidden_next"></button>
                            </div>

                            <div class="tab-pane" role="tabpanel" id="step2">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h2 class="panel-title">
                                            Address Details
                                            <span class="pull-right clearfix">
                                        <i class="fa fa-home"></i>
                                    </span>
                                        </h2>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group @if ($errors->has('address_line_one')) has-error @endif">
                                                    {!! Form::label('address_line_one', 'Address Line One') !!}
                                                    {!! Form::input('text', 'address_line_one', null, ['class' => 'form-control']) !!}
                                                    @if ($errors->has('address_line_one'))
                                                        <p class="help-block">{{ $errors->first('address_line_one') }}</p> @endif
                                                </div>

                                                <div class="form-group @if ($errors->has('country')) has-error @endif">
                                                    {!! Form::label('country', 'Country') !!}
                                                    <select class="form-control" id="country" name="country">
                                                        @include('auth.includes.countries')
                                                    </select>
                                                    @if ($errors->has('country'))<p
                                                            class="help-block">{{ $errors->first('country') }}</p> @endif
                                                </div>

                                                <div class="form-group @if ($errors->has('province')) has-error @endif">
                                                    {!! Form::label('province', 'Province') !!}
                                                    {!! Form::select('province', [
                                                        'GP' => 'Gauteng',
                                                        'EC' => 'Eastern Cape',
                                                        'FS' => 'Free State',
                                                        'KZN' => 'KwaZulu-Natal',
                                                        'LP' => 'Limpopo',
                                                        'MP' => 'Mpumalanga',
                                                        'NW' => 'North West',
                                                        'NC' => 'Northern Cape',
                                                        'WC' => 'Western Cape',
                                                        'ETO' => 'Etosha',
                                                        'OSH' => 'Oshana',
                                                        'OSK' => 'Oshakati',
                                                        'WDH' => 'Windhoek',
                                                        'other' => 'Other',
                                                    ],null, ['class' => 'form-control']) !!}
                                                    @if ($errors->has('province'))
                                                        <p class="help-block">{{ $errors->first('province') }}</p> @endif
                                                </div>


                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group @if ($errors->has('address_line_two')) has-error @endif">
                                                    {!! Form::label('address_line_two', 'Address Line Two') !!}
                                                    {!! Form::input('text', 'address_line_two', null, ['class' => 'form-control']) !!}
                                                    @if ($errors->has('address_line_two'))
                                                        <p class="help-block">{{ $errors->first('address_line_two') }}</p> @endif
                                                </div>

                                                <div class="form-group @if ($errors->has('city')) has-error @endif">
                                                    {!! Form::label('city', 'City') !!}
                                                    {!! Form::input('text', 'city', null, ['class' => 'form-control']) !!}
                                                    @if ($errors->has('city'))
                                                        <p class="help-block">{{ $errors->first('city') }}</p> @endif
                                                </div>

                                                <div class="form-group @if ($errors->has('area_code')) has-error @endif">
                                                    {!! Form::label('area_code', 'Area Code') !!}
                                                    {!! Form::input('text', 'area_code', null, ['class' => 'form-control']) !!}
                                                    @if ($errors->has('area_code'))
                                                        <p class="help-block">{{ $errors->first('area_code') }}</p> @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-inline pull-right">
                                    <li>
                                        <button type="button" class="btn btn-default prev-step">Back to previous
                                        </button>
                                    </li>
                                    <li>
                                        <a href="#" class="btn btn-primary disabled" id="dummybutton2">Proceed to next
                                            Step</a>
                                        <button type="button" class="btn btn-primary next-step hidden" id="nextstep2">
                                            Proceed to next Step
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-pane" role="tabpanel" id="step3">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h2 class="panel-title">
                                            Please select your Professional Body
                                            <span class="pull-right clearfix">
                                        <i class="fa fa-home"></i>
                                    </span>
                                        </h2>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="professional-body">
                                                <div class="col-md-12">
                                                    <div class="form-group @if ($errors->has('body')) has-error @endif">
                                                        {!! Form::label('body', 'Professional Body') !!}

                                                        <select name="body" id="body" v-model="bodies"
                                                                class="form-control">
                                                            <option value="" @if(old('body') == '') selected
                                                                    @endif selected>Please Select
                                                            </option>
                                                            @foreach($bodies as $body)
                                                                <option value="{{ $body->id }}"
                                                                        @if(old('body') == $body->id) selected @endif>{{ ucfirst($body->title) }}</option>
                                                            @endforeach
                                                            <option value="other"
                                                                    @if(old('body') == 'other') selected @endif>Other
                                                                (Specify)
                                                            </option>
                                                        </select>

                                                        @if ($errors->has('body'))
                                                            <p class="help-block">{{ $errors->first('body') }}</p> @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-12" v-show="bodies !== '17' && bodies !== ''">
                                                    <div class="form-group @if ($errors->has('membership_number')) has-error @endif">
                                                        {!! Form::label('membership_number', 'Membership Number') !!}
                                                        {!! Form::input('text', 'membership_number', null, ['class' => 'form-control', 'id' => 'membership_number']) !!}
                                                        @if ($errors->has('membership_number'))
                                                            <p class="help-block">{{ $errors->first('membership_number') }}</p> @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-12" v-show="bodies == 'other'">
                                                    <div class="form-group @if ($errors->has('specified_body')) has-error @endif">
                                                        {!! Form::label('specified_body', 'Please Specify') !!}
                                                        {!! Form::input('text', 'specified_body', null, ['class' => 'form-control', 'id' => 'specified_body']) !!}
                                                        @if ($errors->has('specified_body'))
                                                            <p class="help-block">{{ $errors->first('specified_body') }}</p> @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <ul class="list-inline pull-right">
                                    <li>
                                        <button type="button" class="btn btn-default prev-step">Back to previous
                                        </button>
                                    </li>
                                    <li>
                                        <a href="#" class="btn btn-primary disabled" id="dummybutton3">Proceed to next
                                            Step</a>
                                        <button type="button" class="btn btn-primary next-step hidden" id="nextstep3">
                                            Proceed to next Step
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-pane" role="tabpanel" id="complete">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h2 class="panel-title">
                                            Terms & Conditions
                                        </h2>
                                    </div>

                                    @include('auth.includes.terms_and_conditions')
                                    <div class="panel-footer">
                                        <div class="form-group">
                                            <span class="help-block" style="display: none;">
                                                <strong></strong>
                                            </span>
                                            <label class="checkbox nomargin">
                                                <input type="checkbox" name="terms" id="terms_and_conditions">
                                                <i></i>
                                                I Accept The <a href="/terms_and_conditions" target="_blank">Terms Of
                                                    Service</a>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <ul class="list-inline pull-right">
                                    <li>
                                        <button type="button" class="btn btn-default prev-step">Back to previous
                                        </button>
                                    </li>
                                    <li>
                                        <a href="#" class="btn btn-primary disabled" id="dummybutton4">Submit</a>
                                        <button type="submit" class="btn btn-primary next-step hidden" id="nextstep4">
                                            Submit
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        {!! Form::close() !!}

                        @include('auth.registration.includes.existing_account')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script src="/assets/frontend/js/rsa_validator.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.3.4/vue.js"></script>
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
                    ($('#idno').val().length > 0 || $('#nocitizen').val().length > 0) &&
                    ($('#first_name').val().length > 0) &&
                    ($('#last_name').val().length > 0) &&
                    ($('#cell').val().length > 0) &&
                    ($('#password').val().length > 0) &&
                    ($('#password_confirmation').val().length > 0) &&
                    (ValidateID($('#idno').val()) == 1 || $('#nocitizen').val().length > 0)
                ) {
                    $('#nextstep').removeClass('hidden')
                    $('#dummybutton').addClass('hidden')
                } else {
                    $('#nextstep').addClass('hidden')
                    $('#dummybutton').removeClass('hidden')
                }

                if ($('#nocitizen').val().length > 0) {
                    $("input[name*='id_number']").val($('#nocitizen').val());
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

            setInterval(function() {
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
            }, 500)
        });
    </script>
@endsection