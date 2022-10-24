
@extends('app')

@section('title', 'Signup for CPD Subscription')

@section('content')
<style>
    table tr td {
        width: 50%;
        padding: 1px 0 0 8px;
    }
    
    thead td {
        text-align: center;
        font-size: 20px;
        font-weight: 800;
        
    }
    table ol,table ul{
        list-style-type: none;
    }


</style>
    <section style="background:url('/assets/frontend/images/demo/wall2.jpg')">
        <app-subscriptions-screen :subscriptions="{{(auth()->user()?auth()->user()->subscription('cpd')->plan:auth()->user())}}" :plans="{{ $plans }}" :user="{{ (auth()->user() ? auth()->user()->load(['cards', 'subscriptions']) : auth()->user()) }}" inline-template>
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

            <p>If you answer "Yes" to any of the below, then this Tax and Accounting 2019 CPD Catch-Up offer is for you:</p>
            <ul>
                <li>You are a professional registered with SAIBA.</li>
                <li>You did not have time during the year to get all your compulsory Tax, Accounting CPD hours.</li>
                <li>You want to know more about the latest developments within your profession so that you can help your clients better.</li>
            </ul>

            <div class="heading-title heading-dotted" style="margin-bottom: 20px;">
                <h4><span>What is included?</span></h4>
            </div>
            <p>The Last-Minute Tax and Accounting CPD for 2019 bundle consist of the most popular webinars that we hosted in 2019:</p>
            <div>
                  
            </div>
            <table class="table-striped">
                    <thead>
                        <tr>
                            <td>
                                Accounting Topics:
                            </td>
                            <td>
                                Tax Topics:
                            </td>
                
                        </tr>
                    </thead>
                    <tbody>
                
                        <tr>
                            <td>
                                <strong> 1. Ethics Independence and NOCLAR </strong>
                                <ol>
                                    <li> a. Description: Principles of ethics applicable to accountants, the practical implementation
                                        of a workable framework.</li>
                                    <li> b. Duration: 4 hours</li>
                                    <li> c. Presenter: Caryn Maitland CA (SA)</li>
                                </ol>
                            </td>
                            <td>
                                <strong>1. Budget and Tax Update</strong>
                                <ol>
                                    <li> a. Description: The Annual Budget and Tax Update gives you the best opportunity to get up to
                                        speed on recent developments in tax, legislative changes and proposed changes announced in the
                                        National Budget Speech.</li>
                                    <li> b. Duration: 4 hours.</li>
                                    <li> c. Presenter: Prof. Jackie Arendse</li>
                                </ol>
                            </td>
                
                        </tr>
                        <tr>
                            <td>
                                <strong> 2. Reporting Engagements: Accounting Officer, Review and Compilations </strong>
                                <ol>
                                    <li> a. Description: Detailed explanation of the different reporting engagements and guidance on
                                        how to perform them‎.</li>
                                    <li> b. Duration: 4 hours</li>
                                    <li> c. Presenter: Jako Liebenberg CA(SA)</li>
                                </ol>
                            </td>
                            <td>
                                <strong> 2. Submissions of ITR14 </strong>
                                <ol>
                                    <li>a. Description: The primary responsibility of the tax practitioner with regard to the
                                        submission of the ITR14 is to ensure that complete and accurate information is submitted to
                                        SARS and that defendable positions are taken whenever Uncertain Tax Positions arise.</li>
                                    <li> b. Duration: 2 hours.</li>
                                    <li> c. Presenter: Johan Heydenrych CA(SA)</li>
                                </ol>
                            </td>
                
                        </tr>
                        <tr>
                            <td>
                                <strong> 3. IFRS for SME Update </strong>
                                <ol>
                                    <li> a. Description: Understand how to apply the IFRS for SMEs Standard in various circumstances.</li>
                                    <li> b. Duration: 4 hours</li>
                                    <li> c. Presenter: Caryn Maitland CA(SA)</li>
                                </ol>
                            </td>
                            <td>
                                <strong> 3. Tax Issues for Individuals </strong>
                                <ol>
                                    <li> a. Description: The Tax Issues for Individuals workshop will cover key issues for completing
                                        individual tax returns for the 2019 tax filing season.</li>
                                    <li> b. Duration: 5.5 hours</li>
                                    <li> c. Presenter: Piet Nel CA(SA)</li>
                                </ol>
                            </td>
                
                        </tr>
                        <tr>
                            <td>
                                <strong> 4. Knowing the requirements of the Companies Act and your MOI 2019 </strong>
                                <ol>
                                    <li> a. Description: Know the requirements of the Companies Act, 2008 as well as the implications
                                        of not following the contents of your MOI.</li>
                                    <li> b. Duration: 4 hours</li>
                                    <li> c. Presenter: Edith Wilkins</li>
                                </ol>
                            </td>
                            <td>
                                <strong> 4. Effective handling of SARS queries, audits and dispute resolution </strong>
                                <ol>
                                    <li> a. Description: Focus on the administrative aspects of the dispute and controversy process,
                                        from the initial SARS query to the SARS appeals process. Issues relating to the Tax
                                        Administration Act and the rules of dispute resolution will be discussed. </li>
                                    <li> b. Duration: 4 hours.</li>
                                    <li> c. Presenter: Piet Nel CA(SA)</li>
                                </ol>
                            </td>
                
                        </tr>
                        <tr>
                            <td>
                                <strong> 5. Accounting for trusts and deceased estates </strong>
                                <ol>
                                    <li> a. Description: Understand all critical aspects of trusts and estates as well as get insights
                                        on specifics you should be aware of.</li>
                                    <li> b. Duration: 2 hours</li>
                                    <li> c. Presenter: Sonja Frank</li>
                                </ol>
                            </td>
                            <td>
                                <strong> 5. Tax2020: The Tax Migration vs Financial Emigration  </strong>
                                <ol>
                                    <li> a. Description: This two-hour webinar will attempt to unpack the Act, as it was promulgated in
                                        December 2017 (effective as of 1 March 2020), and share some thoughts on the relevant 2019
                                        foreign payroll and foreign remuneration tax policy changes.</li>
                                    <li> b. Duration: 2 hours.</li>
                                    <li> c. Presenter: Hugo van Zyl CA(SA)</li>
                                </ol>
                            </td>
                
                        </tr>
                        <tr>
                            <td>
                                <strong> 6. Management accounts </strong>
                                <ol>
                                    <li> a. Description: Get useful internal reporting techniques that assist businesses to manage
                                        their working capital risks and solve liquidity and solvency issues</li>
                                    <li> b. Duration: 2 hours</li>
                                    <li> c. Presenter: Caryn Maitland CA (SA)</li>
                                </ol>
                            </td>
                            <td>
                                <strong> 6. Dealing with interest free loans (Section 7C) </strong>
                                <ol>
                                    <li> a. Description: The purpose of this webinar is to provide guidance on the section 7C
                                        application, and the various compliance and planning issues faced by tax practitioners.</li>
                                    <li> b. Duration: 2 hours.</li>
                                    <li> c. Presenter: Hugo van Zyl CA(SA)</li>
                                </ol>
                            </td>
                
                        </tr>
                        <tr>
                            <td>
                                <strong> 7. Independent Review </strong>
                                <ol>
                                    <li> a. Description: Be able to accept, perform and complete an independent review engagement.</li>
                                    <li> b. Duration: 2 hours</li>
                                    <li> c. Presenter: Tendai Chinyande CA(SA)</li>
                                </ol>
                            </td>
                            <td>
                                <strong> 7. Provisional Tax & Penalties </strong>
                                <ol>
                                    <li> a. Description: Provisional taxpayers are often penalised by SARS, not because the provisional
                                        tax was paid late, but as a result of the underpayment of provisional tax due to an
                                        underestimation. In this webinar, we will revisit the law relevant to provisional taxpayers and
                                        the payment of provisional tax. The webinar will specifically deal with how the estimate of
                                        taxable income must be made and the relevance of the ‘basic amount’ in this respect</li>
                                    <li> b. Duration: 2 hours.</li>
                                    <li> c. Presenter: Piet Nel CA(SA)</li>
                                </ol>
                            </td>
                
                        </tr>
                        <tr>
                            <td>
                                <strong> 8. The Accountant's role in BEE Verification </strong>
                                <ol>
                                    <li> a. Description: Understand the documents and verification processes that will guide you
                                        towards becoming an expert yourself.</li>
                                    <li> b. Duration: 2 hours</li>
                                    <li> c. Presenter: Yugen Pillay</li>
                                </ol>
                            </td>
                            <td>
                                <strong> 8. Payroll Taxes and Administration</strong>
                                <ol>
                                    <li> a. Description: This webinar will explain the changes underway to tax law, including the
                                        important foreign employment income taxation rules that will have important consequences for
                                        our economy. Significant changes are also being made to various labour statutes, introducing
                                        three new types of leave as well as their related benefits.</li>
                                    <li> b. Duration: 2 hours.</li>
                                    <li> c. Presenter: Rob Cooper</li>
                                </ol>
                            </td>
                
                
                        </tr>
                
                    </tbody>
                </table>
            
            <p>For more information you can phone us on 010 593 0466 or e-mail <a href="mailto:support@accountingacademy.co.za" >support@accountingacademy.co.za </a></p>

        </div>

        

        
    </section>

    @include('subscriptions.2017.include.help')
    @include('includes.login')
@endsection