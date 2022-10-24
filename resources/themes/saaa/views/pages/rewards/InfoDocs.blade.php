@extends('app')

@section('content')

@section('title')
    InfoDocs
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
                    <div class="row">
                        <div class="col-md-12">
                            <h4>InfoDocs Sponsor </h4>
                           <div class="moz-forward-container">
                                <div class="WordSection1">
                                <p><b>InfoDocs Company Secretarial Software</b><br />
                                The easiest way to manage your company records. Simple, easy, online.</p>

                                <p>&nbsp;</p>

                                <p>For business owners and professionals who manage company secretarial requirements. InfoDocs is the complete cloud-based solution that reminds you when filings are due, guides you through share transactions and stores all your company records in one place.</p>

                                <p>&nbsp;</p>

                                <p>InfoDocs helps you automate the following company secretarial requirements:</p>

                                <ul type="disc">
                                    <li><b>CIPC integration</b>: Search for companies by name, registration number or director details to import registered information directly from CIPC for all your company secretarial needs. Provides accurate, verifiable and historical company data.</li>
                                    <li><b>Annual returns</b>: Check for outstanding annual returns and receive email reminders when annual returns become due. You can also file annual returns directly from the company dashboard with our radically simplified FAS.</li>
                                    <li><b>Statutory registers</b>: Select from&nbsp;our comprehensive list of company registers to produce accurate and professional documents at the click of a button. Includes registers of directors, members, shareholders, share capital and more.</li>
                                    <li><b>Template library</b>: Appointments, resignations, allotments, transfers, consent letters, BBBEE affidavits, share certificates and more. Our comprehensive template library provides for all types of company secretarial transactions.</li>
                                </ul>

                                <p><b>Sign up for a free trial</b></p>

                                <p>InfoDocs is incredibly simple and easy to use. There is no training required and you can get started in minutes. Simply login to your SA Accounting Academy profile and claim this reward.</p>

                                <p>&nbsp;</p>

                                <p><b>Upgrade for a 10% discount</b></p>

                                <p>In partnership with the SA Accounting Academy, InfoDocs is offering members a 10% discount when they upgrade to subscription. Once you claim this reward a promo code will be sent to you.</p>

                                <p>&nbsp;</p>

                                <p><b>Testimonials</b></p>

                                <p>&ldquo;Secretarial duties a breeze!&rdquo; - Andre P., Financial Services</p>

                                <p>&ldquo;Great Solution for Company Secretarial in the Cloud!&rdquo; - Peter M., Accounting</p>

                                <p>&ldquo;Superbly Smooth and Easy&rdquo; - Zelda Patricia H., Venture Capital &amp; Private Equity<br />
                                &ldquo;InfoDocs is the Xero of Secretarial&rdquo; - Christie H., Financial Services</p>
                                </div>
                            </div>

                            <p>&nbsp;</p>
                            <p><b>Screenshots</b></p>
                            <p>&nbsp;</p>
                            <img src="/assets/frontend/images/sponsors/215661.png" height="50%" width="100%" class="thumbnail" alt="InfoDocs">
                            <img src="/assets/frontend/images/sponsors/215662.png" height="50%" width="100%" class="thumbnail" alt="InfoDocs">
                            <img src="/assets/frontend/images/sponsors/215663.png" height="50%" width="100%" class="thumbnail" alt="InfoDocs">
                            <img src="/assets/frontend/images/sponsors/215664.png" height="50%" width="100%" class="thumbnail" alt="InfoDocs">
                            <img src="/assets/frontend/images/sponsors/215665.png" height="50%" width="100%" class="thumbnail" alt="InfoDocs">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <img src="/assets/frontend/images/sponsors/infodocs-partner-logo-square.png" width="100%" class="thumbnail" alt="InfoDocs">
                <h4>Need more information ?</h4>
                {!! Form::open(['method' => 'post', 'route' => ['rewards.store', 'saiba']]) !!}
                @include('questionnaire.includes.form', ['product' => ['product' => $product]])
                {!! Form::close() !!}
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
