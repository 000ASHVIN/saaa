@extends('app')

@section('content')

@section('title')
    Draftworx Financials Statements & Working Papers
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('rewards') !!}
@stop

@section('styles')
    <style>
        .verticalLine {
            border-right: thick solid #e3e3e3;
        }
    </style>
@endsection

<section class="alternate">
    <div class="container">
        <div class="row">
            <div class="col-md-8 verticalLine">
                <div style="background-color: white; padding: 10px; border: 1px solid #e3e3e3">
                    <h4>Automate your Financial Statements and Working Papers.</h4>
                    <p>More than 4 000 accounting firms can’t be wrong. DRAFTWORX is flexible, affordable, easy to use and easy to learn.</p>
                    <p><strong>DRAFTWORX helps you complete financial statements for your clients and ensures you are compliant with:</strong></p>
                    <ul>
                        <li>IFRS for SME</li>
                        <li>IFRS</li>
                        <li>Modified cash basis of accounting</li>
                        <li>XBRL reporting to CIPC</li>
                    </ul>
                    <p><strong>You can also use DRAFTWORX to help you conduct an:</strong></p>
                    <ul>
                        <li>Audit</li>
                        <li>Independent review</li>
                        <li>Accounting officer engagements</li>
                        <li>Agreed upon procedures</li>
                        <li>Compilations</li>
                    </ul>
                    <p>DRAFTWORX provides full integration into all Sage Pastel, Quickbooks and Xero accounting products. </p>
                    <p><strong>Cost</strong></p>
                    <ul>
                        <li>Standard licence – <strong>R5 650</strong> annual fee.</li>
                        <li>Exclusive offer to SAAA members – <strong>R4 803</strong> for first annual licence fee.</li>
                        <li>XBRL tagging application for Excel financial statements – <strong>R1 250</strong>.</li>
                    </ul>
                    <p><strong>Steps to get your first DRAFTWORX licence at 15% discount.</strong></p>
                    <ul>
                        <li>Login to your profile on accountingacademy.co.za.</li>
                        <li>Click the Rewards tab and choose DRAFTWORX.</li>
                        <li>Have a look at the demo videos below.</li>
                        <li>Complete the form on the right.</li>
                        <li>You will receive a quote from DRAFTWORX.</li>
                    </ul>
                    <p><strong>Testimonial</strong></p>
                    <i>The program is just the best. Where it took me a day to do 2 financials on (censored product), I can now do 4 in a day and so easy to modify if the clients want to change something. Briliant! – Johan van Gass, VG Accountants</i>

                    <br>
                    <p><strong>EMAIL: </strong> admin@auxilla.co.za</p>
                    <p><strong>PHONE: </strong> Ronell 083 286 7350</p>
                    <p>DRAFTWORX offer only via SAAA and administered by <img src="/assets/frontend/images/auxilla.png" width="20%" alt="Logo">.</p>
                </div>
                <hr>
                <div class="col-md-6">
                    <iframe style="min-height: 271px!important;" width="0" height="0" src="https://www.youtube.com/embed/A3GNSWEqTwY" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    <hr>
                    <iframe style="min-height: 271px!important;" width="0" height="0" src="https://www.youtube.com/embed/kVmUJdACbJc" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    <hr>
                    <iframe style="min-height: 271px!important;" width="0" height="0" src="https://www.youtube.com/embed/pcSM98U37gg" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
                <div class="col-md-6">
                    <iframe style="min-height: 271px!important;" width="0" height="0" src="https://www.youtube.com/embed/fkIxyOHpgS4" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    <hr>
                    <iframe style="min-height: 271px!important;" width="0" height="0" src="https://www.youtube.com/embed/z03AX_ZLqJE" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                </div>
            </div>

            <div class="col-md-4">
                <img src="/assets/frontend/images/sponsors/draftworx.jpg" width="100%" class="thumbnail" alt="Draftworx">
                @if(auth()->guest())
                    <div class="alert alert-info text-center">
                        <p><strong><i class="fa fa-lock"></i> You are not logged in..</strong></p>
                        <p>In order to claim this reward, you must be logged into your account.</p>
                        <hr>
                        <div class="form-group">
                            <a class="btn btn-primary"><i class="fa fa-lock"></i> Login</a>
                            <a class="btn btn-default"><i class="fa fa-unlock"></i> Signup</a>
                        </div>
                    </div>
                @else

                {!! Form::open(['method' => 'post', 'route' => 'rewards.draftworx_store']) !!}

                    <label class="radio">
                        <input type="radio" checked value="1" name="quote">
                        <i></i> Yes, I am interested in DRAFTWORX. Please send me a quote now.
                    </label>

                    <label class="radio">
                        <input type="radio" value="0" name="quote">
                        <i></i> No, I don't need DRAFTWORX right now, but keep me informed.
                    </label>

                    <div class="form-group @if ($errors->has('email')) has-error @endif">
                        {!! Form::label('email', 'Email Address') !!}
                        {!! Form::input('text', 'email', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('company_trading_name')) has-error @endif">
                        {!! Form::label('company_trading_name', 'Company Trading Name') !!}
                        {!! Form::input('text', 'company_trading_name', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('company_trading_name')) <p class="help-block">{{ $errors->first('company_trading_name') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('physical_business_address')) has-error @endif">
                        {!! Form::label('physical_business_address', 'Physical address of Business') !!}
                        {!! Form::input('text', 'physical_business_address', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('physical_business_address')) <p class="help-block">{{ $errors->first('physical_business_address') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('vat_number')) has-error @endif">
                        {!! Form::label('vat_number', 'VAT number (if registered)') !!}
                        {!! Form::input('text', 'vat_number', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('vat_number')) <p class="help-block">{{ $errors->first('vat_number') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                        {!! Form::label('first_name', 'Contact person - first name') !!}
                        {!! Form::input('text', 'first_name', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('surname')) has-error @endif">
                        {!! Form::label('surname', 'Contact person - surname') !!}
                        {!! Form::input('text', 'surname', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('surname')) <p class="help-block">{{ $errors->first('surname') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('contact_number')) has-error @endif">
                        {!! Form::label('contact_number', 'Contact Number') !!}
                        {!! Form::input('text', 'contact_number', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('contact_number')) <p class="help-block">{{ $errors->first('contact_number') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('id_or_passport')) has-error @endif">
                        {!! Form::label('id_or_passport', 'ID or Passport number') !!}
                        {!! Form::input('text', 'id_or_passport', null, ['class' => 'form-control']) !!}
                        @if ($errors->has('id_or_passport')) <p class="help-block">{{ $errors->first('id_or_passport') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('type_of_subscription')) has-error @endif">
                        {!! Form::label('type_of_subscription', 'Type of SAAA subscription') !!}
                        {!! Form::select('type_of_subscription', [
                            'Registered user without CPD subscription' => 'Registered user without CPD subscription',
                            'Monthly CPD subscription' => 'Monthly CPD subscription',
                            'Yearly CPD subscription' => 'Yearly CPD subscription',
                            'Not a subscriber with SAAA' => 'Not a subscriber with SAAA',
                        ],null, ['class' => 'form-control']) !!}
                        @if ($errors->has('type_of_subscription')) <p class="help-block">{{ $errors->first('type_of_subscription') }}</p> @endif
                    </div>
                    <div class="form-group @if ($errors->has('professional_body')) has-error @endif">
                        {!! Form::label('professional_body', 'Member of which professional body?') !!}
                        {!! Form::select('professional_body', [
                            'SAIBA' => 'SAIBA',
                            'SAICA' => 'SAICA',
                            'SAIPA' => 'SAIPA',
                            'SAIT' => 'SAIT',
                            'CIMA' => 'CIMA',
                            'ICSA' => 'ICSA',
                            'IIA' => 'IIA',
                            'AAT' => 'AAT',
                            'SAIGA' => 'SAIGA',
                            'CIGFARO' => 'CIGFARO',
                            'ICBA' => 'ICBA',
                            'ACCA' => 'ACCA',
                            'IAC' => 'IAC',
                            'IBA' => 'IBA',
                            'Other' => 'Other',
                        ],null, ['class' => 'form-control']) !!}
                        @if ($errors->has('professional_body')) <p class="help-block">{{ $errors->first('professional_body') }}</p> @endif
                    </div>

                    <div class="form-group @if ($errors->has('number_of_licenses')) has-error @endif">
                        {!! Form::label('number_of_licenses', 'Number of user licences required') !!}
                        {!! Form::input('text', 'number_of_licenses', null, ['class' => 'form-control', 'placeholder' => 'Enter a number between 1 - 10']) !!}
                        @if ($errors->has('number_of_licenses')) <p class="help-block">{{ $errors->first('number_of_licenses') }}</p> @endif
                    </div>

                    <div class="form-group @if ($errors->has('applies_to_you')) has-error @endif">
                        {!! Form::label('applies_to_you', 'Which applies to you?') !!}
                        {!! Form::select('applies_to_you', [
                            "I'm a first-time DRAFTWORX user" => "I'm a first-time DRAFTWORX user",
                            "I'm already using DRAFTWORX - need to add more user licences" => "I'm already using DRAFTWORX - need to add more user licences",
                            "I'm already using DRAFTWORX - need to renew my user licence." => "I'm already using DRAFTWORX - need to renew my user licence.",
                        ],null, ['class' => 'form-control']) !!}
                        @if ($errors->has('applies_to_you')) <p class="help-block">{{ $errors->first('applies_to_you') }}</p> @endif
                    </div>

                    <div class="form-group @if ($errors->has('type_of_business')) has-error @endif">
                        {!! Form::label('type_of_business', 'Your type of business') !!}
                        {!! Form::select('type_of_business', [
                            'Audit/accounting firm - external use for clients' => 'Audit/accounting firm - external use for clients',
                            'Corporate company - internal use' => 'Corporate company - internal use',
                        ],null, ['class' => 'form-control']) !!}
                        @if ($errors->has('type_of_business')) <p class="help-block">{{ $errors->first('type_of_business') }}</p> @endif
                    </div>

                    <button class="btn btn-primary" onclick="spin(this)"><i class="fa fa-check"></i> Submit Form</button>

                    {!! Form::close() !!}

                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
    <script>
        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
    </script>
@endsection