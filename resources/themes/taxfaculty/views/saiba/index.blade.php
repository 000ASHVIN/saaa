@extends('app')

@section('content')

@section('title')
    SAIBA CPD Subscription 2018
@stop

@section('intro')
    {{ config('app.name') }} fantastic Presenters
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('saiba') !!}
@stop

<section style="background-image: url('/assets/frontend/images/demo/wall2.jpg'); padding-bottom: 10px; padding-top: 30px">

    <div class="container text-center">
        <img width="150px" src="https://imagizer.imageshack.com/v2/320x240q90/922/dtlF8u.png" alt="Logo">
        <hr>
        <h4><span>Business Accountants - Tailor-made CPD Subscription Packages</span> <br> <span style="font-size: 14px">Technical Resource Centre & CPD Subscription</span></h4>
        <hr>
    </div>

    <app-subscriptions-screen :subscriptions="{{(auth()->user()?auth()->user()->subscription('cpd')->plan:auth()->user())}}" :plans="{{ $profession->plans }}" :profession="{{ $profession }}"
                              :user="{{ auth()->user() }}" inline-template>
        <div id="app-register-screen" class="container app-screen">

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
            </div>
        </div>

        <br>
        <br>

    </app-subscriptions-screen>

    <div class="container text-center">
        <h4><span>{{ config('app.name') }} is a SAIBA Approved CPD Provider </span></h4>
        <br>
    </div>
</section>

<div class="callout-dark heading-arrow-bottom">
    <a class="btn btn-primary size-10 fullwidth " style="background-color: #adb1b2; border-color: transparent;">
        <span style="font-size: 20px">CPD Subscription and Technical Resource Centre Features</span>
    </a>
</div>

<section style="padding-top: 30px; padding-bottom: 30px">
    <div class="container">
        <p><strong>Join more than 9000 Accountants, Auditors and Tax Practitioners using our CPD and technical Resource Centre</strong></p>

        <ol>
            <li><strong>Engage your clients with confidence</strong> &nbsp;- &nbsp;Quickly and easily get the answers to SARS and client queries. Save +-28 hours of research time on Google/Websites.</li>
            <li><strong>Core and elective CPD topics</strong> &nbsp; - &nbsp; do only the CPD you need or elect to receive all our topics.</li>
            <li><strong>Tax and Accounting Topics</strong> &nbsp; - &nbsp; If you upgrade to the Plus package you get all our Accounting and Tax topics.&nbsp;</li>
            <li><strong>Watch webinars at your convenience</strong> &nbsp; - &nbsp; Stored in you online profile, watch webinars anytime, anywhere, and on catch-up.</li>
            <li><strong>CPD Tracker / Logbook</strong> &nbsp; - &nbsp; We keep track of all your CPD hours so you don’t have to.</li>
            <li><strong>Printable CPD Certificates</strong> &nbsp; - &nbsp; That are compliant with your professional body requirements.&nbsp;</li>
            <li><strong>Searchable Technical Resource Centre</strong> &nbsp;- &nbsp;Search articles, webinars, and course notes to find your answer.</li>
            <li><strong>Access to experts</strong> &nbsp;- &nbsp; Gain access to our presenters and ask them a technical query.&nbsp;</li>
            <li><strong>Reference guides</strong> &nbsp;- &nbsp;Our course material are drafted in a format that allows for ease of reference and self study.&nbsp;</li>
            <li><strong>Interactive sessions</strong> &nbsp;- &nbsp;Our webinars are supported by live interactive sessions with the presenter.&nbsp;</li>
            <li><strong>Online Q&amp;A </strong>&nbsp;- &nbsp;Use the online forum to gain access to the views of your peers.&nbsp;</li>
            <li><strong>Practical examples</strong> &nbsp;- &nbsp; Our presenters are instructed to include practical example of real life cases.&nbsp;</li>
            <li><strong>Affordable &nbsp;</strong>- &nbsp;The monthly CPD subscription fee makes CPD affordable and easy to pay via debit order.&nbsp;</li>
            <li><strong>Accredited &nbsp;</strong>- &nbsp;All our events are accredited by SAIBA and SAIT and recognised in terms of IES7 by all professional bodies.&nbsp;</li>
        </ol>

    </div>
</section>


<section class="alternate" style="padding-top: 30px; padding-bottom: 30px">
    <div class="container">
        <div class="heading-title heading-dotted">
            <h4><span>CPD Subscription content</span></h4>
        </div>

        <p>1)</p>

        <table class="table table-striped">
            <thead>
            <tr>
                <th>
                    <p>Core Events</p>
                </th>
                <th>Month</th>
                <th>Provider</th>
                <th>CPD Hours</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Ethics</td>
                <td>February</td>
                <td>SAAA</td>
                <td>* 5 Hours</td>
            </tr>
            <tr>
                <td>Reporting engagements</td>
                <td>April</td>
                <td>SAAA</td>
                <td>* 5 Hours</td>
            </tr>
            <tr>
                <td>IFRS SME update</td>
                <td>June</td>
                <td>SAAA</td>
                <td>* 5 Hours</td>
            </tr>
            <tr>
                <td>Companies Act</td>
                <td>August</td>
                <td>SAAA</td>
                <td>* 5 Hours</td>
            </tr>
            <tr>
                <td>Annual Accounting update</td>
                <td>November</td>
                <td>SAAA</td>
                <td>* 5 Hours</td>
            </tr>
            </tbody>
        </table>

        <p>* Get an additional hour for passing an assessment on the topic.</p>

        <hr>
        <p>2)</p>

        <p><strong>Elective accounting topics - Covers other ad hoc events and trending topics such as:</strong></p>

        <ul>
            <li>Ethics, Independence and NOCLAR,</li>
            <li>SARS access to working papers,</li>
            <li>CIPC and XBRL, &nbsp;</li>
            <li>Accounting for Trust Deceased Estates,&nbsp;</li>
            <li>SARS and IRBA CIPC Compliance,&nbsp;</li>
            <li>IFRS Update,&nbsp;</li>
            <li>Bookkeeping,&nbsp;</li>
            <li>Management Accounts,&nbsp;</li>
            <li>Corporate Governance,&nbsp;</li>
            <li>Financial Statements,&nbsp;</li>
            <li>Business Rescue,&nbsp;</li>
            <li>Independent Review,&nbsp;</li>
            <li>Immigration Accounting,&nbsp;</li>
            <li>Audit And Assurance</li>
        </ul>

        <p>3)</p>

        <p><strong>*These topics are included in the CPD subscription package (excluding MCLU) and are offered at no extra cost to subscribers.&nbsp;</strong></p>

        <ul>
            <li>Optional Tax Topics (Included in the Tax And Accounting Package)</li>
            <li>Monthly Tax Update,&nbsp;</li>
            <li>Budget and Tax Update,&nbsp;</li>
            <li>VAT Refresher,&nbsp;</li>
            <li>ITR12: Tax Issues for Individuals,&nbsp;</li>
            <li>Reducing SARS penalties &amp; interests,&nbsp;</li>
            <li>Effective handling of SARS queries &amp; audits and dispute resolution,&nbsp;</li>
            <li>Trusts,&nbsp;</li>
            <li>Capital Gains Tax,&nbsp;</li>
            <li>Provisional Tax &amp; Penalties,&nbsp;</li>
            <li>Tax Issues for SMME,&nbsp;</li>
            <li>Farming,&nbsp;</li>
            <li>Managing Tax Risks,&nbsp;</li>
            <li>Annual Tax Update&nbsp;</li>
        </ul>

        <p>4)</p>

        <p><strong>Monthly Legislation Update</strong></p>

        <p>This is a monthly 2-hour webinar presented by Lettie Janse van Vuuren CA(SA), SAICA and IRBA compliance expert. The webinar covers all the latest changes and updates at SARS, CIPC, accounting, auditing, tax, Labour and other laws applicable to business.</p>

    </div>
</section>


<section style="padding-top: 30px; padding-bottom: 30px">
    <div class="container">
        <div class="heading-title heading-dotted">
            <h4><span>Technical Resource Centre  </span></h4>
        </div>

        <table class="table table-striped">
            <tbody>
            <tr>
                <td>Technical query&nbsp;</td>
                <td>Ask our technical experts a questions and get the right answer.</td>
            </tr>
            <tr>
                <td>FAQ&nbsp;</td>
                <td>Library of all technical questions and answers asked by peers.</td>
            </tr>
            <tr>
                <td>Technical articles&nbsp;</td>
                <td>Additional reading material linked to specific technical topics.</td>
            </tr>
            <tr>
                <td>ActsOnline</td>
                <td>Access to relevant legislation, including amendments and regulations, in an intuitive, online format.</td>
            </tr>
            <tr>
                <td>Courses</td>
                <td>Practical courses on specific technical topics.</td>
            </tr>
            <tr>
                <td>Seminars</td>
                <td>Practical seminars and workshop on specific technical topics</td>
            </tr>
            </tbody>
        </table>

    </div>
</section>


<section class="alternate">
    <div class="container">
        <div class="row">
            <div class="heading-title heading-dotted" style="margin-bottom: 10px">
                <h4><span>Additional benefits of subscribing</span></h4>
            </div>
            <p>Get up to 50% discount on seminars and courses and these exclusive rewards as additional benefits for subscribing with us.</p>
            <br>
            <br>


            <div class="row">
                <div class="col-md-2">
                    <img src="/assets/frontend/images/sponsors/draftworx.jpg" width="100%" style="margin-bottom: 0px" class="thumbnail" alt="SAIBA">
                </div>
                <div class="col-md-10" style="text-align: left">
                    <h4>Draftworx Financials Statements &amp; Working Papers</h4>
                    <p><small>Comprehensive Financial and Audit Solution without equal</small></p>
                    <p><strong>Reward:</strong> Up to 15% discount. Find out more.</p>
                    <a href="{{ url('/') }}/draftworx" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
                </div>
            </div>

            <div class="row" style="background-color: #e3e3e357; padding-top: 15px; padding-bottom: 15px; margin-top: 15px; margin-bottom: 15px">
                <div class="col-md-2">
                    <img src="/assets/frontend/images/sponsors/bluestar.jpg" width="100%" style="margin-bottom: 0px" class="thumbnail" alt="SAIBA">
                </div>
                <div class="col-md-10" style="text-align: left">
                    <h4>First for BlueStar</h4>
                    <p><small>Professional Financial Advisory Services covering Insurance, Financial Planning, Retirement, Investments and Wealth</small></p>
                    <p><strong>Reward:</strong> Professional Indemnity Insurance from R 600 per annum and reduced premiums. Find out more.</p>
                    <a href="{{ url('/') }}/bluestar" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <img src="/assets/frontend/images/sponsors/quickbooks.jpg" width="100%" style="margin-bottom: 0px" class="thumbnail" alt="SAIBA">
                </div>
                <div class="col-md-10" style="text-align: left">
                    <h4>QuickBooks for Accountants</h4>
                    <p><small>QuickBooks Cloud Accounting Platform: The one place to grow and manage your entire practice.</small></p>
                    <p><strong>Reward:</strong> Free sign up and certification.</p>
                    <a href="{{ url('/') }}/quickbooks" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
                </div>
            </div>

            <div class="row" style="background-color: #e3e3e357; padding-top: 15px; padding-bottom: 15px; margin-top: 15px; margin-bottom: 15px">
                <div class="col-md-2">
                    <img src="/assets/frontend/images/sponsors/saiba.jpg" width="100%" style="margin-bottom: 0px" class="thumbnail" alt="SAIBA">
                </div>
                <div class="col-md-10" style="text-align: left">
                    <h4>SAIBA - Southern African Institute for Business Accountants</h4>
                    <p><small>Your gateway to the accounting profession. Join. Earn. Share.</small></p>
                    <p><strong>Reward:</strong> 50% discount on a designation fees. Find out more</p>
                    <a href="{{ url('/') }}/saiba" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection