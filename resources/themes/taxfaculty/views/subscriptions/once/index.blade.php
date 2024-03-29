
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
    .table-striped ol li {
        margin-top: 5px;
    }

</style>
<section id="slider" class="hidden-sm hidden-xs">
    <center >
        <div data-target="#need_help_subscription_cyber" data-toggle="modal" target="_blank" style="background-image: url('/assets/themes/taxfaculty/img/50__Discount_on_your_CPD_Catch-up_Package_(web).jpg'); height: 320px; background-color: #000000; position:relative; top: 0px;    background-repeat: no-repeat;background-size: 100% 100%;cursor: pointer;">
            {{--  <h4 style="color: red; line-height: 30px; font-size: 30px">Black Friday 50% discount</h4>
            <h5 style="color: #ffffff; line-height: 30px;">Find great deals and save up to 50% this Friday 29 November.</h5>  --}}
            {{--  <div class="countdown bordered-squared theme-style" data-from="November 1, 2018 00:00:00"></div>  --}}
            {{--  <a href="#" data-target="#need_help_subscription" data-toggle="modal" target="_blank" style="margin-bottom: 10px; background-color: red"; class="btn btn-red">Need Help ?</a>
            <p style="font-weight: bold">Limited stock available!</p>  --}}

        </div>
    </center>
</section>
    <section style="background:url('/assets/frontend/images/demo/wall2.jpg')">
        <app-subscriptions-screen :subscriptions="{{(auth()->user()?auth()->user()->subscription('cpd')->plan:auth()->user())}}"  :plans="{{ $plans }}" :user="{{ (auth()->user() ? auth()->user()->load(['cards', 'subscriptions']) : auth()->user()) }}" inline-template>
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

            <p>If you answer "Yes" to any of the below, then this Tax and Accounting 2021 CPD Catch-Up offer is for you:</p>
            <ul>
                <li>You are a professional registered with SAIT, FPI, SAICA, SAIBA, SAIPA, CIMA, ACCA, CSSA, IAC or ICBA.</li>
                <li>You did not have time during the year to get all your compulsory Tax, Accounting CPD hours.</li>
                <li>You want to know more about the latest developments within your profession so that you can help your clients better.</li>
            </ul>

            <div class="heading-title heading-dotted" style="margin-bottom: 20px;">
                <h4><span>What is included?</span></h4>
            </div>
            {{--  <p>CPD for 2020 and hosted in 2020 to 2021</p>  --}}
            <div>
                  
            </div>
            <table  width="100%"  class="table-striped">
                <thead>
                    <tr>
                        <td>
                            Tax Topics:
                        </td>
                        <td>
                            Accounting Topics:
                        </td>
                    </tr>
                </thead>
                <tbody>
            
                    <tr>
                        <td>
                            <strong>1. Taxation of crypto assets </strong>
                            <ol>
                                {{--  <li> a. Description: Get a full update of the most important tax amendments and be able to prepare for proposed changes, without being caught off-guard.
                                </li>  --}}
                                <li> a. Duration: 2 Hours</li> 
                                <li> b. Presenter: Chris Herbst</li>
                            </ol>
                        </td>
                        <td>
                            <strong> 1. Ethics, Independence and Noclar </strong>
                            <ol>
                                {{--  <li> a. Description: Description: The importance of adhering to the principles of ethics cannot be overemphasised in the accounting profession. Integrity and objectivity are two of the cornerstones of the accountancy profession that should constantly be evaluated considering today's evolving business environment. As corporate and government scandals (some involving accounting professionals) continue to pop up regularly in the headlines, accountants need to take a critical look at themselves and play their part in stopping this continuing rot.</li>  --}}
                                <li> a. Duration: 4 Hours</li> 
                                <li> b. Presenter: Caryn Maitland CA(SA)</li>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> 2. Trusts: Get the Basics Right </strong>
                            <ol>
                                 {{--  <li>a.  Description: Various South Africans are required to work abroad. Many questions arise related to the taxing of income earned by these South Africans. Double tax agreements, the location of the employee’s family and home, the duration of the agreement and various other factors could impact the tax consequences and considerations.</li>  --}}
                                <li> a. Duration: 2 Hours</li> 
                                <li> b. Presenter: Prof Walter Geach</li>
                            </ol>
                        </td>
                        <td>
                            <strong> 2. Financial Statements</strong>
                            <ol>
                                {{--  <li> a. Description: Our Monthly Compliance and Legislation Update (MCLU) webinars provide a summary of some of the most important legislation updates from the previous months. This helps you, our client to stay up to date on recent and important legislation developments in auditing, accounting, tax, SARS operations, CIPC operations, Labour and other relevant laws.</li>  --}}
                                <li> a. Duration: 4 Hours</li> 
                                <li> b. Presenter: Caryn Maitland CA(SA)</li>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> 3. 2021 Current Tax Issues for Individual Tax Payers</strong>
                             <ol>
                                {{-- <li> a.  Description: This webinar covers the principles related to a SARS audit or verification as well as the practical considerations, such as how to communicate with SARS and what taxpayers should do if they are not in agreement with audit findings. The webinar is practical and hands-on and attendees will be exposed to case-study based learning.</li> --}}
                                <li> a. Duration: 4 Hours</li>
                                <li> b. Presenter: Corlia Faurie </li>
                            </ol> 
                        </td>
                        <td>
                            <strong> 3. Reporting Engagements  </strong>
                            <ol>
                                 {{--  <li> a. Description: The three distinct types of reporting engagements that accountants can perform are guided by specific framework, standards as well as legislation. ISRS 4410, ISRS 4400 and ISRE 2400 give specific guidance on compilations, agreed-upon procedure engagements and independent reviews respectively. Various acts such as the Close Corporations Act, 1984, Companies Act, 2008, Sectional Titles Schemes Management Act, 2011, and South African Schools Act, 1996; have provisions that need to be adhered to during the performance of reporting engagements.</li>  --}}
                                <li> a. Duration: 4 Hours</li> 
                                <li> b. Presenter: Jako Liebenberg CA(SA)</li>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> 4.  Dealing with SARS Income Tax Audits</strong>
                            <ol>
                                 {{--  <li> a.  Description: The accounting and tax profession has faced many challenges in the last few years, to which we need to respond with a reflective assessment of our personal, professional and business ethics, as well as understanding the conflicts and ethical dilemmas we face on a daily basis.</li>  --}}
                                <li> a. Duration: 4 Hours</li> 
                                <li> b. Presenter: Johan Heydenrych</li>
                            </ol>
                        </td>
                        <td>
                            <strong> 4. IFRS for SME Update  </strong>
                            <ol>
                                 {{--  <li> a.  Description: According to a recently published report, the SME sector contributes more than 20% to the country’s gross domestic product (GDP) and employs 47% of South Africa’s workforce. These numbers show how important the SME sector is to the South African economy. Accurate accounting and reporting of your SME clients' financials contributes greatly to the success and growth of this important sector. As you perform your financial reporting duties for these clients, you should be guided by the IFRS for SMEs Standard. This financial reporting Standard is based on the principles contained in full IFRS Standards but is specifically tailored for small companies.</li>  --}}
                                <li> a. Duration: 4 Hours</li> 
                                <li> b. Presenter: Caryn Maitland CA(SA)</li>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> 5. Ethics for Tax Practitioners</strong>
                            <ol>
                                 {{--  <li> a.  Description: Personal Income Tax is relevant to salaried employees and self-employed individuals.   In this comprehensive session we will take a 360-degree view of the issues affecting individual taxpayers.  We will also deal with how items should be reflected and declared on the ITR12.  The session will be practical and case study based.</li>  --}}
                                <li> a. Duration: 5 Hours</li> 
                                <li> b. Presenter: Caryn Maitland CA(SA)</li>
                            </ol>
                        </td>
                        <td>
                            <strong> 5. Drafting Wills </strong>
                            <ol>
                                 {{--  <li> a.  Description: On the 1st of January 2020, SAICA's and IRBA's amended output-based CPD policies came into effect. There are some notable differences between SAICA’s old input-based CPD policy and the new output-based 2020 CPD policy which every full or associate member of SAICA needs to be aware of.  Anton van Wyk M.Com, CA(SA) studied the contents and requirements of the amended CPD policy and has previously presented a webinar on it.</li>  --}}
                                <li> a. Duration: 2 Hours</li> 
                                <li> b. Presenter: Jako Liebenberg CA(SA)</li>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> 6. Taxation of Farming Enterprises </strong>
                            <ol>
                                 {{--  <li> a.  Description: The taxation of trusts is an area that is often poorly understood. For many years, this did not negatively impact taxpayers, as taxpayers had a lot of freedom to structure trusts in such a way to obtain a tax advantage through effective estate planning.</li>  --}}
                                <li> a. Duration: 2 Hours</li> 
                                <li> b. Presenter: Carmen Westermeyer</li>
                            </ol>
                        </td>
                        <td>
                            <strong> 6. Overview & Recent Amendments to the Companies Act  </strong>
                            <ol>
                                 {{--  <li> a. Description: The Companies Act, 2008 is the piece of legislation that forms the backbone of company regulation in South Africa. In a nutshell, it provides for the incorporation, registration, organisation, management and capitalisation of companies. This Act also defines the relationships between companies and their shareholders and directors/members. The purpose of this webinar is not to discuss all the legal provisions of the Act but to highlight the parts of the Act that affect accountants in their day to day roles. Recent amendments and their effect on the work of the accountant will also be discussed as well as the CIPC's increasing regulatory powers.</li>  --}}
                                <li> a. Duration: 4 Hours</li> 
                                <li> b. Presenter: Edith Wilkins</li>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> 7. 2021 Current Tax Issues for Corporate Taxpayers </strong>
                            <ol>
                                <li> a. Duration: 4 Hours</li> 
                                <li> b. Presenter: Carmen Westermeyer</li>
                            </ol>
                        </td>
                        <td>
                            <strong> 7. Business Rescue Engagements    </strong>
                            <ol>
                                 {{--  <li> a.  Description: Accountants, bookkeepers, financial managers and accounting officers play important roles in the preparation, presentation and interpretation of financial statements. In these roles, they are guided by IFRS or IFRS for SMEs, the Companies Act, and the Tax Administration Act. The aim of this webinar is to take the requirements of the above Acts and reporting standards, and condense them into an understandable and practical presentation so that application, presentation and disclosure in the financial statements is simplified for practitioners. During the webinar, attendees will receive practical advice and guidance as they are taken through an illustrative set of financial statements that will enable them to create a disclosure checklist that they can make use of in their work. </li>  --}}
                                <li> a. Duration: 2 Hours</li> 
                                <li> b. Presenter: Caryn Maitland CA(SA)</li>
                            </ol>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> 8. Dealing with SARS VAT and Payroll Audits </strong>
                            <ol>
                                <li> a. Duration: 4 Hours</li> 
                                <li> b. Presenter: Carmen Westermeyer</li>
                            </ol>
                        </td>
                        <td>
                            <strong> 8. Management Accounts  </strong>
                            <ol>
                                 {{--  <li> a. Description: Owners and managers of SMEs monitor the health of their businesses and make sound business decisions based on management accounting reports. In order to draft effective management accounts, accountants need to focus on relevant and useful information. Two important elements to producing good reports are:
                                    <ol>
                                        <li>1. Including all the necessary detail; and</li>
                                        <li>2. Making visually appealing reports. This is important so that the non-financially minded can get the big picture at a glance.</li>
                                    </ol>
                                    This webinar will focus how to take the detailed reports produced and convert them into user-friendly, visually appealing and concise reports.                                        
                                 </li>  --}}
                                <li> a. Duration: 2 Hours</li> 
                                <li> b. Presenter: Caryn Maitland CA (SA)</li>
                            </ol>
                        </td>
            
                    </tr>
                    {{--  <tr>
                        <td>
                           
                        </td>
                        <td>
                            <strong> 9. Preparing accountants to thrive in the Fourth Industrial Revolution</strong>
                            <ol>
                                <li> a. Description: The Fourth Industrial Revolution also known as 4IR or Industry 4.0 is a phenomenon that has been with us for some time now. But what is the Fourth Industrial Revolution and why should you care? 4IR is the growing utilisation of new technologies that are disrupting the way we have always done things and more importantly, how we conduct business. These technologies include robotics, artificial intelligence (AI), the Internet of Things (IoT) and cloud computing. As an accountant you will no doubt have seen how most accounting software is becoming cloud-based. Practitioners who had embraced at least some of these technologies in their business processes before COVID-19 are not facing major disruption to their operations.</li>
                                <li> b. Duration: 2 hours</li>
                                <li> c. Presenter:  Earl Steyn CBA (SA)</li>
                            </ol>
                        </td>
            
                    </tr>  --}}
                    
            
                </tbody>
            </table>
            
            <p>* These Tax Topics are included in the Last-Minute Tax CPD for 2021 Package</p>

            <p>For more information you can phone us on 012 943 7002 or e-mail <a href="mailto:info@taxfaculty.ac.za" >info@taxfaculty.ac.za </a></p>

        </div>

        

        
    </section>

    @include('subscriptions.2017.include.cyberhelp')
    @include('includes.login')
@endsection