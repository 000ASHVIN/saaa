@extends('app')


@section('meta_tags')
    <title>One Day Only </title>
    <meta name="description" content="Find great deals and save up to 50% this Monday 26 November. The Tax Faculty is accredited as a Continuous Professional Development (CPD) provider.">
    <meta name="Author" content="{{ config('app.name') }}"/>
@endsection

@section('title', 'Signup for CPD Subscription')

@section('content')
<style>
    section.theme-color.hidden-print {
        display: none !important;
    }
</style>
<section id="slider" class="hidden-sm hidden-xs">
    <center  style="    ">
        <div  data-target="#need_help_subscription_one" data-toggle="modal"  style="background-image: url({{ url('assets/themes/taxfaculty/img/50_discount_web.jpg') }});background-position: center;
        background-repeat: no-repeat;
        background-size: cover; height: 280px; cursor: pointer; position:relative;">
            

        </div>
    </center>
</section>

    <section style="background:url('/assets/frontend/images/demo/wall2.jpg')">
        <div class="container">
            <div class="col-md-12">
                <div class="heading-title heading-dotted text-center">
                    <h3 style="background-color: #173175; color: white">Technical Resource Centre & <span style="color: white">CPD Subscription</span></h3>
                </div>
            </div>
        </div>

        <app-subscriptions-screen  :subscriptions="{{(auth()->user()?auth()->user()->subscription('cpd')->plan:auth()->user())}}"  :plans="{{ $plans }}" :user="{{ (auth()->user() ? auth()->user()->load(['cards', 'subscriptions']) : auth()->user()) }}"   inline-template>
            <div id="app-register-screen" class="container app-screen">
                @if (Route::getFacadeRoot()->current()->uri() == 'subscriptions/2020/BlackFriday' || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2020/one-day-only'  || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2021/One-Day-Only' || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2022/One-Day-Only'  || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2020/One-Day-Only' || Route::getFacadeRoot()->current()->uri() == 'BlackFriday')
                    <input style="display: none" type="checkbox" checked v-model="forms.subscription.bf" value="bf">
                @endif

                {{-- Subscription Plan Selector --}}
                <div class="col-md-12" v-if="plans.length > 1 && plansAreLoaded && ! forms.subscription.plan">
                    @include('subscriptions.partials.plans.selector')
                </div>

                {{-- Plan is Selected --}}
                <div class="col-md-8 col-md-offset-2" v-if="selectedPlan">

                    {{-- Selected Plan --}}
                    @include('subscriptions.partials.plans.selected')

                    {{-- Plan features --}}
                    @include('subscriptions.partials.plans.features')

                    {{-- Billing Options --}}
                    @include('subscriptions.partials.billing_options')

                    {{-- Billing Options Details --}}
                    @include('subscriptions.partials.billing_information')

                    {{-- Interested in PI --}}
                    @include('subscriptions.partials.pi')

                     {{-- Terms and Conditions and Complete Subscription Signup --}}
                     @include('subscriptions.partials.terms')
                    {{-- Terms and Conditions and Complete Subscription Signup --}}
                </div>
            </div>

            <br>
            <br>
        </app-subscriptions-screen>
    </Section>



    @include('subscriptions.2017.include.oneDayhelp')
    @include('includes.login')

    <div class="callout-dark heading-arrow-bottom">
        <a class="btn btn-primary size-10 fullwidth " style="background-color: #adb1b2; border-color: transparent;">
            <span style="font-size: 20px">Subscription Plan and Resource Centre access</span>
        </a>
    </div>

    <section style="padding-top: 30px; padding-bottom: 30px">
        <div class="container">
            <h1 style="text-align:center"><span style="font-size:20px">Discounted CPD subscription and resource centre plans</span></h1>

            <p>These&nbsp;subscription plans gives&nbsp;<strong>SARS registered Tax Practitioner</strong> access to the latest technical content to be successful, including webinars and other training platforms in order to develop their skills as <strong>registered tax professionals</strong>.</p>

            <h2><span style="font-size:14px"><strong><span style="color:#82c341">Our subscription plans includes:</span></strong></span></h2>

            <ul>
                <li>Live Scheduled Webinars for Tax Practitioners</li>
                <li>Webinars&nbsp;on Demand (Catch-up)</li>
                <li>Tax Acts Online</li>
                <li>Access to technical helpdesk</li>
                <li>Access to the <a href="https://taxfaculty.ac.za/resource_centre">Knowledge Centre</a>: Technical content to help you solve problems for your clients and employer anytime, anywhere, On-Demand.</li>
            </ul>

            <p><span style="font-size:14px"><strong><span style="color:#82c341">Below is the different Subscription plans and their prices:</span></strong></span></p>

            <p><strong>Tax Technician:</strong></p>

            <ul>
                <li>Once off (yearly) cost</li>
                <ul>
                    <li>Original price: R2 625 per year</li>
                    <li style="color:red">Discounted price: R1 312.50 per year</li>
                </ul>
            </ul>

            <p><strong>Tax Practitioner:</strong></p>

            <ul>
                <li>Once off (yearly) cost</li>
                <ul>
                    <li>Original price: R3 323 per year</li>
                    <li style="color:red">Discounted price: R1 661.50 per year</li>
                </ul>
            </ul>

            <p><strong>Tax Accountant:</strong></p>

            <ul>
                <li>Once off (yearly) cost</li>
                <ul>
                    <li>Original price: R5655  per year</li>
                    <li style="color:red">Discounted price: R2827 per year</li>
                </ul>
            </ul>

            <p><strong>Build Your Own:</strong></p>

            <ul>
                <li>Once off (yearly) cost</li>
                <ul>
                    <li>Original price: R5655 per year</li>
                    <li style="color:red">Discounted price: R2827 per year</li>
                </ul>
            </ul>


            <!-- <p><span style="font-size:11px"><em>*The abovementioned prices is only valid as of 6 April 2022.</em></span></p> -->

        </div>
    </section>

    <section class="alternate" style="padding-top: 30px; padding-bottom: 30px">
        <div class="container">
            <div class="heading-title heading-dotted">
                <h4><span>What is included in your subscription plan</span></h4>
            </div>

            <h4><span style="color:#82c341"><strong>The Tax Technician&nbsp;subscription plan includes the following live webinars:</strong></span></h4>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                <p style="text-align:left">Live scheduled webinars for tax technicians</p>
                                </th>
                                <th>Month</th>
                                <th>CPD hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2022 Budget and Tax Update</td>
                                <td>March</td>
                                <td>4 hours</td>
                            </tr>
                            <tr>
                                <td>Monthly SARS and Tax Update</td>
                                <td>Jan-Nov</td>
                                <td>2 hours</td>
                            </tr>
                            <tr>
                                <td>TaxCafe Discussion Forum: Resolving Current Personal Taxpayer Issues</td>
                                <td>Bi-monthly</td>
                                <td>1.5 hours</td>
                            </tr>
                            <tr>
                                <td>Compulsory Annual Ethics Training for Tax Practitioners</td>
                                <td>August</td>
                                <td>5 hours</td>
                            </tr>
                            <tr>
                                <td>TaxCafe Discussion Forum: Resolving Current Tax Issues for SMMEs</td>
                                <td>Quarterly</td>
                                <td>4 hours</td>
                            </tr>
                            <tr>
                                <td>Annual Tax Update 2022/2023</td>
                                <td>November</td>
                                <td>4 hours</td>
                            </tr>
                            <tr>
                                <td>eFiling Tax Practitioner Discussion Forum: Resolving Practitioner eFiling Issues</td>
                                <td>Bi-monthly</td>
                                <td>1.5 hours</td>
                            </tr>
                            <tr>
                                <td>Essential Excel Skills for Tax Technicians (Series)</td>
                                <td>February/March</td>
                                <td>2 hours per webinar</td>
                            </tr>
                        </tbody>
                    </table>

                    <h4><span style="color:#82c341"><strong>The Tax Practitioner subscription plan includes the following live webinars:&nbsp;</strong></span></h4>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                <p style="text-align:left">Live Scheduled Webinars</p>
                                </th>
                                <th>Month</th>
                                <th>CPD Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Monthly SARS &amp; Tax Update</td>
                                <td>Jan - Nov</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>2022 Budget and Tax Update</td>
                                <td>March</td>
                                <td>4 Hours</td>
                            </tr>
                            <tr>
                                <td>eFiling Tax Practitioner Discussion Forum: Resolving Practioner eFiling Issues</td>
                                <td>Bi-Monthly</td>
                                <td>1.5 Hours</td>
                            </tr>
                            <tr>
                                <td>TaxCafe Discussion Forum: Resolving Current Personal Taxpayer Issues</td>
                                <td>Bi-Monthly</td>
                                <td>1.5 Hour</td>
                            </tr>
                            <tr>
                                <td>Annual Tax Update 2022/2023</td>
                                <td>November</td>
                                <td>4 Hours</td>
                            </tr>
                            <tr>
                                <td>Compulsory Annual Ethics Training for Tax Practitioners</td>
                                <td>August</td>
                                <td>5 Hours</td>
                            </tr>
                            <tr>
                                <td>Expat Tax Issues Series</td>
                                <td>June</td>
                                <td>2 Hours</td>
                            </tr>
                            <tr>
                                <td>Tax Administration Series:<br>
                                Session 1: Dealing with tax return corrections and voluntary disclosure.&nbsp;<br>
                                Session 2: Effectively apply for remission/reduction of tax penalties and interest.&nbsp;<br>
                                Session 3: Drafting SARS objections and appeals.&nbsp;<br>
                                Session 4: Negotiating tax debt and payment arrangements with SARS.</td>
                                <td>
                                <p>April - July</p>
                                </td>
                                <td>2 Hours per webinar</td>
                            </tr>
                            <tr>
                                <td>TaxCafe Discussion Forum: Resolving Current Tax Issues for SMEs</td>
                                <td>Quarterly</td>
                                <td>1 Hours</td>
                            </tr>
                            <tr>
                                <td>Governance, Accounting and Tax of Trusts (Series)</td>
                                <td>September - November</td>
                                <td>2 Hours per webinar</td>
                            </tr>
                            <tr>
                                <td>Essential Excel skills for Tax Practitioners Series</td>
                                <td>February</td>
                                <td>2 Hours per webinar</td>
                            </tr>
                        </tbody>
                    </table>

                    <h4><span style="color:#82c341"><strong>The Tax Accountant&nbsp;subscription plan includes the following live webinars:&nbsp;</strong></span></h4>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                <p style="text-align:left">Tax topics:</p>
                                </th>
                                <th>Month</th>
                                <th>CPD hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Monthly SARS and Tax Update</td>
                                <td>Jan-Nov</td>
                                <td>2 hours</td>
                            </tr>
                            <tr>
                                <td>2022 Budget and Tax Update</td>
                                <td>March</td>
                                <td>4 hours</td>
                            </tr>
                            <tr>
                                <td>eFiling Tax Practitioner Discussion Forum: Resolving Practitioner eFiling Issues</td>
                                <td>Bi-monthly</td>
                                <td>1.5 hours</td>
                            </tr>
                            <tr>
                                <td>TaxCafe Discussion Forum: Resolving Current Personal Taxpayer Issues</td>
                                <td>Bi-monthly</td>
                                <td>1.5 hours</td>
                            </tr>
                            <tr>
                                <td>Annual Tax Update 2022/2023</td>
                                <td>November</td>
                                <td>4 hours</td>
                            </tr>
                            <tr>
                                <td>Compulsory Annual Ethics Training for Tax Practitioners</td>
                                <td>August</td>
                                <td>5 hours</td>
                            </tr>
                            <tr>
                                <td>Expat Tax Issues Series</td>
                                <td>June</td>
                                <td>2 hours</td>
                            </tr>
                            <tr>
                                <td>Tax Administration Series:<br>
                                Session 1: Dealing with Tax Return Corrections and voluntary Disclosure<br>
                                Session 2: Effectively Apply for Remission/Reduction of Tax Penalties and Interest<br>
                                Session 3: Drafting SARS Objections and Appeals<br>
                                Session 4: Negotiating Tax Debt and Payment Arrangements with SARS</td>
                                <td>
                                <p>April-July</p>
                                </td>
                                <td>2 hours per webinar</td>
                            </tr>
                            <tr>
                                <td>TaxCafe Discussion Forum: Resolving Current Tax Issues for SMMEs</td>
                                <td>Quarterly</td>
                                <td>1 hour</td>
                            </tr>
                            <tr>
                                <td>Governance, Accounting and Tax of Trusts (Series)</td>
                                <td>Sept-Nov</td>
                                <td>2 hours per webinar</td>
                            </tr>
                            <tr>
                                <td>Essential Excel skills for Tax Practitioners Series</td>
                                <td>February/March</td>
                                <td>2 hours per webinar</td>
                            </tr>
                        </tbody>
                    </table>

                    <p>&nbsp;</p>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>
                                <p style="text-align:left">Accounting topics:</p>
                                </th>
                                <th>Month</th>
                                <th>CPD hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Annual Accounting Update</td>
                                <td>November</td>
                                <td>4 hours</td>
                            </tr>
                            <tr>
                                <td>IFRS for SMEs Update 2021/2022</td>
                                <td>June</td>
                                <td>4 hours</td>
                            </tr>
                            <tr>
                                <td>IFRS Update 2021/2022</td>
                                <td>October</td>
                                <td>4 hours</td>
                            </tr>
                            <tr>
                                <td>Compilation Engagements: Draft Companies Act Compliant Annual Financial Statements</td>
                                <td>March</td>
                                <td>2 hours</td>
                            </tr>
                            <tr>
                                <td>Annual Compliance and Regulator Update</td>
                                <td>July</td>
                                <td>2 hours</td>
                            </tr>
                            <tr>
                                <td>Monthly Accountants Tech Talk: Resolving Your Current Issues</td>
                                <td>Monthly</td>
                                <td>1 hour</td>
                            </tr>
                            <tr>
                                <td>Monthly Compliance and Legislation Update</td>
                                <td>Monthly</td>
                                <td>1 hour</td>
                            </tr>
                            <tr>
                                <td>Management Accounting and Performance Reporting</td>
                                <td>May</td>
                                <td>2 hours</td>
                            </tr>
                            <tr>
                                <td>Ethics, Independence and NOCLAR</td>
                                <td>February</td>
                                <td>4 hours</td>
                            </tr>
                            <tr>
                                <td>Performing Independent Reviews</td>
                                <td>September</td>
                                <td>4 hours</td>
                            </tr>
                        </tbody>
                    </table>

                    <p><strong>Important:</strong> These live webinars becomes available as&nbsp;<span style="color:#8cc03c"><strong>on-demand</strong></span>&nbsp;after the scheduled events, on your profile.</p>

                </div>
            </section>

            <section style="padding-top: 30px; padding-bottom: 30px">
                    <div class="container">
                        <div class="heading-title heading-dotted">
                            <h4><span>Technical Resource Centre  </span></h4>
                        </div>

                        <h4><span style="color:#404041"><strong>Your&nbsp;subscription includes 24-hour access to the following technical content and recorded webinar content in the Knowledge Centre:</strong></span></h4>

        <p><span style="color:#8cc03c"><strong>On-Demand Webinars:</strong></span></p>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                    <p style="text-align:left">Topics:</p>
                    </th>
                    <th>CPD Hours</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>SARS Dispute Resolution</td>
                    <td>2 Hours</td>
                </tr>
                <tr>
                    <td>Dealing with a SARS Income Tax Audit</td>
                    <td>4 Hours</td>
                </tr>
                <tr>
                    <td>Dealing with a SARS VAT &amp; Payroll Audit</td>
                    <td>4 Hours</td>
                </tr>
                <tr>
                    <td>Taxation of Crypto Assets</td>
                    <td>2 Hour</td>
                </tr>
                <tr>
                    <td>Sale of business: Important Tax considerations&nbsp;</td>
                    <td>2 Hours</td>
                </tr>
                <tr>
                    <td>Solving Expat Tax issues series</td>
                    <td>2 Hours</td>
                </tr>
                <tr>
                    <td>Administration of Deceases Estates</td>
                    <td>4 Hours</td>
                </tr>
                <tr>
                    <td>Estate Planning</td>
                    <td>4 Hours</td>
                </tr>
                <tr>
                    <td>Trusts Beyond 2021 Considering all the risks and benefits Series</td>
                    <td>2 Hours per webinar</td>
                </tr>
                <tr>
                    <td>SARS Tax Reconciliations</td>
                    <td>2 Hours</td>
                </tr>
                <tr>
                    <td>Wills and intestate succession</td>
                    <td>2 Hours</td>
                </tr>
                <tr>
                    <td>Provisional Tax</td>
                    <td>2 Hours</td>
                </tr>
                <tr>
                    <td>Taxation of farmers</td>
                    <td>2 Hours</td>
                </tr>
                <tr>
                    <td>Leases: Considering the Tax and Accounting Principles</td>
                    <td>2 Hours</td>
                </tr>
                <tr>
                    <td>Dividend Tax</td>
                    <td>2 Hours</td>
                </tr>
                <tr>
                    <td>Voluntary Disclosure</td>
                    <td>2 Hours</td>
                </tr>
                <tr>
                    <td>Carbon Tax</td>
                    <td>2 Hours</td>
                </tr>
            </tbody>
        </table>

        <p><strong>Back-to-Basics: Video on Demand Training:</strong></p>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                    <p style="text-align:left">Topics:</p>
                    </th>
                    <th>CPD Hours</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Principles: Payroll taxes and administration</td>
                    <td>2 Hours</td>
                </tr>
                <tr>
                    <td>Principles: Taxation of Individuals</td>
                    <td>4 Hours</td>
                </tr>
                <tr>
                    <td>Principles: CGT</td>
                    <td>2 Hours</td>
                </tr>
                <tr>
                    <td>Principles: Farming enterprises</td>
                    <td>2 Hour</td>
                </tr>
                <tr>
                    <td>Introduction to eFiling</td>
                    <td>2 Hours</td>
                </tr>
                <tr>
                    <td>Principles: VAT</td>
                    <td>2 Hours</td>
                </tr>
                <tr>
                    <td>Principles: interest-free loans (Section 7C)</td>
                    <td>2 Hours</td>
                </tr>
                <tr>
                    <td>How to reduce SARS penalties &amp; interest</td>
                    <td>2 Hours</td>
                </tr>
            </tbody>
        </table>

        <p>&nbsp;</p>

        <hr>
        <p><span style="color:#82c341"><strong>Who is this subscription plan for?</strong></span></p>

        <p>This subscription plan is suitable for individuals who are <strong>SARS registered tax practitioners</strong>.</p>

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
    <script>
        $(document).ready(function(){
            var max=50;
            $('.price-clean p').each(function(){
                if($(this).height()>max){
                    max=$(this).height();
                }
            })
    
            $('.price-clean p').each(function(){
                $(this).css('min-height',max+'px'); 
            })
            var maxh=50;
             $('.price-clean h5').each(function(){
                if($(this).height()>maxh){
                    maxh=$(this).height();
                }
            })
    
            $('.price-clean h5').each(function(){
                $(this).css('min-height',maxh+'px'); 
            })
        })
    </script>
@endsection