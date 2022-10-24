
@extends('app')

@section('title', 'Signup for CPD Subscription')

@section('content')
    <section style="background:url('/assets/frontend/images/demo/wall2.jpg')">
        <app-subscriptions-screen :plans="{{ $plans }}" :user="{{ (auth()->user() ? auth()->user()->load(['cards', 'subscriptions']) : auth()->user()) }}" inline-template>
            <div id="app-register-screen" class="container app-screen">

                {{-- Subscription Plan Selector --}}
                <div class="col-md-12" v-if="plans.length >= 1 && plansAreLoaded && ! forms.subscription.plan">
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
    </Section>

    <section>
        <div class="container">
            <div class="heading-title heading-dotted" style="margin-bottom: 20px;">
                <h4><span>Is this for you?</span></h4>
            </div>

            <p>If you answer "Yes" to any of the below this CPD bundle is for you:</p>
            <ul>
                <li>You did not have time during the year to get all your CPD hours.</li>
                <li>You need to submit your CPD hours to SAICA, SAIPA, or SAIBA before year end and need more hours.</li>
                <li>You want to know more about the latest developments within your profession so that you can help your clients better.</li>
            </ul>

            <div class="heading-title heading-dotted" style="margin-bottom: 20px;">
                <h4><span>What is included?</span></h4>
            </div>
            <p>The bundle consist of the most popular webinars that we hosted in 2018:</p>
            <ol>
                <li>
                    <strong>Accountant's Guide to the Companies Act</strong>
                    <ol>
                        <li><strong>Description</strong>: Detailed analysis of what the Companies Act requires from Auditors, Reviewers and Compilers.</li>
                        <li><strong>Duration</strong>: 2 hours.</li>
                        <li><strong>Presenter</strong>: Lettie Jansen van Vuuren, CA(SA).</li>
                    </ol>
                </li>
                <br>
                <li>
                    <strong>Clarification on CIPC</strong>
                    <ol>
                        <li><strong>Description</strong>: How to file XBRL financial statements with CIPC‎.</li>
                        <li><strong>Duration</strong>: 2 hours.</li>
                        <li><strong>Presenter</strong>: Lettie Jansen van Vuuren, CA(SA).</li>
                    </ol>
                </li>
                <br>
                <li>
                    <strong>SARS Access to Working Papers </strong>
                    <ol>
                        <li><strong>Description</strong>: Search and Seizure: What to do if regulators demands access to your working papers?</li>
                        <li><strong>Duration</strong>: 2 hours.</li>
                        <li><strong>Presenter</strong>: Lettie Jansen van Vuuren, CA(SA).</li>
                    </ol>
                </li>
                <br>
                <li>
                    <strong>How to assist clients to apply for business visas</strong>
                    <ol>
                        <li><strong>Description</strong>: update on the Immigration Act and the various ways your firm can assist foreigners to set up businesses in South Africa. </li>
                        <li><strong>Duration</strong>: 2 hours.</li>
                        <li><strong>Presenter</strong>: Marisa Jacobs, Maya Nikolova, Christopher Renwick.</li>
                    </ol>
                </li>
                <br>
                <li>
                    <strong>How to start a Trust and Estate Practice</strong>
                    <ol>
                        <li><strong>Description</strong>: How accountants should plan, create, manage, and oversee Trusts and Estates for their clients, and charge a fee for this service. </li>
                        <li><strong>Duration</strong>: 4 hours.</li>
                        <li><strong>Presenter</strong>: Jako Liebenberg </li>
                    </ol>
                </li>
                <br>
                <li>
                    <strong>Business Rescue</strong>
                    <ol>
                        <li><strong>Description</strong>: How to become and operate as a Business Rescue Practitioner. </li>
                        <li><strong>Duration</strong>: 4 hours.</li>
                        <li><strong>Presenter</strong>: Louis Klopper </li>
                    </ol>
                </li>
                <br>
                <li>
                    <strong>Ethics Independence and NOCLAR</strong>
                    <ol>
                        <li><strong>Description</strong>: The practical implementation of a workable framework and why it is important for professional accounting organisations to enforce these concepts and revaluate it every year.. </li>
                        <li><strong>Duration</strong>: 4 hours.</li>
                        <li><strong>Presenter</strong>: Nicolaas van Wyk  </li>
                    </ol>
                </li>
                <br>
                <li>
                    <strong>IFRS for SMEs Update</strong>
                    <ol>
                        <li><strong>Description</strong>: The main objective of this course is to keep accounting professionals and practitioners up to date and to give them a comprehensive approach on the current version of the IFRS for SMEs and the amendments .. </li>
                        <li><strong>Duration</strong>: 4 hours.</li>
                        <li><strong>Presenter</strong>: Milan van Wyk, Prof Hentie van wyk</li>
                    </ol>
                </li>
                {{--<br>--}}
                {{--<li>--}}
                    {{--<strong>Monthly Legislation Update</strong>--}}
                    {{--<ol>--}}
                        {{--<li><strong>Description</strong>: The main objective of this course is to keep accounting professionals and practitioners up to date and to give them a comprehensive approach on the current version of the IFRS for SMEs and the amendments .. </li>--}}
                        {{--<li><strong>Duration</strong>: 2 hours Monthly.</li>--}}
                        {{--<li><strong>Presenter</strong>: Lettie Janse van Vuuren CA(SA)</li>--}}
                    {{--</ol>--}}
                {{--</li>--}}
            </ol>

            <div class="heading-title heading-dotted" style="margin-bottom: 20px;">
                <h4><span>Cost</span></h4>
            </div>
            <p>The bundle is valued at more than R4390-00.</p>

        </div>
    </section>

    @include('subscriptions.2017.include.help')
    @include('includes.login')
@endsection