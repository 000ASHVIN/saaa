@extends('app')

@section('content')

@section('title')
    SAIBA CPD Subscription 2018
@stop

@section('intro')
    SA Accounting Academy fantastic Presenters
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('saiba') !!}
@stop

<section>
    <div class="container">
        <div class="row">
            <a href="https://saiba.org.za/" target="_blank">
                <img class="thumbnail" width="100%" src="http://imageshack.com/a/img921/6804/jehGkB.png" alt="SAIBA">
            </a>
        </div>
    </div>
</section>

<section style="background-image: url('/assets/frontend/images/demo/wall2.jpg'); padding-bottom: 10px;">

    <app-subscriptions-screen :plans="{{ $profession->plans }}" :profession="{{ $profession }}"
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
</section>

<section>
    <div class="container">


        <div class="row">
            <div class="col-md-12">
                <div class="heading-title heading-dotted" style="margin-bottom: 20px;">
                    <h4><span>CPD Programme: ESSENTIAL </span></h4>
                </div>

                <p>
                    This is the essential package if you only want CPD points at the lowest possible price, and need an update on the latest changes.
                </p>

                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <td>Monthly Compliance and Legislation Updates</td>
                        <td>Month</td>
                        <td>CPD points</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Monthly Compliance and Legislation Update</td>
                        <td>Every Month</td>
                        <td>24</td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <ul>
                                <li>1 x 2 hour webinar on the 2nd wednesday of every month</li>
                                <li>Recordings, slides and additional material stored within your own online profile
                                </li>
                                <li>Ideal for staff training</li>
                                <li>Full set of course material</li>
                                <li>Free e-mail support on technical issues covered during the monthly sessions</li>
                                <li>Topics: auditing, accounting, tax, SARS operations, CIPC operations, Labour and
                                    other laws
                                </li>
                            </ul>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="heading-title heading-dotted" style="margin-bottom: 20px;">
                    <h4><span>CPD Programme: ESSENTIAL PLUS</span></h4>
                </div>
                {!! $profession->description !!}
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="heading-title heading-dotted" style="margin-bottom: 20px;">
                    <h4><span>CPD Programme: Essential Practice</span></h4>
                </div>
                <p>
                    Get your whole firm compliant.
                </p>
                <p>
                    Select this option and gain access to all Essential Plus content for you and your staff.
                </p>
                <p>
                    The lead partner can be attend either via seminar or webinar. Webinars and recordings Webinars are
                    available for all staff. All participants will get CPD points (certificates). Recordings are loaded
                    on your firm's online profile
                </p>
                <p>
                    Use this facility to monitor your staff training and development.
                </p>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-md-12">
                <div class="heading-title heading-dotted" style="margin-bottom: 20px;">
                    <h4><span>Additional benefits of subscribing</span></h4>
                </div>

                <p>
                    CPD subscribers gain access to <strong><a target="_blank"
                                                              href="http://www.firstforaccountants.co.za/">Professional
                            Indemnity Insurance</a></strong> from as little as R600 per annum. PI
                    Insurance cover is subject to approval and risk assessment by the broker and the underwriter.
                    Typically, an accountant that is compliant and in good standing will be able to obtain R5 000 000 PI
                    cover for less than R600.00 pa.
                </p>

                <p>
                    The PI cover is subject to:
                </p>

                <ul>
                    <li>Underwriter assessment of risk,</li>
                    <li>CPD compliance</li>
                    <li>Professional body membership, and</li>
                    <li>Compliance to professional member standards.</li>
                </ul>

                <a href="http://www.firstforaccountants.co.za/" target="_blank" class="btn btn-primary">Read More</a>

            </div>
        </div>
    </div>
</section>
@endsection