@extends('app')

@section('content')

@section('title')
    {!! $profession->title !!}
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('profession', $profession->title) !!}
@stop

@section('styles')
    <style>
        .curtian {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: #800000;
            opacity: 0.5;
            z-index: 10;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login {
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 100%;
            z-index: 10;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .brandlogo {
            text-align: center;
            margin: 0 0 15px 0;
        }
    </style>
@endsection


    <section style="background-image: url('/assets/frontend/images/demo/wall2.jpg'); padding-bottom: 10px; padding-top: 30px">

        <div class="container text-center">
            <h4><span>{{ ucwords($profession->title) }}</span> <br> <span style="font-size: 14px">Technical Resource Centre & CPD Subscription</span></h4>
            <hr>

        </div>

        <app-subscriptions-screen :plans="{{ $profession->plans }}" :profession="{{ $profession }}"
                                      :user="{{ (auth()->user() ? auth()->user()->load('cards') : auth()->user()) }}" inline-template>
            @if ($profession->slug != 'practice-management')
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
        @else
            <div class="text-center">
                <a href="/auth/register" class="btn btn-primary">Sign Up Now</a>
                <br>
                <br>
            </div>
        @endif
    </section>


@if ($profession->slug != 'practice-management')
    <div class="callout-dark heading-arrow-bottom">
        <a class="btn btn-primary size-10 fullwidth " style="background-color: #adb1b2; border-color: transparent;">
            <span style="font-size: 20px">CPD Subscription and Technical Resource Centre Features</span>
        </a>
    </div>

    <section style="padding-top: 30px; padding-bottom: 30px">
        <div class="container">
            {!! $profession->features !!}
        </div>
    </section>

    <section class="alternate" style="padding-top: 30px; padding-bottom: 30px">
        <div class="container">
            <div class="heading-title heading-dotted">
                <h4><span>CPD Subscription content</span></h4>
            </div>

            {!! $profession->description !!}
        </div>
    </section>

    @if ($profession->slug != 'monthly-legislation-update')
        <section style="padding-top: 30px; padding-bottom: 30px">
            <div class="container">
                <div class="heading-title heading-dotted">
                    <h4><span>Technical resource centre  </span></h4>
                </div>

                {!! $profession->resource_center !!}
            </div>
        </section>
    @endif
    
@else

    <div class="callout-dark heading-arrow-bottom">
        <a  class="btn btn-primary size-10 fullwidth " style="background-color: #adb1b2; border-color: transparent;">
            <span style="font-size: 20px">Free CPD Subscription </span>
        </a>
    </div>

    <section style="padding-top: 30px; padding-bottom: 30px">
        <div class="container">
            {!! $profession->features !!}
        </div>
    </section>
    
    <div class="callout-dark heading-arrow-bottom">
        <a  class="btn btn-primary size-10 fullwidth " style="background-color: #adb1b2; border-color: transparent;">
            <span style="font-size: 20px">CPD Subscription content</span>
        </a>
    </div>

    <section>
        <div class="container">
            {!! $profession->description !!}
        </div>
    </section>
@endif


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
                    <h4>Draftworx Financials Statements & Working Papers</h4>
                    <p><small>Comprehensive Financial and Audit Solution without equal</small></p>
                    <p><strong>Reward:</strong> Up to 15% discount. Find out more.</p>
                    <a href="{{ route('rewards.draftworx') }}" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
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
                    <a href="{{ route('rewards.bluestar') }}" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
                </div>
            </div>

            <div class="row" >
                <div class="col-md-2">
                    <img src="/assets/frontend/images/sponsors/quickbooks.jpg" width="100%" style="margin-bottom: 0px" class="thumbnail" alt="SAIBA">
                </div>
                <div class="col-md-10" style="text-align: left">
                    <h4>QuickBooks for Accountants</h4>
                    <p><small>QuickBooks Cloud Accounting Platform: The one place to grow and manage your entire practice.</small></p>
                    <p><strong>Reward:</strong> Free sign up and certification.</p>
                    <a href="{{ route('rewards.quickbooks') }}" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
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
                    <a href="{{ route('rewards.saiba') }}" class="btn btn-primary pull-right"><i class="fa fa-arrow-right"></i> Find out more</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{--<section>--}}
    {{--<div class="container">--}}
        {{--@if($profession->slug != 'practice-management')--}}
            {{--<div class="row">--}}
                {{--<div class="col-md-12">--}}
                    {{--<div class="heading-title heading-dotted" style="margin-bottom: 20px;">--}}
                        {{--<h4><span>Technical Resource & CPD Programme: ESSENTIAL</span></h4>--}}
                    {{--</div>--}}

                    {{--<p>The ESSENTIAL programme for professionals includes the following technical resource: Monthly Technical & Legislation Updates.</p>--}}
                    {{--<p>The ESSENTIAL programme for {!! $profession->title !!} includes the following: Monthly Compliance and Legislation Updates. </p>--}}

                    {{--<p>--}}
                        {{--This is the essential package if you want to:--}}
                    {{--</p>--}}

                    {{--<div class="row">--}}
                        {{--<div class="col-md-6">--}}
                            {{--<ul>--}}
                                {{--<li><strong>Engage</strong> your <strong>clients</strong> with <strong>confidence</strong>,</li>--}}
                                {{--<li>Get assistance to identify new services to offer your clients,</li>--}}
                                {{--<li>Save +-28 hours of research time on Google/Websites to find the latest technical information and changes to address your clients' problems,</li>--}}
                                {{--<li>Get CPD points at the lowest possible price, and need an update on the latest changes in South Africa (profession and in law).</li>--}}
                            {{--</ul>--}}
                            {{--<p>This is a monthly 2-hour webinar presented by Lettie Janse van Vuuren CA(SA), SAICA and IRBA compliance expert. The webinar covers all the latest changes and updates at SARS, CIPC, accounting, auditing, tax, Labour and other laws applicable to business.</p>--}}
                            {{--<p>Presented every month, on a Wednesday. If a recording is missed you still get to view it online when convenient on the SAAA Catch Up system.</p>--}}
                        {{--</div>--}}

                        {{--<div class="col-md-6">--}}
                            {{--<video width="100%" height="305px" controls="">--}}
                                {{--<source src="https://player.vimeo.com/external/245162850.sd.mp4?s=34484e8db32561d50f36e00b4249dbebb0fcef47&amp;profile_id=164" type="video/mp4">--}}
                                {{--<source src="https://player.vimeo.com/external/245162850.sd.mp4?s=34484e8db32561d50f36e00b4249dbebb0fcef47&amp;profile_id=164" type="video/ogg">--}}
                                {{--Your browser does not support HTML5 video.--}}
                            {{--</video>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--@if($profession->slug != 'monthly-legislation-update')--}}
            {{--<br>--}}
            {{--<div class="row">--}}
                {{--<div class="col-md-12">--}}
                    {{--<div class="heading-title heading-dotted" style="margin-bottom: 20px;">--}}
                        {{--<h4><span>CPD Programme: ESSENTIAL PLUS</span></h4>--}}
                    {{--</div>--}}
                    {{--{!! $profession->description !!}--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--@else--}}
                {{--{!! $profession->description !!}--}}
            {{--@endif--}}

            {{--@if($profession->plans->contains('is_practice', true))--}}
            {{--<br>--}}
            {{--<div class="row">--}}
                {{--<div class="col-md-12">--}}
                    {{--<div class="heading-title heading-dotted" style="margin-bottom: 20px;">--}}
                        {{--<h4><span>CPD Programme: Essential Practice</span></h4>--}}
                    {{--</div>--}}
                    {{--<p>--}}
                        {{--Get your whole firm compliant.--}}
                    {{--</p>--}}
                    {{--<p>--}}
                        {{--Select this option and gain access to all Essential Plus content for you and your staff.--}}
                    {{--</p>--}}
                    {{--<p>--}}
                        {{--The lead partner can be attend either via seminar or webinar. Webinars and recordings Webinars are--}}
                        {{--available for all staff. All participants will get CPD points (certificates). Recordings are loaded--}}
                        {{--on your firm's online profile--}}
                    {{--</p>--}}
                    {{--<p>--}}
                        {{--Use this facility to monitor your staff training and development.--}}
                    {{--</p>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--@endif--}}

            {{--<br>--}}

            {{--<div class="row">--}}
                {{--<div class="col-md-12">--}}
                    {{--<div class="heading-title heading-dotted" style="margin-bottom: 20px;">--}}
                        {{--<h4><span>Additional benefits of subscribing</span></h4>--}}
                    {{--</div>--}}

                    {{--<p>--}}
                        {{--CPD subscribers gain access to <strong><a target="_blank"--}}
                                                                  {{--href="http://www.firstforaccountants.co.za/">Professional--}}
                                {{--Indemnity Insurance</a></strong> from as little as R600 per annum. PI--}}
                        {{--Insurance cover is subject to approval and risk assessment by the broker and the underwriter.--}}
                        {{--Typically, an accountant that is compliant and in good standing will be able to obtain R5 000 000 PI--}}
                        {{--cover for less than R600.00 pa.--}}
                    {{--</p>--}}

                    {{--<p>--}}
                        {{--The PI cover is subject to:--}}
                    {{--</p>--}}

                    {{--<ul>--}}
                        {{--<li>Underwriter assessment of risk,</li>--}}
                        {{--<li>CPD compliance</li>--}}
                        {{--<li>Professional body membership, and</li>--}}
                        {{--<li>Compliance to professional member standards.</li>--}}
                    {{--</ul>--}}

                    {{--<a href="http://www.firstforaccountants.co.za/" target="_blank" class="btn btn-primary">Read More</a>--}}

                {{--</div>--}}
            {{--</div>--}}
        {{--@else--}}
            {{--<div class="row">--}}
                {{--<div class="container">--}}
                    {{--<div class="heading-title heading-dotted" style="margin-bottom: 20px;">--}}
                        {{--<h4><span>Practice management series</span></h4>--}}
                    {{--</div>--}}
                    {{--<p><strong>Introduction:</strong></p>--}}
                    {{--<p>The Practice Management series of webinars and events is designed to improve your ability to run both a profitable and compliant practice. </p>--}}
                    {{--<p>The Practice Management series offers the following: </p>--}}
                    {{--<ul>--}}
                        {{--<li>How to run and manage a profitable accounting/auditing firm.</li>--}}
                        {{--<li>How to manage the risks associated with running an accounting/auditing firm.</li>--}}
                        {{--<li>How to improve your employees financial IQ and wellbeing.</li>--}}
                    {{--</ul>--}}
                    {{--<div class="heading-title heading-dotted" style="margin-bottom: 20px;">--}}
                        {{--<h4><span>How to run and manage a firm</span></h4>--}}
                    {{--</div>--}}
                    {{--<p>Using the IFAC Guide for SMPs the key aspects of practice management include the following:</p>--}}
                    {{--<p>--}}
                        {{--<strong>Strategy and planning</strong> - Practices need to build a strong foundation to ensure their success. A strong foundation starts with a clear strategy and a plan to execute it.--}}
                    {{--</p>--}}
                    {{--<p>--}}
                        {{--<strong>Practice models</strong> - Practices will need to consider the best structure and model to use as well as determine profit sharing and decision making within the firm.--}}
                    {{--</p>--}}
                    {{--<p>--}}
                        {{--<strong>Business development</strong> - As practices grow, they will increasingly need to consider issues like developing a growth strategy, coping with increased regulation and competition, pricing, marketing and client relationship management, what services to offer and which clients to serve, and building a firm culture. Professional services range from the more traditional offerings of audit, assurance, accounting, and tax to emerging areas of advisory and reporting such as sustainability and integrated reporting. Successful firms can be highly specialized or general; they can focus on traditional accounting services or value-added advisory services. Advisory services is one of the fastest growing service areas as organizations, especially smaller ones, increasingly look to accounting firms for advice ranging from regulatory compliance to doing business overseas and adopting sustainable business practices.--}}
                    {{--</p>--}}
                    {{--<p>--}}
                        {{--<strong>Networking and networks</strong> - In order to gain new clients and access expertise, many practices use different forms of networking, from traditional face to face through to electronic social media, as well as joining networks, associations and alliances of practices as a means of obtaining client referrals, providing clients access to other experts  and gaining access to resources and tools.--}}
                    {{--</p>--}}
                    {{--<p>--}}
                        {{--<strong>Marketing</strong> - Attracting and retaining clients is a primary challenge facing practices, and securing new clients is one of the main drivers of future profits. This makes marketing and client relations one of the keys to success. Effective marketing and client relationship management demands communicating your value and services as well as knowing your clients, existing and potential—their challenges, aspirations, needs, and preferences. Practices need to understand how best to develop and maintain client relationships, including strategies to improve and cement client relationships.--}}
                    {{--</p>--}}
                    {{--<p>--}}
                        {{--<strong>Human resource management</strong> - Staff and the firm’s leadership are arguably the most important assets of any firm and, as such, crucial to the provision of high-quality services and the ultimate success of the firm. Key issues to address include how to attract, retain, motivate, and train staff.--}}
                    {{--</p>--}}
                    {{--<p>--}}
                        {{--<strong>Information technology</strong> - It is critical for firms to adopt best practice in respect to emerging technologies, such as social media, smartphones, and cloud computing, as these technologies can help marketing and talent recruitment, reduce costs, and offer new client service opportunities. Cloud computing presents both opportunities—lower cost, wider geographical reach, and new services—and threats, such as the ability of SMEs to perform certain basic accounting functions themselves resulting in a reduction in the demand for such services from practices. Effective selection, implementation, and management of technologies, as well as training employees to use them, are fundamental to the success of any firm.--}}
                    {{--</p>--}}
                    {{--<p>--}}
                        {{--<strong>Risk Management</strong> - Risk management is important to practices especially those in more litigious jurisdictions where the number and size of legal claims have increased over the years. There are frameworks and standards for identifying, evaluating, and acting on risks within a firm. Risk management includes ethical issues and safeguards that can be used to deal with ethical threats, the role of quality control systems, and additional risk mitigation, such as insurance.--}}
                    {{--</p>--}}
                    {{--<p>--}}
                        {{--<strong>Succession planning</strong> - As professional accountants in practice age, their thoughts inevitably turn to the value of their assets within the firm and their exit strategies from the firm. Hence, it is important that the practice has a succession plan that allows for the orderly exit of practitioners and a strategy that can be implemented to become succession ready. Succession planning includes discussing valuation and pricing, and options for consolidations, mergers, and internal and external buyouts.--}}
                    {{--</p>--}}
                    {{--<div class="heading-title heading-dotted" style="margin-bottom: 20px;">--}}
                        {{--<h4><span>How to manage the following risks associated with your firm:</span></h4>--}}
                    {{--</div>--}}
                    {{--<ul>--}}
                        {{--<li>Client Intake / Declined Client Procedures</li>--}}
                        {{--<li>Professional conduct</li>--}}
                        {{--<li>Professional conduct</li>--}}
                        {{--<li>Human resources</li>--}}
                        {{--<li>Business Continuity and Disaster Response</li>--}}
                        {{--<li>Computer / Network Risks</li>--}}
                        {{--<li>Insurance Protection Review</li>--}}
                        {{--<li>Electronic communications and Confidentiality</li>--}}
                        {{--<li>File Control (paper and electronic)</li>--}}
                        {{--<li>Subpoena, Incident and Claims Handling Procedures</li>--}}
                        {{--<li>Subpoena, Incident and Claims Handling Procedures</li>--}}
                        {{--<li>Calendar Systems</li>--}}
                        {{--<li>Practice Area Risks</li>--}}
                        {{--<li>Client Relations</li>--}}
                        {{--<li>Public Relations</li>--}}
                    {{--</ul>--}}
                    {{--<div class="heading-title heading-dotted" style="margin-bottom: 20px;">--}}
                        {{--<h4><span>How to improve your employees financial IQ and wellbeing.</span></h4>--}}
                    {{--</div>--}}
                    {{--<ul>--}}
                        {{--<li>Personal financial planning</li>--}}
                        {{--<li>Investing made simple</li>--}}
                        {{--<li>Investing in property</li>--}}
                        {{--<li>Improve your money IQ</li>--}}
                    {{--</ul>--}}

                    {{--@if(auth()->guest())--}}
                        {{--<div class="heading-title heading-dotted" style="margin-bottom: 20px;">--}}
                            {{--<h4><span>How to subscribe</span></h4>--}}
                        {{--</div>--}}
                        {{--<p><a >Click here</a> to subscribe to the complete practice management series.</p>--}}
                    {{--@endif--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--@endif--}}
    {{--</div>--}}
{{--</section>--}}
@endsection