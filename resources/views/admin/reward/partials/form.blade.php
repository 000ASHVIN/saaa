
<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('name')) has-error @endif">
            {!! Form::label('name', 'Name') !!}
            {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
            @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
        </div>
    </div>
    <div class="col-md-6">
          <div class="form-group @if ($errors->has('email')) has-error @endif">
                {!! Form::label('email', 'Email') !!}
                {!! Form::input('text', 'email', null, ['class' => 'form-control']) !!}
                @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
           </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">

<div class="form-group @if ($errors->has('contact_number')) has-error @endif">
    {!! Form::label('contact_number', 'Contact Number') !!}
    {!! Form::input('text', 'contact_number', null, ['class' => 'form-control']) !!}
    @if ($errors->has('contact_number')) <p class="help-block">{{ $errors->first('contact_number') }}</p> @endif
</div>
</div>
<div class="col-md-6">

    <div class="form-group @if ($errors->has('date_of_birth')) has-error @endif">
        {!! Form::label('date_of_birth', 'Date of Birth') !!}
        {!! Form::input('date', 'date_of_birth', Carbon\Carbon::parse($reward->date_of_birth)->format('Y-m-d'), ['class' => 'form-control']) !!}
        @if ($errors->has('date_of_birth')) <p
                class="help-block">{{ $errors->first('date_of_birth') }}</p> @endif
    </div>
    </div>

</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('product')) has-error @endif">
            {!! Form::label('product', 'Product interested in:') !!}
            {!! Form::select('product', $product, null, ['class' => 'form-control']) !!}
            @if ($errors->has('product')) <p class="help-block">{{ $errors->first('product') }}</p> @endif
        </div>
        </div>
    <div class="col-md-6">

<div class="form-group @if ($errors->has('age')) has-error @endif">
    {!! Form::label('age', 'Age') !!}
    {!! Form::select('age', [
        '20 - 30' => '20 - 30',
        '31 - 40' => '31 - 40',
        '41 - 50' => '41 - 50',
        '51 - 60' => '51 - 60',
        '60 - older' => '60 - older',
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('age')) <p class="help-block">{{ $errors->first('age') }}</p> @endif
</div>
</div>

</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('accountant_type')) has-error @endif">
            {!! Form::label('accountant_type', 'Type of accountant:') !!}
            {!! Form::select('accountant_type', [
                'In practice ' => 'In practice ',
                'In commerce' => 'In commerce',
                'Both' => 'Both',
                'Other' => 'Other'
            ],null, ['class' => 'form-control']) !!}
            @if ($errors->has('accountant_type')) <p
                    class="help-block">{{ $errors->first('accountant_type') }}</p> @endif
        </div>
        </div>
  
<div class="col-md-6">
<div class="form-group @if ($errors->has('gender')) has-error @endif">
    {!! Form::label('gender', 'Gender') !!}
    {!! Form::select('gender', [
        'Male' => 'Male',
        'Female' => 'Female',
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('gender')) <p class="help-block">{{ $errors->first('gender') }}</p> @endif
</div>
</div>
</div>

<div class="row">
    <div class="col-md-6">

<div class="form-group @if ($errors->has('race')) has-error @endif">
    {!! Form::label('race', 'Race') !!}
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
<div class="form-group @if ($errors->has('level_of_management')) has-error @endif">
    {!! Form::label('level_of_management', 'Level of management ') !!}
    {!! Form::select('level_of_management', [
        'Junior' => 'Junior',
        'Middle' => 'Middle',
        'Senior' => 'Senior',
        'Director' => 'Director',
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('level_of_management')) <p class="help-block">{{ $errors->first('level_of_management') }}</p> @endif
</div>
</div>
</div>


<div class="row">
    <div class="col-md-6">

<div class="form-group @if ($errors->has('registered_professional_accountancy_body')) has-error @endif">
    {!! Form::label('registered_professional_accountancy_body', 'Are you a member of a registered professional accountancy body?') !!}
    {!! Form::select('registered_professional_accountancy_body', [
        true => 'Yes',
        false => 'No',
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('registered_professional_accountancy_body')) <p class="help-block">{{ $errors->first('registered_professional_accountancy_body') }}</p> @endif
</div>
</div>
<div class="col-md-6">
<div class="form-group @if ($errors->has('professional_body_name')) has-error @endif">
    {!! Form::label('professional_body_name', 'Select name of professional body') !!}
    {!! Form::select('professional_body_name', [
        null => 'Please Select',
        'AAT' => 'AAT',
        'CIMA' => 'CIMA',
        'IAC' => 'IAC',
        'IIA' => 'IIA',
        'SAICA' => 'SAICA',
        'ACCA' => 'ACCA',
        'CIS' => 'CIS',
        'ICBA' => 'ICBA',
        'SAIBA' => 'SAIBA',
        'SAIPA' => 'SAIPA',
        'OTHER' => 'OTHER',
    ],null, ['class' => 'form-control', 'v-model' => 'professional_body']) !!}
    @if ($errors->has('professional_body_name')) <p class="help-block">{{ $errors->first('professional_body_name') }}</p> @endif
</div>
</div>
</div>


<div class="row">
    <div class="col-md-6">

<div v-if="professional_body == 'OTHER'">
    <div class="form-group @if ($errors->has('other_professional_body_name')) has-error @endif">
        {!! Form::label('other_professional_body_name', 'Other (Please Specify)') !!}
        {!! Form::input('text', 'other_professional_body_name', null, ['class' => 'form-control']) !!}
        @if ($errors->has('other_professional_body_name')) <p class="help-block">{{ $errors->first('other_professional_body_name') }}</p> @endif
    </div>
</div>
</div>
<div class="col-md-6">
<div class="form-group @if ($errors->has('do_you_adhere_to_a_code_of_conduct')) has-error @endif">
    {!! Form::label('do_you_adhere_to_a_code_of_conduct', 'Do you adhere to a Code of Conduct that is equal or similar to the IFAC Code of Conduct?') !!}
    {!! Form::select('do_you_adhere_to_a_code_of_conduct', [
        true => 'Yes',
        false => 'No'
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('do_you_adhere_to_a_code_of_conduct')) <p class="help-block">{{ $errors->first('do_you_adhere_to_a_code_of_conduct') }}</p> @endif
</div>
</div>
</div>


<div class="row">
    <div class="col-md-6">

<div class="form-group @if ($errors->has('are_your_cpd_hours_up_to_date')) has-error @endif">
    {!! Form::label('are_your_cpd_hours_up_to_date', 'Are your CPD hours up to date as required by your professional body?') !!}
    {!! Form::select('are_your_cpd_hours_up_to_date', [
        true => 'Yes',
        false => 'No'
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('are_your_cpd_hours_up_to_date')) <p class="help-block">{{ $errors->first('are_your_cpd_hours_up_to_date') }}</p> @endif
</div>
</div>
<div class="col-md-6">
<div class="form-group @if ($errors->has('do_you_use_engagement_letters')) has-error @endif">
    {!! Form::label('do_you_use_engagement_letters', 'Do you use engagement letters for all clients?') !!}
    {!! Form::select('do_you_use_engagement_letters', [
        true => 'Yes',
        false => 'No'
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('do_you_use_engagement_letters')) <p class="help-block">{{ $errors->first('do_you_use_engagement_letters') }}</p> @endif
</div>
</div>
</div>


<div class="row">
    <div class="col-md-6">

<div class="form-group @if ($errors->has('latest_technical_knowledge_or_library')) has-error @endif">
    {!! Form::label('latest_technical_knowledge_or_library', 'Do you have access to the latest technical knowledge or library?') !!}
    {!! Form::select('latest_technical_knowledge_or_library',[
        true => 'Yes',
        false => 'No'
    ], null, ['class' => 'form-control']) !!}
    @if ($errors->has('latest_technical_knowledge_or_library')) <p class="help-block">{{ $errors->first('latest_technical_knowledge_or_library') }}</p> @endif
</div>
</div>
<div class="col-md-6">
<div class="form-group @if ($errors->has('do_you_have_the_required_infrastructure')) has-error @endif">
    {!! Form::label('do_you_have_the_required_infrastructure', 'Do you have the required infrastructure and resources to perform professional work for clients?') !!}
    {!! Form::select('do_you_have_the_required_infrastructure', [
        true => 'Yes',
        false => 'No'
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('do_you_have_the_required_infrastructure')) <p class="help-block">{{ $errors->first('do_you_have_the_required_infrastructure') }}</p> @endif
</div>
</div>
</div>


<div class="row">
    <div class="col-md-6">

<div class="form-group @if ($errors->has('do_you_or_your_firm_perform_reviews_of_all_work')) has-error @endif">
    {!! Form::label('do_you_or_your_firm_perform_reviews_of_all_work', 'Do you or your firm perform reviews of all work performed by your professional support staff?') !!}
    {!! Form::select('do_you_or_your_firm_perform_reviews_of_all_work',[
        true => 'Yes',
        false => 'No'
    ] ,null, ['class' => 'form-control']) !!}
    @if ($errors->has('do_you_or_your_firm_perform_reviews_of_all_work')) <p class="help-block">{{ $errors->first('do_you_or_your_firm_perform_reviews_of_all_work') }}</p> @endif
</div>
</div>
<div class="col-md-6">
<div class="form-group @if ($errors->has('do_you_apply_relevant_auditing_and_assurance_standards')) has-error @endif">
    {!! Form::label('do_you_apply_relevant_auditing_and_assurance_standards', 'Do you apply relevant auditing and assurance standards when issuing reports on financial statements for clients?') !!}
    {!! Form::select('do_you_apply_relevant_auditing_and_assurance_standards', [
        true => 'Yes',
        false => 'No'
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('do_you_apply_relevant_auditing_and_assurance_standards')) <p class="help-block">{{ $errors->first('do_you_apply_relevant_auditing_and_assurance_standards') }}</p> @endif
</div>
</div>
</div>


<div class="row">
    <div class="col-md-6">

<div class="form-group @if ($errors->has('do_you_use_the_latest_technology_and_software')) has-error @endif">
    {!! Form::label('do_you_use_the_latest_technology_and_software', 'Do you use the latest technology and software to manage your practice and perform professional work?') !!}
    {!! Form::select('do_you_use_the_latest_technology_and_software', [
        true => 'Yes',
        false => 'No'
    ],null, ['class' => 'form-control']) !!}
    @if ($errors->has('do_you_use_the_latest_technology_and_software')) <p
            class="help-block">{{ $errors->first('do_you_use_the_latest_technology_and_software') }}</p> @endif
</div>
</div>
<div class="col-md-6">

    <div class="form-group @if ($errors->has('adviser_to_contact_me')) has-error @endif">
        {!! Form::label('adviser_to_contact_me', 'I’d like an Old Mutual Adviser to contact me?') !!}
        {!! Form::select('adviser_to_contact_me', [
            true => 'Yes',
            false => 'No'
        ],null, ['class' => 'form-control']) !!}
        @if ($errors->has('adviser_to_contact_me')) <p
                class="help-block">{{ $errors->first('adviser_to_contact_me') }}</p> @endif
    </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">

        <div class="form-group @if ($errors->has('being_a_referral_agent')) has-error @endif">
            {!! Form::label('being_a_referral_agent', 'I’d like to hear more about being a referral agent?') !!}
            {!! Form::select('being_a_referral_agent', [
                true => 'Yes',
                false => 'No'
            ],null, ['class' => 'form-control']) !!}
            @if ($errors->has('being_a_referral_agent')) <p
                    class="help-block">{{ $errors->first('being_a_referral_agent') }}</p> @endif
        </div>
        </div>
        <div class="col-md-6">

            <div class="form-group @if ($errors->has('income')) has-error @endif">
                {!! Form::label('income', 'Income (R)') !!}
                {!! Form::select('income', [
                    '0 - 300 000 pa' => '0 - 300 000 pa',
                    '300 001 - 500 000 pa' => '300 001 - 500 000 pa',
                    '500 001 - 700 000 pa' => '500 001 - 700 000 pa',
                    '700 001 - 900 000 pa' => '700 001 - 900 000 pa',
                    '900 001 - 1 500 000 pa' => '900 001 - 1 500 000 pa',
                    '1 500 000 - 2 500 000 pa' => '1 500 000 - 2 500 000 pa',
                    'More than 2 500 000 pa' => 'More than 2 500 000 pa',
                ],null, ['class' => 'form-control']) !!}
                @if ($errors->has('income')) <p class="help-block">{{ $errors->first('income') }}</p> @endif
            </div>
            </div>    
</div>


<button class="btn btn-primary" onclick="spin(this)"><i class="fa fa-check"></i> Submit Form</button>

