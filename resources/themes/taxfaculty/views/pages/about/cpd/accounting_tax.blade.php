@extends('app')

@section('content')

@section('title')
    CPD Subscription Accounting And Tax
@stop

@section('intro')
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('CPD') !!}
@stop
<section>
    <div class="container">

        <h3>CPD Subscription 2017</h3>
        <p>The convenient and affordable way to keep your technical knowledge up to date</p>
        <hr>
        <h4>Why CPD?</h4>
        <p>
            Make sure that your CPD hours are taken care of in 2017 by subscribing to our highly cost-effective annual
            subscription package. This year you have even more flexibility as we are including 3 different package
            options according to your specific role and responsibilities. But if none of these packages is appropriate
            we are also offering the option to ‘build your own’ package of eight seminars that are most appropriate for
            you. All packages also include monthly webinars (accounting and/or tax related topics, depending on which
            option you choose).
        </p>

        <p>
            You will be able to view the recordings of all seminars and webinars from our online platform, so that you
            can watch them at your own convenience. This facility is available to both individuals and firms and is
            available for 12 months.
        </p>

        <hr>
        <h3>What do you get as CPD Subscriber?</h3>
        <ul>
            <li>Access to a selected number of face-to-face seminars and webinars</li>
            <li>12 months’ unlimited access to all event, webinar and conference recordings</li>
            <li>Online profile to manage all your CPD events</li>
            <li>Automated management of CPD points and CPD certificates</li>
            <li>Topics that focus on both compliance and the performance of your accounting firm </li>
            <li><strong>New for 2017!</strong> Personal Indemnity insurance included at no extra cost</li>
        </ul>

        <p><strong>Option One: Bookkeeper/Junior Accountant</strong></p>
        <table class="table table-bordered table-responsive">
            <thead>
            <th>Accounting</th>
            <th>Tax</th>
            <th>CPD hours</th>
            </thead>
            <tbody>
            <tr>
                <td width="400">Accounting Cycle</td>
                <td width="400">Small businesses</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td>From Trial Balance to Financial Statement </td>
                <td>Individual Tax Return</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td></td>
                <td>Year-end Tax Update</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td></td>
                <td>Budget & Tax Update</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td></td>
                <td>Employees & Provisional Tax/Employees & Payroll Withholding</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td>Monthly accounting update webinar</td>
                <td></td>
                <td>10</td>
            </tr>
            <tr>
                <td>Monthly tax update webinar</td>
                <td></td>
                <td>20</td>
            </tr>
            <tr>
                <td>Monthly Tax Administration Act</td>
                <td></td>
                <td>20</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right;"><strong>TOTAL CPD HOURS</strong></td>
                <td>78</td>
            </tr>
            </tbody>
        </table>

        <p><strong>Option Two: Accounting Officer</strong></p>
        <table class="table table-bordered table-responsive">
            <thead>
            <th>Accounting</th>
            <th>Tax</th>
            <th>CPD hours</th>
            </thead>
            <tbody>
            <tr>
                <td width="400">Accounting Officer Engagements</td>
                <td width="400">Budget & Tax Update</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td>CIPC Update </td>
                <td>Tax Administration Act/ SARS Objections, Appeals & Dispute Resolution</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td>Trust Law Update</td>
                <td>Tax Planning for Trusts & Deceased Estates</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td>Business Rescue</td>
                <td>Specialised VAT Issues</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td>IFRS for SMEs Update</td>
                <td>Entrepreneurial & Medium Enterprises</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td>Preparing Financial Statements for IFRS for SMEs</td>
                <td>Year-end Tax Update</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td>Monthly accounting webinar (various topics)</td>
                <td></td>
                <td>20</td>
            </tr>
            <tr>
                <td>Monthly tax update webinar</td>
                <td></td>
                <td>20</td>
            </tr>
            <tr>
                <td>Monthly accounting update webinar</td>
                <td></td>
                <td>10</td>
            </tr>
            <tr>
                <td>Monthly practice management webinar</td>
                <td></td>
                <td>10</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right;"><strong>TOTAL CPD HOURS</strong></td>
                <td>108</td>
            </tr>
            </tbody>
        </table>

        <p><strong>Option Three: Independent Reviewer</strong></p>
        <table class="table table-bordered table-responsive">
            <thead>
            <th>Accounting</th>
            <th>Tax</th>
            <th>CPD hours</th>
            </thead>
            <tbody>
            <tr>
                <td width="400">Independent Review Standard Update</td>
                <td width="400">-</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td>Preparing Working Papers for Independent Reviews  </td>
                <td>-</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td>IFRS for SMEs Update</td>
                <td>-</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td>Preparing Financial Statements for IFRS for SMEs</td>
                <td>-</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td>Monthly accounting webinar (various topics)</td>
                <td></td>
                <td>20</td>
            </tr>
            <tr>
                <td>Monthly accounting update webinar</td>
                <td></td>
                <td>10</td>
            </tr>
            <tr>
                <td>Monthly practice management webinar</td>
                <td></td>
                <td>10</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right;"><strong>TOTAL CPD HOURS</strong></td>
                <td>56</td>
            </tr>
            </tbody>
        </table>

        <p><strong>Option Four: ‘Build Your Own’ Package (choose any eight of the below seminar topics)</strong></p>
        <table class="table table-bordered table-responsive">
            <thead>
            <th>Accounting</th>
            <th>Tax</th>
            <th>CPD hours</th>
            </thead>
            <tbody>
            <tr>
                <td width="400">Accounting Officer Engagements</td>
                <td width="400">Budget & Tax Update</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td>CIPC Update </td>
                <td>Tax Administration Act/ SARS Objections, Appeals & Dispute Resolution</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td>Trust Law Update</td>
                <td>Tax Planning for Trusts & Deceased Estates</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td>Business Rescue</td>
                <td>Specialised VAT Issues</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td>IFRS for SMEs Update</td>
                <td>Entrepreneurial & Medium Enterprises</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td>Preparing Financial Statements for IFRS for SMEs</td>
                <td>Year-end Tax Update</td>
                <td>4 per seminar</td>
            </tr>
            <tr>
                <td>Accounting Cycle</td>
                <td>Small businesses</td>
                <td></td>
            </tr>
            <tr>
                <td>From Trial Balance to Financial Statement</td>
                <td>Individual Tax Return</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>Employees & Provisional Tax/Employees & Payroll Withholding</td>
                <td></td>
            </tr>
            <tr>
                <td>Monthly accounting webinar (various topics)</td>
                <td></td>
                <td>20</td>
            </tr>
            <tr>
                <td>Monthly tax update webinar</td>
                <td></td>
                <td>20</td>
            </tr>
            <tr>
                <td>Monthly accounting update webinar</td>
                <td></td>
                <td>10</td>
            </tr>
            <tr>
                <td>Monthly practice management webinar</td>
                <td></td>
                <td>10</td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right;"><strong>TOTAL CPD HOURS</strong></td>
                <td>92</td>
            </tr>
            </tbody>
        </table>
        <hr>
        <h3>Your Investment</h3>
        <table class="table">
            <thead>
            <th>Package</th>
            <th>Once-Off Pricing</th>
            <th>Debit Order Pricing</th>
            </thead>
            <tbody>
            <tr>
                <td>Bookkeeper/Junior Accountant</td>
                <td>R 3,600.00</td>
                <td>R 330.00 PM</td>
            </tr>
            <tr>
                <td>Accounting Officer</td>
                <td>R4 850.00</td>
                <td>R 445.00 PM</td>
            </tr>
            <tr>
                <td>Independent Reviewer</td>
                <td>R2,950.00</td>
                <td>R 270.00 PM</td>
            </tr>
            <tr>
                <td>Build Your Own Package</td>
                <td>R4 850.00</td>
                <td>R 445.00 PM</td>
            </tr>
            </tbody>
        </table>

        <p><i>All prices are inclusive of VAT. Payments can be made by EFT (once-off payment only), credit card or debit order. </i></p>

        <hr>
        <p><strong>Can I join the CPD subscription package after the start of the year?</strong></p>
        <p>Yes you can.  If you join partway during the year, you will receive access to the online recordings of all events and webinars that have already taken place.  You will still be able to claim CPD hours for these recordings provided that you have watched them.  If you opt for the monthly payment option, please note that payment for the months that have already passed will be payable in full up front.  Thereafter you will be able to pay monthly.  </p>
        <p><strong>When do I get access to my CPD?</strong></p>
        <p>Once you have signed up you will receive access to your own personal profile. All events and webinars will be uploaded to your profile and will be available from February 2016. </p>

        <p><a href="{{ route('cpd_new') }}">Click here to register now</a></p>
    </div>
</section>

@endsection