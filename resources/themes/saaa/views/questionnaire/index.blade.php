@extends('app')

@section('content')

@section('title')
    Update your details and WIN!
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('questionnaire') !!}
@stop

@section('styles')
    <script src="http://demo.expertphp.in/js/jquery.js"></script>
@endsection

<section class="alternate" style="background-image: url('/assets/frontend/images/demo/wall2.jpg');">
    <div class="container">
        <div class="row">
            {!! Form::open() !!}
            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82); text-align: left">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                            {!! Form::label('first_name', 'First Name') !!}
                            {!! Form::input('text', 'first_name', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('first_name')) <p
                                    class="help-block">{{ $errors->first('first_name') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('last_name')) has-error @endif">
                            {!! Form::label('last_name', 'Last Name') !!}
                            {!! Form::input('text', 'last_name', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('last_name')) <p
                                    class="help-block">{{ $errors->first('last_name') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('contact_number')) has-error @endif">
                            {!! Form::label('contact_number', 'Contact Number') !!}
                            {!! Form::input('text', 'contact_number', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('contact_number')) <p
                                    class="help-block">{{ $errors->first('contact_number') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('email')) has-error @endif">
                            {!! Form::label('email', 'Email Address') !!}
                            {!! Form::input('text', 'email', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('age')) has-error @endif">
                            {!! Form::label('age', 'Your Age Range') !!}
                            {!! Form::select('age', [
                                '20-30' => 'Between 20-30',
                                '30-40' => 'Between 30-40',
                                '40-50' => 'Between 40-50',
                                '50-60' => 'Between 50-60',
                                '60+' => '60 and up'
                            ],null, ['class' => 'form-control']) !!}
                            @if ($errors->has('age')) <p class="help-block">{{ $errors->first('age') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('gender')) has-error @endif">
                            {!! Form::label('gender', 'Gender') !!}
                            {!! Form::select('gender', [
                                'male' => 'Male',
                                'female' => 'Female'
                            ],null, ['class' => 'form-control']) !!}
                            @if ($errors->has('gender')) <p class="help-block">{{ $errors->first('gender') }}</p> @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Select Country:</label>
                            {!! Form::select('country', ['' => 'Select'] +$countries,'',array('class'=>'form-control','id'=>'country'));!!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Select State:</label>
                            <select name="state" id="state" class="form-control">
                                <option value="">Select your country</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Select City:</label>
                            <select name="city" id="city" class="form-control">
                                <option value="">Select your state</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('income')) has-error @endif">
                            {!! Form::label('income', 'Personal Income range: (In R10 000’s)') !!}
                            {!! Form::select('income', [
                                '0 - 300 000 pa' => '0 - 300 000 pa',
                                '300 001 - 500 000 pa' => '300 001 - 500 000 pa',
                                '500 001 - 700 000 pa' => '500 001 - 700 000 pa',
                                '700 001 - 900 000 pa' => '700 001 - 900 000 pa',
                                '900 001 - 1 500 000 pa' => '900 001 - 1 500 000 pa',
                                'More than 2 500 000 pa' => 'More than 2 500 000 pa',
                            ],null, ['class' => 'form-control']) !!}
                            @if ($errors->has('income')) <p class="help-block">{{ $errors->first('income') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('race')) has-error @endif">
                            {!! Form::label('race', 'Please Select your Race') !!}
                            {!! Form::select('race', [
                                'Black' => 'Black',
                                'Indian' => 'Indian',
                                'Coloured' => 'Coloured',
                                'White' => 'White',
                            ],null, ['class' => 'form-control']) !!}
                            @if ($errors->has('race')) <p class="help-block">{{ $errors->first('race') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group @if ($errors->has('job_title')) has-error @endif">
                            {!! Form::label('job_title', 'Select the job title that best describes your current position') !!}
                            {!! Form::select('job_title', [
                                'Bookkeeper' => 'Bookkeeper',
                                'Senior Accountant' => 'Senior Accountant',
                                'Financial manager' => 'Financial Manager',
                                'Financial Director' => 'Financial Director',
                                'Practice Owner' => 'Practice Owner',
                                'Debtor clerk' => 'Debtor Clerk',
                                'Creditor clerk' => 'Creditor Clerk',
                                'Financial accountant' => 'Financial Accountant',
                                'Financial controller' => 'Financial Controller',
                                'Management accountant' => 'Management Accountant',
                                'Forensic accountant' => 'Forensic Accountant',
                                'Company secretary' => 'Company Secretary',
                                'Tax practitioner' => 'Tax Practitioner',
                                'Tax professional' => 'Tax Professional',
                                'Trainee' => 'Trainee',
                                'Technician' => 'Technician',
                                'other' => 'Other',
                            ],null, ['class' => 'form-control', 'v-model' => 'jobTitle', 'placeholder' => 'Please select']) !!}
                            @if ($errors->has('job_title')) <p
                                    class="help-block">{{ $errors->first('job_title') }}</p> @endif
                        </div>
                    </div>
                    
                    <div class="col-md-12" v-if="jobTitle == 'other'">
                        <div class="form-group @if ($errors->has('other_job_title')) has-error @endif">
                            {!! Form::label('other_job_title', 'Please specify your job title') !!}
                            {!! Form::input('text', 'other_job_title', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('other_job_title')) <p class="help-block">{{ $errors->first('other_job_title') }}</p> @endif
                        </div>
                    </div>

                </div>
            </div>

            <div class="margin-bottom-20"></div>

            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82); text-align: left">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('level_of_education')) has-error @endif">
                            {!! Form::label('level_of_education', 'What is your level of education') !!}
                            {!! Form::select('level_of_education', [
                                'Grade 9' => 'Grade 9',
                                'Grade 10 and National (vocational) Certificates level 2' => 'Grade 10 and National (vocational) Certificates level 2',
                                ' Grade 11 and National (vocational) Certificates level 3' => ' Grade 11 and National (vocational) Certificates level 3',
                                'Grade 12 (National Senior Certificate) and National (vocational) Cert. level 4' => 'Grade 12 (National Senior Certificate) and National (vocational) Cert. level 4',
                                'Higher Certificates and Advanced National (vocational) Cert.' => 'Higher Certificates and Advanced National (vocational) Cert.',
                                'National Diploma and Advanced certificates' => 'National Diploma and Advanced certificates',
                                'Bachelor\'s degree, Advanced Diplomas, Post Graduate Certificate and B-tech' => 'Bachelor\'s degree, Advanced Diplomas, Post Graduate Certificate and B-tech',
                                'Honours degree, Post Graduate diploma and Professional Qualifications' => 'Honours degree, Post Graduate diploma and Professional Qualifications',
                                'Master\'s degree' => 'Master\'s degree',
                                'Doctor\'s degree' => 'Doctor\'s degree',
                            ],null, ['class' => 'form-control']) !!}
                            @if ($errors->has('level_of_education')) <p
                                    class="help-block">{{ $errors->first('level_of_education') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('accounting_field')) has-error @endif">
                            {!! Form::label('accounting_field', 'Accounting fields (Indicate your top three areas of focus in your current job or practice)') !!}
                            {!! Form::select('accounting_field[]', [
                                'Preparing financial statements' => 'Preparing financial statements',
                                'Audits and reviews' => 'Audits and reviews',
                                'Accounting Officer engagements' => 'Accounting Officer engagements',
                                'Accounts preparation (Bookkeeping etc)' => 'Accounts preparation (Bookkeeping etc)',
                                'Completing tax returns' => 'Completing tax returns',
                                'Providing tax opinions' => 'Providing tax opinions',
                                'Management accounts' => 'Management accounts',
                                'Corporate governance' => 'Corporate governance',
                                'Strategic financial management' => 'Strategic financial management',
                                'CIPC secretarial work' => 'CIPC secretarial work',
                                'Company secretary' => 'Company secretary',
                                'Legal advisory' => 'Legal advisory',
                                'IT Governance' => 'IT Governance',
                                'Treasury' => 'Treasury',
                                'Financial controls' => 'Financial controls',
                                'Financial planning' => 'Financial planning',
                            ],null, ['class' => 'form-control select2', 'multiple' => 'true', 'style' => 'width:100%']) !!}
                            @if ($errors->has('accounting_field')) <p class="help-block">{{ $errors->first('accounting_field') }}</p> @endif
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('other_accounting_field')) has-error @endif">
                            {!! Form::label('other_accounting_field', 'Other Accounting Field, (only complete if none of the above aplied to you.)') !!}
                            {!! Form::input('text', 'other_accounting_field', null, ['class' => 'form-control']) !!}
                            @if ($errors->has('other_accounting_field')) <p class="help-block">{{ $errors->first('other_accounting_field') }}</p> @endif
                        </div>
                    </div>


                    <div class="col-md-12 text-center">
                        <hr>
                        <label for="radio"><strong>What type of a professional are you?</strong></label>
                        <label class="radio">
                            <input type="radio" name="type_of_professional" value="own_practice" checked="checked"
                                   v-model="type_of_professional">
                            <i></i> I own my own practice
                        </label>
                        <label class="radio">
                            <input type="radio" name="type_of_professional" value="part_time_practice"
                                   v-model="type_of_professional">
                            <i></i> Part time practice
                        </label>
                        <label class="radio">
                            <input type="radio" name="type_of_professional" value="commerce" v-model="type_of_professional">
                            <i></i> I am an employee in commerce and industry
                        </label>
                        <label class="radio">
                            <input type="radio" name="type_of_professional" value="in_practice"
                                   v-model="type_of_professional">
                            <i></i> I am an employee in practice
                        </label>
                        <label class="radio">
                            <input type="radio" name="type_of_professional" value="other" v-model="type_of_professional">
                            <i></i> Other
                        </label>
                    </div>

                    <div class="col-md-12" v-if="type_of_professional == 'other'">
                        <hr>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="form-group @if ($errors->has('other_accountant_type')) has-error @endif">
                                    {!! Form::label('other_accountant_type', 'Please Specify (Other)') !!}
                                    {!! Form::input('text', 'other_accountant_type', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('other_accountant_type')) <p
                                            class="help-block">{{ $errors->first('other_accountant_type') }}</p> @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12"
                         v-if="type_of_professional == 'own_practice' || type_of_professional == 'part_time_practice'">
                        <hr>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="form-group @if ($errors->has('staff_members_amount')) has-error @endif">
                                    {!! Form::label('staff_members_amount', 'How many staff members do you have?') !!}
                                    {!! Form::input('text', 'staff_members_amount', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('staff_members_amount')) <p
                                            class="help-block">{{ $errors->first('staff_members_amount') }}</p> @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="form-group @if ($errors->has('staff_benefits')) has-error @endif">
                                    {!! Form::label('staff_benefits', 'Do you offer your staff benefits like medical aid, pension and/or retirement funds?') !!}
                                    {!! Form::input('text', 'staff_benefits', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('staff_benefits')) <p
                                            class="help-block">{{ $errors->first('staff_benefits') }}</p> @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                <div class="form-group @if ($errors->has('professional_indemnity')) has-error @endif">
                                    {!! Form::label('professional_indemnity', 'Do you have Professional Indemnity Cover?') !!}
                                    {!! Form::select('professional_indemnity', [
                                        true => 'Yes',
                                        false => 'No'
                                    ],null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('professional_indemnity')) <p
                                            class="help-block">{{ $errors->first('professional_indemnity') }}</p> @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" v-else>
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <div class="form-group @if ($errors->has('organisation_do_you_work')) has-error @endif">
                                    {!! Form::label('organisation_do_you_work', 'Which organisation do you work for?') !!}
                                    {!! Form::input('text', 'organisation_do_you_work', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('organisation_do_you_work')) <p
                                            class="help-block">{{ $errors->first('organisation_do_you_work') }}</p> @endif
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group @if ($errors->has('employer_offer_benefits')) has-error @endif">
                                    {!! Form::label('employer_offer_benefits', 'Does your employer offer benefits like medical aid, pension and/or retirement funds? (yes, no, some)') !!}
                                    {!! Form::select('employer_offer_benefits', [
                                        'yes' => 'Yes',
                                        'no' => 'No',
                                        'some' => 'Some'
                                    ],null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('employer_offer_benefits')) <p
                                            class="help-block">{{ $errors->first('employer_offer_benefits') }}</p> @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('do_you_belong_to_a_professional_body')) has-error @endif">
                            {!! Form::label('do_you_belong_to_a_professional_body', 'Do you belong to a professional body?') !!}
                            {!! Form::select('do_you_belong_to_a_professional_body', [
                                'yes' => 'Yes',
                                'no' => 'No'
                            ],null, ['class' => 'form-control', 'v-model' => 'do_you_belong_to_a_professional_body']) !!}
                            @if ($errors->has('do_you_belong_to_a_professional_body')) <p class="help-block">{{ $errors->first('do_you_belong_to_a_professional_body') }}</p> @endif
                        </div>

                        <div class="col-md-12" v-if="do_you_belong_to_a_professional_body == 'yes'">
                            <div class="row">
                                <div class="form-group @if ($errors->has('select_professional_body')) has-error @endif">
                                    {!! Form::label('select_professional_body', 'Please select your professional body') !!}
                                    {!! Form::select('select_professional_body', [
                                        'ICBA'      => 'ICBA',
                                        'SAIBA'     => 'SAIBA',
                                        'SAICA'     => 'SAICA',
                                        'SAIPA'     => 'SAIPA',
                                        'ACCA'      => 'ACCA',
                                        'CIMA'      => 'CIMA',
                                        'CIGFARO'   => 'CIGFARO',
                                        'IAC'       => 'IAC',
                                        'IBA'       => 'IBA',
                                        'SAIGA'     => 'SAIGA',
                                        'ICSA'      => 'ICSA',
                                        'ICB'       => 'ICB',
                                        'CSSA'      => 'CSSA',
                                        'IRBA'      => 'IRBA',
                                        'SAIT'      => 'SAIT',
                                        'other'     => 'other (Not one of the above)',
                                    ],null, ['class' => 'form-control', 'v-model' => 'select_professional_body']) !!}
                                    @if ($errors->has('select_professional_body')) <p
                                            class="help-block">{{ $errors->first('select_professional_body') }}</p> @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" v-if="select_professional_body == 'other'">
                            <div class="row">
                                <div class="form-group @if ($errors->has('other_professional_body')) has-error @endif">
                                    {!! Form::label('other_professional_body', 'Please specify your professional body') !!}
                                    {!! Form::input('text', 'other_professional_body', null, ['class' => 'form-control']) !!}
                                    @if ($errors->has('other_professional_body')) <p
                                            class="help-block">{{ $errors->first('other_professional_body') }}</p> @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="margin-bottom-20"></div>

            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82); text-align: left">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('expand_practice_income')) has-error @endif">
                            {!! Form::label('expand_practice_income', 'Do you want to expand your practice income by becoming a financial advisor?') !!}
                            {!! Form::select('expand_practice_income', [
                                'Yes' => 'Yes',
                                'No' => 'No'
                            ],null, ['class' => 'form-control']) !!}
                            @if ($errors->has('expand_practice_income')) <p class="help-block">{{ $errors->first('expand_practice_income') }}</p> @endif
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group @if ($errors->has('reduce_risk_products')) has-error @endif">
                            {!! Form::label('reduce_risk_products', 'Which of the following products do you use to reduce your firms risks and liabilities?') !!}
                            {!! Form::select('reduce_risk_products[]', [
                                'Key-man policy'      => 'Key-man policy',
                                'Wills, Trust Services and Estate Planning'     => 'Wills, Trust Services and Estate Planning',
                                'Asset Protection'     => 'Asset Protection',
                                'Buy-and-Sell Agreement'      => 'Buy-and-Sell Agreement',
                                'Business Debt Insurance'       => 'Business Debt Insurance',
                                'Payment and Debit Order Collections'       => 'Payment and Debit Order Collections',
                                'Retirement annuities'     => 'Retirement annuities',
                                'Income protection'      => 'Income protection',
                                'Short term insurance'       => 'Short term insurance',
                            ],null, ['class' => 'form-control select2', 'multiple' => 'true', 'style' => 'width:100%']) !!}
                            @if ($errors->has('reduce_risk_products')) <p
                                    class="help-block">{{ $errors->first('reduce_risk_products') }}</p> @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="margin-bottom-20"></div>

            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82); text-align: left">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <label for=""><strong>SAAA has the following rewards partners</strong></label>
                    </div>
                    <div class="col-md-12">
                        <div class="margin-bottom-20"></div>
                    </div>
                    <center>
                        <div class="col-md-5th"><a target="_blank" href="{{ route('rewards.saiba') }}"><img src="/assets/frontend/images/sponsors/saiba.jpg" width="80%" class="thumbnail" alt="SAIBA"></a></div>
                        <div class="col-md-5th"><a target="_blank" href="{{ route('rewards.draftworx') }}"><img src="/assets/frontend/images/sponsors/draftworx.jpg" width="80%" class="thumbnail" alt="Draftworx"></a></div>
                        <div class="col-md-5th"><a target="_blank" href="{{ route('rewards.santam') }}"><img src="/assets/frontend/images/sponsors/sanlam.jpg" width="80%" class="thumbnail" alt="Sanlam"></a></div>
                        <div class="col-md-5th"><a target="_blank" href="{{ route('rewards.aon') }}"><img src="/assets/frontend/images/sponsors/aon.jpg" width="80%" class="thumbnail" alt="AON"></a></div>
                        <div class="col-md-5th"><a target="_blank" href="{{ route('rewards.santam') }}"><img src="/assets/frontend/images/sponsors/santam.jpg" width="80%" class="thumbnail" alt="Santam"></a></div>
                    </center>
                    <div class="col-md-12">
                        <div class="margin-bottom-20"></div>
                    </div>
                    <div class="col-md-12 text-center">
                        <label class="checkbox">
                            <input type="checkbox" value="1" name="benefits_of_discounts" checked="checked">
                            <i></i> Would you like one of our consultants to contact you and explain some of these benefits and discounts.
                        </label>
                    </div>
                    
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <hr>
                    <button type="submit" class="btn btn-success btn-block">Submit</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</section>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('#country').change(function () {
                var countryID = $(this).val();
                if (countryID) {
                    $.ajax({
                        type: "GET",
                        cache: false,
                        url: "{{url('api/get-state-list')}}?country_id=" + countryID,
                        error: function (xhr, settings, exception) {
                            alert('The update server could not be contacted.');
                        },
                        success: function (res) {
                            if (res) {
                                $("#state").empty();
                                $("#state").append('<option>Select</option>');
                                $.each(res, function (key, value) {
                                    $("#state").append('<option value="' + key + '">' + value + '</option>');
                                });

                            } else {
                                $("#state").empty();
                            }
                        }
                    });
                } else {
                    $("#state").empty();
                    $("#city").empty();
                }
            });
            $('#state').on('change', function () {
                var stateID = $(this).val();
                if (stateID) {
                    $.ajax({
                        type: "GET",
                        cache: false,
                        url: "{{url('api/get-city-list')}}?state_id=" + stateID,
                        error: function (xhr, settings, exception) {
                            alert('The update server could not be contacted.');
                        },
                        success: function (res) {
                            if (res) {
                                $("#city").empty();
                                $("#city").append('<option>Select</option>');
                                $.each(res, function (key, value) {
                                    $("#city").append('<option value="' + key + '">' + value + '</option>');
                                });

                            } else {
                                $("#city").empty();
                            }
                        }
                    });
                } else {
                    $("#city").empty();
                }

            });
        });
    </script>
@endsection