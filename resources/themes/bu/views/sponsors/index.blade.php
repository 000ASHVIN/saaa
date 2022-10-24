@extends('app')

@section('content')

@section('title')
    Our Sponsors
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('sponsors') !!}
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
        <div class="col-md-8 verticalLine">
            <div style="background-color: white; padding: 10px; border: 1px solid #e3e3e3">
                <div class="row">
                    <div class="col-md-4">
                        <center>
                            <img src="/assets/frontend/images/sponsors/draftworx.jpg" width="80%" class="thumbnail"
                                 alt="Draftworx">
                        </center>
                    </div>
                    <div class="col-md-8">
                        <h4>Draftworx Financials Statements & Working Papers</h4>
                        <p><strong>Discount</strong>: Up to 15% discount for SAAA CPD Subscribers who are new to
                            Draftworx.</p>
                        <p><strong>Steps: </strong></p>
                        <ol>
                            <li>Register as an SAAA CPD Subscriber.</li>
                            <li>Complete the form to the right and we will contact you to explain how you can claim your
                                discount and get Draftworx.
                            </li>
                        </ol>

                        <p>
                            Draftworx helps you complete financial statements for your clients and ensures you are
                            compliant with IFRS, IFRS for SME or Modified Cash Basis of Accounting.
                        </p>

                        <p>
                            You can also use Draftworx to help you conduct an audit, independent review, accounting
                            officer engagements, agreed upon procedures and compilations. Draftworx provides full
                            integration into all Sage Pastel, Quickbooks & Xero accounting products.
                        </p>
                    </div>
                </div>
            </div>
            <div class="margin-bottom-20"></div>
            <div style="background-color: white; padding: 10px; border: 1px solid #e3e3e3">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <center>
                            <img src="/assets/frontend/images/sponsors/sanlam.jpg" width="80%" class="thumbnail"
                                 alt="Sanlam">
                            <img src="/assets/frontend/images/sponsors/santam.jpg" width="80%" class="thumbnail"
                                 alt="Santam">
                        </center>
                    </div>
                    <div class="col-md-8">
                        <h4>Insurance for professionals</h4>
                        <p><strong>Discount</strong>: Insurance based on the quality of your firm</p>
                        <p><strong>Steps: </strong></p>
                        <ol>
                            <li>Register as an SAAA CPD Subscriber.</li>
                            <li>Complete the form to the right and we will contact you to explain how you can reduce your
                                insurance cost by adhering to practice quality control standards.
                            </li>
                        </ol>
                        <p>
                            Sanlam and Santam value the work performed by accountants, and believes that compliance to
                            quality control standards reduces your practice's insurance risk. Why pay more insurance if
                            you run a quality firm?
                        </p>
                    </div>
                </div>
            </div>
            <div class="margin-bottom-20"></div>
            <div style="background-color: white; padding: 10px; border: 1px solid #e3e3e3">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <center>
                            <img src="/assets/frontend/images/sponsors/aon.jpg" width="80%" class="thumbnail" alt="AON">
                        </center>
                    </div>
                    <div class="col-md-8">
                        <h4>First for Accountants Professional Indemnity Programme</h4>
                        <p><strong>Discount</strong>: Qualify for PI Insurance from as little as R399 per month for R2
                            000 000 cover.</p>
                        <p><strong>Steps: </strong></p>
                        <ol>
                            <li>Register as an SAAA CPD Subscriber.</li>
                            <li>Complete the form to the right and we will contact you to explain how you can claim your
                                discount and get affordable professional indemnity insurance.
                            </li>
                        </ol>
                        <p>
                            Aon South Africa, the leading Specialty Insurance Brokers in South Africa, the Hollard
                            Insurance Company has teamed up with SAAA to profice a first for accountants. Insurance that
                            covers all your risk of professional misconduct for less than R400 per year.
                        </p>
                    </div>
                </div>
            </div>
            <div class="margin-bottom-20"></div>
            <div style="background-color: white; padding: 10px; border: 1px solid #e3e3e3">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <center>
                            <img src="/assets/frontend/images/sponsors/saiba.jpg" width="80%" class="thumbnail"
                                 alt="SAIBA">
                        </center>
                    </div>
                    <div class="col-md-8">
                        <h4>SAIBA - Southern African Institute for Business Accountants</h4>
                        <p><strong>Discount</strong>:
                            <br>
                            <strong>Existing members of professional bodies</strong>: SAAA subscribers that are members
                            of professional bodies get exemptions to earn a SAIBA designation and a 50% discount on a
                            designation fee.
                            <br>
                            <br>
                            <strong>Not yet a member of a body</strong>: SAAA subscribers get free SAIBA Associate
                            membership for one year for free. Get to experience the SAIBA benefits at no cost.
                        </p>
                        <p><strong>Steps: </strong></p>
                        <ol>
                            <li>Register as a SAAA subscriber.</li>
                            <li>Complete the form on the left and we will contact you to explain how you can claim your
                                discount.
                            </li>
                        </ol>

                        <p>
                            SAIBA is a SAQA recognised controlling body for accountants with a statutory licence to
                            issue designations for junior accountants, financial managers, CFOs and accountants in
                            practice.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="vertical_line"></div>
        <div class="col-md-4">
            <div style="background-color: white; padding: 10px;">
                <h4>Need more information ?</h4>
                <p>
                    Please complete the form if you want more information. All information treated confidentially and
                    will assist in providing best discount options:
                </p>
                {!! Form::open(['method' => 'post', 'route' => 'sponsors.store']) !!}
                <div class="form-group @if ($errors->has('name')) has-error @endif">
                    {!! Form::label('name', 'Name') !!}
                    {!! Form::input('text', 'name', null, ['class' => 'form-control']) !!}
                    @if ($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
                </div>
                <div class="form-group @if ($errors->has('email')) has-error @endif">
                    {!! Form::label('email', 'Email') !!}
                    {!! Form::input('text', 'email', null, ['class' => 'form-control']) !!}
                    @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                </div>
                <div class="form-group @if ($errors->has('contact_number')) has-error @endif">
                    {!! Form::label('contact_number', 'Contact Number') !!}
                    {!! Form::input('text', 'contact_number', null, ['class' => 'form-control']) !!}
                    @if ($errors->has('contact_number')) <p
                            class="help-block">{{ $errors->first('contact_number') }}</p> @endif
                </div>
                <div class="form-group @if ($errors->has('product')) has-error @endif">
                    {!! Form::label('product', 'Product interested in:') !!}
                    {!! Form::select('product', [
                        'Draftworx' => 'Draftworx',
                        'AON PI' => 'AON PI',
                        'Sanlam' => 'Sanlam',
                        'SAIBA' => 'SAIBA',
                    ],null, ['class' => 'form-control']) !!}
                    @if ($errors->has('product')) <p class="help-block">{{ $errors->first('product') }}</p> @endif
                </div>
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

                <div class="form-group @if ($errors->has('gender')) has-error @endif">
                    {!! Form::label('gender', 'Gender') !!}
                    {!! Form::select('gender', [
                        'Male' => 'Male',
                        'Female' => 'Female',
                    ],null, ['class' => 'form-control']) !!}
                    @if ($errors->has('gender')) <p class="help-block">{{ $errors->first('gender') }}</p> @endif
                </div>
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
                <div class="form-group @if ($errors->has('level_of_management')) has-error @endif">
                    {!! Form::label('level_of_management', 'Level of management ') !!}
                    {!! Form::select('level_of_management', [
                        'Junior' => 'Junior',
                        'Middle' => 'Middle',
                        'Senior' => 'Senior',
                        'Director' => 'Director',
                    ],null, ['class' => 'form-control']) !!}
                    @if ($errors->has('level_of_management')) <p
                            class="help-block">{{ $errors->first('level_of_management') }}</p> @endif
                </div>

                <button onclick="spin(this)" class="btn btn-primary btn-block"><i class="fa fa-send"></i> Submit</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>
@endsection