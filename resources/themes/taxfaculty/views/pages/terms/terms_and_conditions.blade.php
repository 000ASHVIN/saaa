@extends('app')

@section('content')

@section('title')
    Terms and Conditions
@stop

@section('intro')

@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('terms') !!}
@stop

<section>
    <div class="container">
        <div class="terms_and_conditions">
            <div class="row">
                <div class="heading-title">
                    <h4>Standard terms and conditions of business</h4>
                </div>

                <div class="section">
                    <div class="title">
                        <p>Introduction</p>
                    </div>
                    <p>These terms and conditions will apply to all educational services (including bookings, engagements with, attendance or use of a seminar, conference, workshop, CPD subscription service, online learning services, online profile or other similar service or product hosted, offered, organised or to be hosted, offered, or organised by The Tax Faculty NPC or any of its representatives and marketed as such ('the service' or 'the event').</p>
                </div>

                <div class="section">
                    <div class="title">
                        <p>Definitions</p>
                    </div>

                    <ul>
                        <li>“Agreement” means these terms and conditions, the Invoice, the Booking Form, and the Client’s acceptance of the Booking Form.</li>
                        <li>“Profile” means Internet Identity with The Tax Faculty.</li>
                        <li>The Tax Faculty, a non-profit company with registration number 2007/020344/08 and principal place of business at 41 MatroosbergRoad, Pretoria, 0081.</li>
                    </ul>
                </div>

                <div class="section">
                    <div class="title">
                        <p>Bookings, Confirmations and Effective date</p>
                    </div>

                    <ul>
                        <li>All clients are required to make a booking online for any required service or event hosted.</li>
                        <li>Once a booking has been made, an invoice will be automatically generated and sent to the client.</li>
                        <li>The contract of service ('the booking') will be in force and deemed to be effective once payment has been made to and received by The Tax Faculty.</li>
                        <li>If a client has booked an event but does not receive a confirmation 7 (seven) working days prior to the Event date, it is the responsibility of the client to contact The Tax Faculty on 012 943 7002 or <a href="mailto:events@taxfaculty.ac.za" class="aqua" target="_blank">events@taxfaculty.ac.za</a> and follow-up on the booking.</li>
                        <li>Live training event registration closes 24 (twenty-four) hours prior to each event. Late registrations shall only be permitted if the client has made prior arrangements with <a href="mailto:events@taxfaculty.ac.za" class="aqua" target="_blank">events@taxfaculty.ac.za</a>.</li>
                        <li>Venues, times, topics, presenters, programme offerings, and online schedules are subject to change without notice and are determined solely at the discretion of The Tax Faculty.</li>
                        <li>All clients must provide true and correct contact and notice details. Clients must notify in writing the Tax Faculty of any change in contact information or notice address. The Tax Faculty can not be held liable if a client, fails to receive important or relevant communique from the Tax Faculty because they, the client, failed to provide or notify The Tax Faculty of their change of address or contact information.</li>
                    </ul>
                </div>

                <div class="section">
                    <div class="title">
                        <p>Payments</p>
                    </div>

                    <ul>
                        <li>Payment for an invoice can be made in any of the following manner:
                            <br>
                            <ul type="circle">
                                <li>by electronic funds transfer (EFT);</li>
                                <li>direct cash deposit to the bank account of the Tax Faculty; or</li>
                                <li>by credit/debit card using the payment facility on the Tax Faculty website; or</li>
                                <li>by instant EFT payment on the Tax Faculty website</li>
                                <li>Clients should not wait for payments to reflect, clients must effect payment and provide a proof of payment to the Tax Faculty to have their funds allocated in a timely manner.</li>
                            </ul>
                        </li>
                        <li>Payment for bookings must be made no later than 48 (forty-eight) hours prior to the event.</li>
                        <li>The price of the event will be the price displayed on the event page. The price is inclusive of Value-Added Tax (VAT).</li>
                        <li>No client will be permitted entrance to the event without proof of payment having been received in advance or presented to an The Tax Faculty staff member on the day. For late registrations, full payment must be made at the venue via EFT and proof of payment provided to staff member. No cash, cheque, credit, or debit card payments will be accepted.</li>
                        <li>EFT Payments:
                            <br>
                            <ul type="circle">
                                <li>It is the responsibility of the client to verify the banking details of the Tax Faculty.</li>
                                <li>To verify the bank details please contact <a href="mailto:accounts@taxfaculty.ac.za" class="theme_color" target="_blank">accounts@taxfaculty.ac.za</a>.</li>
                                <li>Once a payment has been made, clients must submit proof of same, clearly stating the invoice number that appears on the invoice.</li>
                                <li>Proof of payment should be emailed to <a href="mailto:accounts@taxfaculty.ac.za" class="theme_color" target="_blank">accounts@taxfaculty.ac.za</a></li>
                            </ul>
                        </li>
                        <li>Credit Card Payments:
                            <br>
                            <ul type="circle">
                                <li>You will receive notification that your payment was successful. If, for whatever reason, the credit/debit card payment failed, you will also be notified and will be instructed on how to proceed.</li>
                            </ul>
                        </li>
                    </ul>
                </div>


                <div class="section">
                    <div class="title">
                        <p>Cancellations and refunds</p>
                    </div>

                    <div class="theme_color">
                        <p>Event cancellations by the Tax Faculty</p>
                    </div>

                    <ul>
                        <li>The event may be cancelled by the Tax Faculty, without prior notice. In this circumstance the client has the option to attend the live webinar or watch the recording of the event online or transfer the booking to another event. In the case that the client does not want to take up any of the above options, only the attendance fees will be refunded.</li>
                        <li>The client shall not be entitled to receive a refund of the event fee except in circumstances where:
                            <br>
                            <ul type="circle">
                                <li>there was an overbooking by the Tax Faculty; or</li>
                                <li>the event was cancelled by the Tax Faculty.</li>
                            </ul>
                        </li>
                    </ul>

                    <div class="theme_color">
                        <p>Cancellations by the Client</p>
                    </div>

                    <ul>
                       <li>Clients wishing to cancel an event booking must send a written cancellation notice to <a href="mailto:events@taxfaculty.ac.za" class="theme_color" target="_blank">events@taxfaculty.ac.za</a></li>
                       <li>Telephonic cancellations will not be accepted.</li>
                       <li>No cancellation fee shall be levied by the Tax Faculty on cancellation of a booking for reasons of serious illness, hospitalisation or death.</li>
                    </ul>

                    <div class="theme_color">
                        <p>Paid Invoices</p>
                    </div>

                    <ul>
                        <li>Should a client cancel a confirmed booking, being a webinar or seminar, the Tax Faculty reserves the right to charge a cancellation fee, unless the event is cancelled within 10 (ten) working days of the original confirmation of the event.</li>
                        <li>All cancellations must be done in writing and sent via email to <a href="mailto:events@taxfaculty.ac.za" class="theme_color" target="_blank">events@taxfaculty.ac.za</a> from the email address the client registered with on the Tax Faculty website.</li>
                        <li>No telephonic cancellations will be accepted.</li>
                        <li>The following cancellation fees will be charged:
                            <br>
                            <ul type="circle">
                                <li><span class="bold">10 (ten) working days </span> before the event = 100 % refund of the paid invoice.</li>
                                <li><span class="bold">5 (five) working days</span> before the event = 75 % refund of the paid invoice.</li>
                                <li><span class="bold">3 (three) working days</span> before the event = 50 % refund of the paid invoice; and</li>
                                <li><span class="bold">Less than 3 (three) working days</span> before the event = no refund.</li>
                            </ul>
                        </li>
                        <li>Any refunds due will be made within 30 (thirty) days after written receipt of the cancellation of an event in writing.
                            <br>
                            <ul type="circle">
                                <li>Banking details of the account into which the refund must be paid must be sent via email to <a href="mailto:events@taxfaculty.ac.za" class="theme_color" target="_blank">events@taxfaculty.ac.za</a></li>
                            </ul>
                        </li>
                    </ul>

                    <div class="theme_color">
                        <p>Unpaid Invoices</p>
                    </div>

                    <ul>
                       <li>Upon confirmation of an event, the Tax Faculty reserves the right to charge a Penalty fee unless the event is cancelled within 3 (three) working days of the original confirmation of the event with a request in writing via email to <a href="mailto:events@taxfaculty.ac.za" class="theme_color" target="_blank">events@taxfaculty.ac.za</a> from the email address the client registered with on the Tax Faculty website.</li>
                       <li>If a client fails to cancel the booking as set out above, he/she will be liable for the full payment. Once paid, a recording of the event will automatically be made available via the client’s profile page as a courtesy from the Tax Faculty.</li>
                       <li>If a client has a CPD Subscription package and does not cancel the event as stipulated above, a penalty fee of R500.00 will be charged to cover admin and catering costs.</li>
                    </ul>
                </div>

                <div class="section">
                    <div class="title">
                        <p>Technical Resource Centre & CPD Subscription</p>
                    </div>

                    <ul>
                        <li>The Tax Faculty will provide the CPD subscription service to you on a pre-paid basis.</li>
                        <li>To have continued access to the service you must make, and we must receive, payment of your fees in advance.</li>
                        <li>If we do not receive payment of your fees, access to the service will be suspended. You will not have an option to book for an event/s.</li>
                        <li>If your access to the service is suspended, we will not reactivate your access until we have received full payment of your fees and legal action may be taken against you to recover all outstanding money.</li>
                        <li>The Tax Faculty will when it deems fit and economically justified, amend fees payable in respect to access to the service. We will notify you of such an amendment electronically via email as soon as practicable prior to implementing it, so that if you wish to terminate the subscription you may do so.</li>
                        <li>We may use payment systems or collection agencies owned and operated by third parties ("Payment System Providers") to facilitate the collection of fees, additional charges, and other amounts payable by you in respect of the service. None of these Payment System Providers is our employee, subcontractor, agent, intermediary or representative, or otherwise controlled by us.</li>
                        <li>You will be able to join all webinars through your profile page, please ensure you register for the webinars you would like to attend, if it’s streamed as a live event. Recordings of the webinars will be made available for viewing to all subscribers within 7 (seven) working days of the date of the webinar.</li>
                        <li>The CPD subscription entitles you to view recorded training sessions online. You have an option to also attend a selected number of events per year, as part of your package. </li>
                        <li>If you wish to attend a seminar in person, it is your responsibility to register for the event in advance so that we can advise the venue of our expected client numbers and to accommodate any dietary requirements. A registration fee of R390 will be applicable upon registration. Please notify us of any changes, cancellations (events) 3 (three) working days in advance in writing. If you do notify us 3 (three) working days in advance, a recording of the event and CPD assessment will automatically be made available via your profile page after the event has taken place. </li>
                        <li>We cannot guarantee that every seminar will be hosted at all venues and if the client is unable to attend an event or it is not available in his/her area, he/she has the option of attending a webinar for that event. If the date of the webinar is not convenient, then a recording of the event and CPD assessment will be made available. If an event in your area for which you have registered is cancelled, then the recording will be made available, unless you wish to attend the event in another area, in which case you must notify us electronically by emailing <a href="mailto:events@taxfaculty.ac.za" class="theme_color" target="_blank">events@taxfaculty.ac.za</a> at least 3 (three) working days prior to the event taking place.</li>
                        <li>If you subscribe for a CPD Subscription partway during the year, you will receive all the recordings relative to your CPD Subscription in the current month that you signed up in. Should you wish to claim CPD hours for past events you will need to purchase these directly. Kindly note that your CPD package is subject to change to keep updated with current and relevant accounting and tax topics as you continue with your CPD Subscription.</li>
                        <li>Once you have signed up you will receive access to your personal profile. All the CPD events (webinars/seminars) that happened during the month you signed up in will be automatically allocated to your profile. You will have instant access to your current CPD events and can start claiming your CPD hours.</li>
                    </ul>
    
                    <div class="theme_color">
                        <p>Subscription payment options:</p>
                    </div>
    
                    <ul>
                        <li><span class="bold">Yearly Subscription Plans: </span>
                            <br>
                            <ul type="circle">
                                <li>The yearly subscription plan service is offered as a 12-month service from date of purchase. </li>
                                <li>It is purchased and paid in a single once-off instalment. </li>
                                <li>Payment will therefore be due on the date of the invoice issued for the 12-month period of service via EFT, credit card or by instant EFT. </li>
                                <li>At the end of the 12-month period you will be given the opportunity to renew your subscription either on the same plan or on a different plan. </li>
                                <li>If you do not renew your subscription by the expiry date the subscription plan will automatically end. </li>
                                <li>Should you wish to continue with the yearly subscription plan, you must notify us electronically by emailing <a href="mailto:info@taxfaculty.ac.za" class="theme_color" target="_blank">info@taxfaculty.ac.za</a></li>
                                <li>Should you wish to cancel, please refer to conditions of the “subscription cancellation” below.</li>
                            </ul>
                        </li>
                    </ul>
    
                    <ul>
                        <li><span class="bold">Monthly Subscription Plans:  </span>
                            <br>
                            <ul type="circle">
                               <li>The service is offered on a continual monthly basis with no specified end date. Payments will therefore be monthly via credit card or by debit order only. </li>
                               <li>Should you wish to cancel, please refer to conditions of the “subscription cancellation” below.</li>
                            </ul>
                        </li>
                    </ul>
    
                    <ul>
                        <li><span class="bold">Black Friday Annual Subscription (special offer):  </span>
                            <br>
                            <ul type="circle">
                               <li>The effective date for this special offer to access the Resource centre content is the date reflecting on the invoice. </li>
                               <li>Should you wish to cancel prior to the effective date, please refer to conditions of the “subscription cancellation” below.</li>
                            </ul>
                        </li>
                    </ul>
    
                    <div class="theme_color">
                        <p>Subscription cancellation:</p>
                    </div>

                    <ul>
                        <li><span class="bold">Yearly  Subscription Plans:  </span>
                            <br>
                            <ul type="circle">
                              <li>If you wish to cancel your Yearly Subscription Plan after the cooling-off period (refer to “cooling-off period” section below) and prior to the anniversary of your subscription, you will not receive a refund, prorated or otherwise. You will continue to enjoy access to your online Tax Library & Resource Centre content for the remainder of the annual term.</li>
                            </ul>
                        </li>
                    </ul>

                    <ul>
                        <li><span class="bold">Monthly Subscription Plans:  </span>
                            <br>
                            <ul type="circle">
                               <li>If you wish to cancel your monthly CPD subscription, you must notify us in writing of your intention to terminate. A 27 (twenty-seven) day notice period will apply. Such termination will take effect from the last day of the month in which the termination notice is received, if the termination notice is received in the first 3 (three) days of beginning of the month taking into effect the notice period, or the last day of the next month if the termination notice is not received in time for cancellation of the same month. Cancellations received within the first 12 (twelve) months, will attract a cancellation charge equal to the difference calculated for the months already paid and the normal annual subscription charge for the service.</li>
                            </ul>
                        </li>
                    </ul>

                    <ul>
                        <li><span class="bold">Such termination will take effect from:</span>
                            <br>
                            <ul type="circle">
                               <li>The last day of the month in which the termination notice is received, if the termination notice is received in the first 3 (three) days of beginning of the month taking into effect the notice period; or</li>
                               <li>The last day of the next month if the termination notice is not received in time for cancellation of the same month.</li>
                            </ul>
                        </li>
                    </ul>

                </div>

                <div class="section">
                    <div class="title">
                        <p>Cooling-off Period:</p>
                    </div>

                    <ul>
                        <li>All payments by means of an electronic transaction shall be subject to section 44 (cooling-off period) of the Electronic Communications and Transactions Act No 25 of 2002, as amended. As such, clients shall be entitled to cancel this agreement without reason or penalty within 7 (seven) days after the date of an event booking.</li>
                        <li>If the cooling-off period under clause 8.1 does not apply, and a person is a consumer protected by the Consumer Protection Act No 68 of 2008 and the person makes a booking as a result of direct marketing, then that person shall be entitled (under section 16 (consumer's right to cooling-off period after direct marketing) of the Consumer Protection Act) to cancel this agreement within 5 (five) business days after the date of the booking by writing and submitting a detailed explanation, with full contact details to <a href="mailto:info@taxfaculty.ac.za" class="theme_color" target="_blank">info@taxfaculty.ac.za</a>.</li>
                    </ul>
                </div>
                
                <div class="section">
                    <div class="title">
                        <p>Substitutions</p>
                    </div>

                    <ul>
                        <li>Clients are entitled to substitute bookings that have been confirmed.</li>
                        <li>Only written substitution requests will be considered.</li>
                        <li>Telephonic requests for substitution will not be accepted.</li>
                        <li>A request for substituting a client for another must be submitted to The Tax Faculty in writing 24 (twenty-four) hours before the date of the event.</li>
                        <li>No late substitution requests will be considered.</li>
                        <li>Requests for substitution must be sent to <a href="mailto:info@taxfaculty.ac.za" class="theme_color" target="_blank">info@taxfaculty.ac.za</a></li>
                    </ul>
                </div>

                <div class="section">
                    <div class="title">
                        <p>Transfers</p>
                    </div>

                    <ul>
                        <li>Requests for the transfer of a booking to a different event venue or date ('Transfer') must be made in writing to the Tax Faculty.</li>
                        <li>Requests for transfers must be sent to info@taxfaculty.ac.za</li>
                        <li>Telephonic transfers will not be accepted.</li>
                        <li>No charges will be levied for a transfer unless a request for a transfer is received 5 working days before the event. Any Transfer requests thereafter will not be accepted.</li>
                    </ul>
                </div>

                <div class="section">
                    <div class="title">
                        <p>Limitation of Liability</p>
                    </div>

                    <ul>
                        <li>In no event shall The Tax Faculty be liable for any loss of contracts, profits, anticipated savings, revenue, goodwill, business, loss or corruption of data or software programs, financing expenses, interruption in the use or availability of data, stoppage to other work or consequential losses, nor for any indirect losses.</li>
                        <li>The Tax Faculty and the Client hereby indemnify each other against damage to tangible property, whether personal or real, and death or injury to persons to the extent caused by the negligence of the other party.</li>
                        <li>The Client understands and accepts that The Tax Faculty consistently develops its training courses in line with evolving best practices. The Tax Faculty reserves the right to make changes to the published and/or advertised course content in connection with its training services without prior notice to the Client.</li>
                    </ul>
                </div>

                <div class="section">
                    <div class="title">
                        <p>Intellectual Property</p>
                    </div>

                    <ul>
                        <li>All intellectual property rights, including copyright, patents and design arising in connection with this Agreement shall belong to and remain vested in the Tax Faculty and the client shall execute any document necessary for this purpose.</li>
                    </ul>
                </div>

                <div class="section">
                    <div class="title">
                        <p>Force Majeure</p>
                    </div>

                    <ul>
                        <li>Neither party shall be responsible for any failure or delay in performance of its obligations under this Agreement (other than the obligation to make payments of money) due to any force majeure event including, Act of God, fire, explosion, embargo, terrorism, civil disturbance, accident, epidemics, lightning damage, electromagnetic interference, radio interference, strikes, industrial dispute, or any other cause beyond its reasonable control.</li>
                        <li>In the unlikely event of the above, The Tax Faculty will endeavor to reschedule at a mutually convenient date and/ or venue and The Tax Faculty will not be liable for any loss or expenses caused to the Client.</li>
                    </ul>
                </div>

                <div class="section">
                    <div class="title">
                        <p>Domicilium</p>
                    </div>

                    <ul>
                        <li>The Client designates the address furnished by him/her in their online profile as its domiciliumcitandi et executandi ("domicilium") for any notice/s, the serving of any process and for any other purposes arising from this Agreement.</li>
                        <li>The Tax Faculty designates the address Riverwalk Office Park, 41 Matroosberg road, Block A,Ground floor, Ashley Gardens, Pretoria as its domiciliumcitandi et executandi ("domicilium") for any notice/s, the serving of any process and for any other purposes arising from this Agreement.</li>
                    </ul>
                </div>

                <div class="section">
                    <div class="title">
                        <p>Entire agreement</p>
                    </div>

                    <ul>
                       <li>This Agreement sets out the entire agreement between the parties in relation to the subject matter hereof and supersedes all previous arrangements, agreements, and representations whether written, oral or implied between the Client and The Tax Faculty relating to the Services.</li>
                       <li>Any amendments to this Agreement shall be in writing and signed by both parties.</li>
                    </ul>
                </div>

                <div class="section">
                    <div class="title">
                        <p>Governing Law and Jurisdiction</p>
                    </div>

                    <ul>
                       <li>The Parties agree that the validity and interpretation of this Agreement will be governed by the laws of the Republic of South Africa.</li>
                       <li>The parties agree to the jurisdiction of the Magistrate’s Court in relation to any legal proceedings which may result from the Agreement, provided that The Tax Faculty is entitled in its discretion to institute any such legal proceedings in any other competent court.</li>
                    </ul>
                </div>

                <div class="section">
                    <div class="title">
                        <p>Costs</p>
                    </div>

                    <ul>
                       <li>In the event of either party breaching any obligation under this agreement and the aggrieved party deeming it necessary to engage the services of a registered debt collector to recover any payments which may be due or payable, the infringing party shall be liable for:
                           <br>
                           <ul type="circle">
                                <li>Tracing agent fees (if required).</li>
                                <li>Fees, disbursements, and expenses to which the debt collector is entitled in terms of the Debt Collectors Act.</li>
                                <li>Collection Commission will be charged on an instalment paid to the debt collector or paid directly to the aggrieved party following handover of the matter to the debt collector, provided that the collection commission charged shall not exceed the statutorily prescribed maximum amount.</li>
                            </ul>
                       </li>
                       <li>In the event of either party breaching any obligation under this agreement and the aggrieved party deeming it necessary to engage the services of an attorney to enforce his/her rights (including the right to receive payment), the infringing party shall be liable for:
                           <br>
                           <ul type="circle">
                                <li>Tracing agent fees (if required).</li>
                                <li>The attorney’s costs on an attorney and own client scale.</li>
                                <li>Collection Commission will be charged on an instalment paid to the attorney or paid directly to the aggrieved party following handover of the matter to the attorney, provided that the collection commission charged shall not exceed the statutorily prescribed maximum amount.</li>
                            </ul>
                       </li>
                       <li>The aggrieved party’s attorney or debt collector (as the case may be) shall on receiving a payment from the infringing party, have the right to allocate such payment firstly towards disbursements incurred by the attorney or debt collector, secondly towards fees to which the attorney or debt collector is legally entitled, thirdly towards interest due to the aggrieved party and finally towards the capital amount due to the aggrieved party.</li>
                    </ul>
                </div>

                <div class="section">
                    <div class="title">
                        <p>PI Insurance</p>
                    </div>

                    <ul>
                        <li>The Tax Faculty is not a Financial Service Provider (FSP) and does not provide financial advice. Any information provided regarding Professional Indemnity Insurance (PI) is merely a description and should not be understood as advice. The Tax Faculty will refer all PI Insurance matters to the Insurance Broker.</li>
                    </ul>
                </div>

                <div class="section">
                    <div class="title">
                        <p>Privacy Policy</p>
                    </div>

                    <ul>
                        <li><a href="{{ route('privacy_policy') }}" class="aqua" target="_blank">Click here to read our full Privacy Policy</a></li>
                    </ul>
                </div>

                <div class="section">
                    <div class="title">
                        <p>You authorize us, subject to any applicable laws, to – </p>
                    </div>

                    <ul>
                        <li>Access from credit bureau who are members of the Credit Bureau Association and subscribe to its Code of Conduct ("credit bureau") your personal information concerning financial risk and payment habits ("payment profile") for purposes of fraud prevention and debtor tracing, and to disclose information regarding your payment profile to such credit bureau.</li>
                        <li>Obtain capture, store, analyse and use for our marketing purposes your viewing habits and profile.</li>
                        <li>Use information that we may have in relation to you for the purposes of:
                            <br>
                            <ul type="circle">
                                <li>Processing your request.</li>
                                <li>Administering the service.</li>
                                <li>Informing you of any new aspects of the service or services provided by our affiliates.</li>
                                <li>Informing you of promotional competitions; and</li>
                                <li>Notifying you of a General Amendment.</li>
                            </ul>
                        </li>
                        <li>Disclose your personal information:
                            <br>
                            <ul type="circle">
                                <li>To companies affiliated with us for purposes of marketing their services (subject to your right to refuse such disclosure of your personal information).</li>
                                <li>To any company which acquires our business or any part thereof, or which we acquire.</li>
                                <li>To agents, representatives, or service providers which we appoint to process your request, administer the service, or provide customer management services.</li>
                                <li>To our payment system providers to facilitate the collection of your fees using payment systems owned and operated by third parties; or</li>
                                <li>If, and to the extent that we are required to do so, to comply with any Applicable Law; and</li>
                                <li>Retain your personal information referred to in this clause for as long as we are required to do so in terms of Applicable Laws or to exercise or protect any of our rights.</li>
                            </ul>
                        </li>
                        <li>It is your responsibility to ensure that the information which you provide to us is complete, accurate and up to date</li>
                    </ul>
                </div>

            </div>
        </div>
        {{-- <div class="row">
            <div class="col-md-12">
                <p><b>STANDARD TERMS AND CONDITIONS OF BUSINESS</b></p>
                <p><b>1. INTRODUCTION</b></p>
                <p>
                    These terms and conditions will apply to all educational services (including bookings, engagements with, attendance or use of a seminar, conference, workshop, CPD subscription service, online learning services, online profile or other similar service or product hosted, offered, organised or to be hosted, offered, or organised by The Tax Faculty NPC or any of its representatives and marketed as such ('the service' or 'the event').
                </p>
                <p><b>2.	DEFINITIONS</b></p>
                <p>
                    2.1.	“Agreement” means these terms and conditions, the Invoice, the Booking Form, and the Client’s acceptance of the Booking Form.
                    <br>
                    2.2.	“Profile” means Internet Identity with The Tax Faculty.<br>
                    2.3.	The Tax Faculty, a non-profit company with registration number 2007/020344/08 and principal place of business at 41 MatroosbergRoad, Pretoria, 0081.<br> --}}
                    {{-- 2.4.	“the Client” means a person or company who uses the services of {{ config('app.name') }}. <br>
                    2.5.	“Personal Data” means the data which relates to a natural person who can be identified from that data or from that data and other information and which is provided to {{ config('app.name') }} by the Client. <br>
                    2.6.	“CPD Subscription” means monthly/annual continuous professional development subscription package <br> --}}
                {{-- </p>
                <p><b>3.	BOOKINGS, CONFIRMATION AND EFFECTIVE DATE </b></p>
                <p>
                    3.1.	All clients are required to make a booking online for any required service or event hosted. <br>
                    3.2.	Once a booking has been made, an invoice will be automatically generated and sent to the client. <br>
                    3.3.	The contract of service ('the booking') will be in force and deemed to be effective once payment has been made to and received by The Tax Faculty. <br>
                    3.4.	If a client has booked an event but does not receive a confirmation 7 (seven) working days prior to the Event date, it is the responsibility of the client to contact The Tax Faculty on 012 943 7002 or <a href="mailto:system@taxfaculty.ac.za" class="aqua">system@taxfaculty.ac.za</a> and follow-up on the booking. <br>
                    3.5.	Live training event registration closes 24 (twenty-four) hours prior to each event. Late registrations shall only be permitted if the client has made prior arrangements with <a href="mailto:system@taxfaculty.ac.za" class="aqua">system@taxfaculty.ac.za</a>. <br>
                    3.6.	Venues, times, topics, presenters, programme offerings, and online schedules are subject to change without notice and are determined solely at the discretion of The Tax Faculty. <br>
                </p>
                <p><b>4.	EFT PAYMENTS</b></p>
                <p>
                    4.1.	Payment for an event can be made in any of the following manner: <br>
                    4.1.1.	by electronic funds transfer (EFT); <br>
                    4.1.2.	direct cash deposit to the bank account of {{ config('app.name') }}; or <br>
                    4.1.3.	by credit/debit card using the payment facility on the {{ config('app.name') }} website. <br>
                    4.2.	Payment for bookings must be made no later than 48 (forty-eight) hours prior to the event. <br>
                    4.3.	The price of the event will be the price displayed on the event page. The price is inclusive of Value-Added Tax (VAT). <br>
                    4.4.	No client will be permitted entrance to the event without proof of payment having been received in advance or presented to an {{ config('app.name') }} staff member on the day. For late registrations, full payment must be made at the venue via SnapScan. No cash, cheque, credit or debit card payments will be accepted. <br>
                    4.5.	EFT Payments <br>
                    4.5.1.	It is the responsibility of the client to verify the banking details of the Tax Faculty. <br>
                    4.5.1.1.	In order to verify the bank details please contact accounts@taxfaculty.ac.za. <br>

                    4.5.2.	Once a payment has been made, clients must submit proof of same, clearly stating the invoice number that appears on the invoice. <br>
                    4.5.3.	Proof of payment should be emailed to system@taxfaculty.ac.za  <br>
                    4.6.	Credit Card Payments <br>
                    4.6.1.	You will receive notification that your payment was successful. If, for whatever reason, the credit/debit card payment failed, you will also be notified and will be instructed on how to proceed. <br>
                </p>
                <p><b>5.	CANCELLATIONS AND REFUNDS</b></p>
                <p>
                    <b>5.1.	Event Cancellations by {{ config('app.name') }} </b><br>
                    5.1.1.	The event may be cancelled by {{ config('app.name') }}, without prior notice. In this circumstance the client has the option to attend the live webinar or watch the recording of the event online or transfer the booking to another event. In the case that the client does not want to take up any of the above options, only the attendance fees will be refunded. <br>
                    5.1.2.	The client shall not be entitled to receive a refund of the event fee except in circumstances where: <br>
                    5.1.2.1.	there was an overbooking by {{ config('app.name') }}; or <br>
                    5.1.2.2.	the Event was cancelled by {{ config('app.name') }}. <br><br>
                    <b>5.2.	Cancellations by the Client </b><br>
                    5.2.1.	Clients wishing to cancel an event booking must send a written cancellation notice to {{ config('app.email') }} <br>
                    5.2.2.	Telephonic cancellations will not be accepted. <br>
                    5.2.3.	No cancellation fee shall be levied by {{ config('app.name') }} on cancellation of a booking for reasons of serious illness, hospitalisation or death <br><br>
                    <b> 5.3.	Paid Invoices </b><br>
                    5.3.1.	Should a client cancel a confirmed booking, being a webinar or Seminar, {{ config('app.name') }} reserves the right to charge a cancellation fee, unless the event is cancelled within 10 (ten) working days of the original confirmation of the event. <br>
                    5.3.2.	All cancellations must be done in writing and sent via email to {{ config('app.email') }} from the email address the client registered with on the {{ config('app.name') }} website.<br>
                    5.3.3.	No telephonic cancellations will be accepted.<br>
                    5.3.4.	The following cancellation fees will be charged:<br>
                    5.3.4.1.	<b>10 (ten) working days</b> before the event = 100 % refund of the paid invoice;<br>
                    5.3.4.2.	<b>5 (five) working days</b> before the event = 75 % refund of the paid invoice;<br>
                    5.3.4.3.	<b>3 (three) working days</b> before the event = 50 % refund of the paid invoice; and<br>
                    5.3.4.4.	<b>Less than 3 (three) working days</b> before the event = no refund.<br>
                    5.3.5.	Any refunds due will be made within 30 (thirty)working days after written receipt of the cancellation of an event.<br>
                    5.3.6.	Banking details of the account into which the refund must be paid must be sent via email to  {{ config('app.email') }}<br><br>
                    <b>  5.4.	Unpaid Invoices</b><br>
                    5.4.1.	Upon confirmation of an event, {{ config('app.name') }} reserves the right to charge a Penalty fee unless the event is cancelled within 3 (three) working days of the original confirmation of the event with a request in writing via email to {{ config('app.email') }} from the email address the client registered with on the {{ config('app.name') }} website.<br>
                    5.5.	If a client fails to cancel the booking as set out above, he/she will be liable for the full payment. Once paid, a recording of the event will automatically be made available via the client’s profile page as a courtesy from {{ config('app.name') }}.<br>
                    5.6.	If a client has a CPD Subscription package and does not cancel the event as stipulated above, a penalty fee of R500.00 will be charged to cover admin and catering costs.<br>
                </p>
                <p><b>6.	Technical Resource Centre & CPD Subscription</b></p>
                <p>
                    6.1.	{{ config('app.name') }} will provide the CPD subscription service to you on a pre-paid basis. <br>
                    6.2.	To have continued access to the service you must make, and we must receive, payment of your fees in advance. <br>
                    6.3.	If we do not receive payment of your fees, access to the service will be suspended. You will not have an option to book for an event/s. <br>
                    6.4.	If your access to the service is suspended, we will not reactivate your access until we have received full payment of your fees and legal action may be taken against you to recover all outstanding money.<br>
                    6.5.	We may, from time to time, amend the fees payable in respect of access to the service. We will notify you of such an amendment electronically via email as soon as practicable prior to implementing it, so that if you wish to terminate the subscription you may do so.<br>
                    6.6.	We may use payment systems or collection agencies owned and operated by third parties ("Payment System Providers") to facilitate the collection of fees, additional charges and other amounts payable by you in respect of the service. None of these Payment System Providers is our employee, subcontractor, agent, intermediary or representative, or otherwise controlled by us.<br>
                    6.7.	Subscription payment options <br>
                    6.7.1. <b>Yearly Subscription Plans:</b> The yearly subscription plan service is offered as a 12-month specific period service. It is purchased and paid in a single once-off instalment. Payment will therefore be due on the date of registration for the 12-month prescribed period of service via EFT, credit card or by debit order. At the end of the 12-month period you will be given the opportunity to renew your subscription either on the same plan or on a different plan. If you do not renew your subscription by the expiry date the subscription plan will be automatically converted to a monthly subscription plan. Should you wish to continue with the Yearly Subscription Plan option, you must notify us electronically by emailing <a href="mailto:system@taxfaculty.ac.za" class="aqua">system@taxfaculty.ac.za</a> at least 1 month before the anniversary date. Please refer to section 6.13 and 6.14 below for conditions applicable to cancellations.<br>
                    6.7.2. <b>Monthly Subscription Plans:</b> The service is offered on a continual monthly basis with no specified end date. Payments will therefore be on a monthly basis via credit card or by debit order only. Should you wish to cancel, please refer to section 6.13 and 6.14 below.<br>
                    6.7.3. <b>Black Friday Annual Subscription (special offer):</b> The effective date for this special offer to access the Resource centre content is 1 January. Should you wish to cancel prior to the effective date, please refer to conditions of section 6.13 and 6.14 below.<br>
                    6.8.	You will be able to join all webinars through your profile page, please ensure you register for the webinars you would like to attend, if its streamed as a live event. Recordings of the webinars will be made available for viewing to all subscribers within 7 (seven) working days of the date of the webinar.<br>
                    6.9.	The CPD Subscription entitles you to view recorded training sessions online. You have an option to also attend a selected number of events per year, as part of your package. If you wish to attend a seminar in person, it is your responsibility to register for the event in advance so that we can advise the venue of our expected client numbers and to accommodate any dietary requirements. Please notify us of any changes, cancellations (events) 3 (three) working days in advance in writing. If not, a penalty fee of R500.00 will be charged. If you do notify us 3 (three) working days in advance, a recording of the event and CPD assessment will automatically be made available via your profile page after the event has taken place. {{ config('app.name') }} cannot guarantee that you will be allocated a seat to an event. Events are seated on a first come first serve basis.<br>
                    6.10.	We cannot guarantee that every seminar will be hosted at all venues and if the client is unable to attend an event or it is not available in his/her area, he/she has the option of attending a webinar for that event. If the date of the webinar is not convenient, then a recording of the event and CPD assessment will be made available as per clause 7.9 above. If an event in your area for which you have registered is cancelled, then the recording will be made available as per clause 7.9. above, unless you wish to attend the event in another area, in which case you must notify us electronically by emailing {{ config('app.email') }} at least 3 (three) working days prior to the event taking place.<br>
                    6.11.	If you subscribe for a CPD Subscription partway during the year, you will receive all the recordings relative to your CPD Subscription in the current month that you signed up in.  Should you wish to claim CPD hours for past events you will need to purchase these directly. Kindly note that your CPD package is subject to change to keep updated with current and relevant accounting and tax topics as you continue with your CPD Subscription.<br>
                    6.12.	Once you have signed up you will receive access to your personal profile. All the CPD events (webinars/seminars) that happened during the month you signed up in will be automatically allocated to your profile. You will have instant access to your current CPD events and can start claiming your CPD hours.<br>
                    6.13.	Cancellation:<br>
                    6.13.1.	<b>Yearly Subscription Plans:</b> If you wish to cancel your Yearly Subscription Plan after the cooling-off period (refer paragraph 7) and prior to the anniversary of your subscription, you will not receive a refund, prorated or otherwise. You will continue to enjoy access to your online Tax Library & Resource Centre content for the remainder of the annual term. .<br>
                    6.13.2.	<b>Monthly Subscription Plans:</b> If you wish to cancel your monthly CPD subscription, you must notify us in writing of your intention to terminate. A 27 (twenty-seven) day notice period will apply. Such termination will take effect from the last day of the month in which the termination notice is received, if the termination notice is received in the first 3 (three) days of beginning of the month taking into effect the notice period, or the last day of the next month if the termination notice is not received in time for cancellation of the same month. Cancellations received within the first  12 (twelve) months, will attract a cancellation charge equal to the difference calculated for the months already paid and the normal annual subscription charge for the service..<br>
                    6.14.	Such termination will take effect from:<br>
                    6.14.1.	The last day of the month in which the termination notice is received, if the termination notice is received in the first 3 (three) days of beginning of the month taking into effect the notice period; or<br>
                    6.14.2.	The last day of the next month if the termination notice is not received in time for cancellation of the same month.<br>
                </p>
                <p><b>7.	COOLING-OFF PERIOD</b></p>
                <p>
                    7.1.	All payments by means of an electronic transaction shall be subject to section 44 (cooling-off period) of the Electronic Communications and Transactions Act No 25 of 2002, as amended. As such, clients shall be entitled to cancel this agreement without reason or penalty within 7 (seven) days after the date of an event booking. <br>
                    7.2.	If the cooling-off period under clause 8.1 does not apply, and a person is a consumer protected by the Consumer Protection Act No 68 of 2008 and the person makes a booking as a result of direct marketing, then that person shall be entitled (under section 16 (consumer's right to cooling-off period after direct marketing) of the Consumer Protection Act) to cancel this agreement within 5 (five) business days after the date of the booking by writing and submitting a detailed explanation, with full contact details to {{ config('app.email') }}. <br>
                </p>
                <p><b>8.	SUBSTITUTIONS</b></p>
                <p>
                    8.1.	Clients are entitled to substitute bookings that have been confirmed.<br>
                    8.2.	Only written substitution requests will be considered.<br>
                    8.3.	Telephonic requests for substitution will not be accepted.<br>
                    8.4.	A request for substituting a client for another must be submitted to {{ config('app.name') }} in writing 24 (twenty-four) hours before the date of the event.<br>
                    8.5.	No late substitution requests will be considered.<br>
                    8.6.	Requests for substitution must be sent to {{ config('app.email') }}<br>
                </p>
                <p><b>9.	TRANSFERS</b></p>
                <p>
                    9.1.	Requests for the transfer of a booking to a different event venue or date ('Transfer') must be made in writing to {{ config('app.name') }}.<br>
                    9.2.	Requests for transfers must be sent to {{ config('app.email') }}<br>
                    9.3.	Telephonic transfers will not be accepted.<br>
                    9.4.	No charges will be levied for a transfer unless a request for a transfer is received 5 working days before the event. Any Transfer requests thereafter will not be accepted.<br>
                </p>
                <p><b>10.	LIMITATION OF LIABILITY</b></p>
                <p>
                    10.1.	In no event shall {{ config('app.name') }} be liable for any loss of contracts, profits, anticipated savings, revenue, goodwill, business, loss or corruption of data or software programs, financing expenses, interruption in the use or availability of data, stoppage to other work or consequential losses, nor for any indirect losses.<br>
                    10.2.	{{ config('app.name') }} and the Client hereby indemnify each other against damage to tangible property, whether personal or real, and death or injury to persons to the extent caused by the negligence of the other party.<br>
                    10.3.	The Client understands and accepts that {{ config('app.name') }} consistently develops its training courses in line with evolving best practices.  {{ config('app.name') }} reserves the right to make changes to the published and/or advertised course content in connection with its training services without prior notice to the Client.<br>
                </p>
                <p><b>11.	INTELLECTUAL PROPERTY</b></p>
                <p>
                    All intellectual property rights, including copyright, patents and design arising in connection with this Agreement shall belong to and remain vested in {{ config('app.name') }} and the Client shall execute any document necessary for this purpose.
                </p>
                <p><b>12.	FORCE MAJEURE</b></p>
                <p>
                    12.1.	Neither party shall be responsible for any failure or delay in performance of its obligations under this Agreement (other than the obligation to make payments of money) due to any force majeure event including, Act of God, fire, explosion, embargo, terrorism, civil disturbance, accident, epidemics, lightning damage, electromagnetic interference, radio interference, strikes, industrial dispute, or any other cause beyond its reasonable control.<br>
                    12.2.	In the unlikely event of the above, {{ config('app.name') }} will endeavour to reschedule at a mutually convenient date and/ or venue and {{ config('app.name') }} will not be liable for any loss or expenses caused to the Client.<br>
                </p>
                <p><b>13.	DOMICILIUM</b></p>
                <p>
                    13.1.	The Client designates the address furnished by him/her in their online profile as its domicilium citandi et executandi ("domicilium") for any notice/s, the serving of any process and for any other purposes arising from this Agreement.<br>
                    13.2.	{{ config('app.name') }} designates the address Broadacres Business Centre as its domicilium citandi et executandi ("domicilium") for any notice/s, the serving of any process and for any other purposes arising from this Agreement.<br>
                </p>
                <p><b>14.	ENTIRE AGREEMENT</b></p>
                <p>
                    14.1.	This Agreement sets out the entire agreement between the parties in relation to the subject matter hereof and supersedes all previous arrangements, agreements and representations whether written, oral or implied between the Client and {{ config('app.name') }} relating to the Services. <br>
                    14.2.	Any amendments to this Agreement shall be in writing and signed by both parties. <br>
                </p>
                <p><b>15.	GOVERNING LAW AND JURISDICTION</b></p>
                <p>
                    15.1.	The Parties agree that the validity and interpretation of this Agreement will be governed by the laws of the Republic of South Africa.<br>
                    15.2.	The parties agree to the jurisdiction of the Magistrate’s Court in relation to any legal proceedings which may result from the Agreement, provided that {{ config('app.name') }} is entitled in its discretion to institute any such legal proceedings in any other competent court. <br>
                </p>
                <p><b>16.	COSTS</b></p>
                <p>
                    16.1.	In the event of either party breaching any obligation under this agreement and the aggrieved party deeming it necessary to engage the services of a registered debt collector to recover any payments which may be due or payable, the infringing party shall be liable for:<br>
                    16.1.1.	Tracing agent fees (if required);<br>
                    16.1.2.	Fees, disbursements and expenses to which the debt collector is entitled in terms of the Debt Collectors Act;<br>
                    16.1.3.	Collection Commission will be charged on an instalment paid to the debt collector or paid directly to the aggrieved party following handover of the matter to the debt collector, provided that the collection commission charged shall not exceed the statutorily prescribed maximum amount.<br>
                    16.2.	In the event of either party breaching any obligation under this agreement and the aggrieved party deeming it necessary to engage the services of an attorney to enforce his/her rights (including the right to receive payment), the infringing party shall be liable for:<br>
                    16.2.1.	Tracing agent fees (if required);<br>
                    16.2.2.	The attorney’s costs on an attorney and own client scale;<br>
                    16.2.3.	Collection Commission will be charged on an instalment paid to the attorney or paid directly to the aggrieved party following handover of the matter to the attorney, provided that the collection commission charged shall not exceed the statutorily prescribed maximum amount.<br>
                    16.3.	The aggrieved party’s attorney or debt collector (as the case may be) shall on receiving a payment from the infringing party, have the right to allocate such payment firstly towards disbursements incurred by the attorney or debt collector, secondly towards fees to which the attorney or debt collector is legally entitled, thirdly towards interest due to the aggrieved party and finally towards the capital amount due to the aggrieved party.<br>
                </p>
                <p><b>17.	PI INSURANCE</b></p>
                <p>
                    {{ config('app.name') }} is not a Financial Service Provider (FSP) and does not provide financial advice. Any information provided regarding Professional Indemnity Insurance (PI) is merely a description and should not be understood as advice. {{ config('app.name') }} will refer all PI Insurance matters to the Insurance Broker.
                </p>
                <p><b>18.	PRIVACY POLICY</b></p>
                <p> --}}
                    {{--  18.1.	{{ config('app.name') }} collect’s no personal information about you when you visit this website, except where otherwise stated, unless you choose to provide this information to us. However, we collect, and store certain information automatically as follows:<br>
                    18.2.	The Internet Protocol (IP) address and the name of the host from which you access the Internet, the browser type and version you are using to access the site, the operating system and version you are running on your machine, the date and time you access our site, the pages you peruse, and the Internet address of the website from which you linked directly to our site.<br>
                    18.3.	Visitors to the {{ config('app.name') }} website have no explicit or implicit expectation of privacy. The website uses software programs to monitor network traffic to identify unauthorised attempts to change, delete information or otherwise cause damage. Any or all visits to the {{ config('app.name') }} website are subject to these conditions.<br>
                    18.4.	We use the summary statistics to help us make our site more useful to visitors (such as assessing what information is of the most and least interest to visitors) and for other purposes such as determining the site's technical design specifications and identifying system performance or problem areas.<br>
                    18.5.	This information is NOT shared with anyone beyond the support staff for the {{ config('app.name') }} website, except as required for site security purposes, to ensure that the {{ config('app.name') }} website remains available to all users and when required by law enforcement investigation. We use the information only as a source of anonymous statistical information and no other attempts are made to identify client users or their usage habits.<br>  --}}
                    {{-- <a target="_blank" href="{{ '/privacy_policy' }}">Click here to read our full Privacy Policy</a>
                </p>
                <p><b>19.	YOU AUTHORISE US, SUBJECT TO ANY APPLICABLE LAWS, TO –</b></p>
                <p>
                    19.1.	 Access from credit bureau who are members of the Credit Bureau Association and subscribe to its Code of Conduct ("credit bureau") your personal information concerning financial risk and payment habits ("payment profile") for purposes of fraud prevention and debtor tracing, and to disclose information regarding your payment profile to such credit bureau; <br>
                    19.2.	Obtain capture, store, analyse and use for our marketing purposes your viewing habits and profile;<br>
                    19.3.	Use information that we may have in relation to you for the purposes of:<br>
                    19.3.1.	 Processing your request;<br>
                    19.3.2.	Administering the service;<br>
                    19.3.3.	Informing you of any new aspects of the service or services provided by our affiliates;<br>
                    19.3.4.	Informing you of promotional competitions; and<br>
                    19.3.5.	Notifying you of a General Amendment;<br>
                    19.4.	Disclose your personal information:<br>
                    19.4.1.	To companies affiliated with us for purposes of marketing their services (subject to your right to refuse such disclosure of your personal information);<br>
                    19.4.2.	To any company which acquires our business or any part thereof, or which we acquire;<br>
                    19.4.3.	To agents, representatives or service providers which we appoint to process your request, administer the service or provide customer management services;<br>
                    19.4.4.	To our payment system providers in order to facilitate the collection of your fees using payment systems owned and operated by third parties; or<br>
                    19.4.5.	If, and to the extent that we are required to do so, to comply with any Applicable Law; and<br>
                    19.4.6.	Retain your personal information referred to in this clause for as long as we are required to do so in terms of Applicable Laws or in order to exercise or protect any of our rights.<br>
                    19.5.	It is your responsibility to ensure that the information which you provide to us is complete, accurate and up to date<br>
                </p>
            </div>
        </div> --}}
    </div>
</section>

@endsection