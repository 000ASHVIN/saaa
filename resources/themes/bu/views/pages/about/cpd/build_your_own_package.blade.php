@extends('app')

@section('content')

@section('title')
    Comprehensive Accountancy Practice
@stop

@section('intro')

@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('CPD') !!}
@stop
<section>
    <div class="container">

        <div class="heading-title heading-dotted">
            <h4><span>2017 CPD Programme</span></h4>
        </div>

        <p>
            You can now choose solution for your Comprehensive Accountancy Practice. Choose any eight of the following seminar topics:
            <span class="pull-right">
                <a href="/auth/register" class="btn btn-primary">Register Now</a>
            </span>
        </p>
        <br>

        <table class="table table-bordered table-striped">
            <thead>
            <th>Accounting</th>
            <th>Tax</th>
            </thead>
            <tbody>
            <tr>
                <td>Accounting Officer Engagements</td>
                <td>Budget & Tax Update</td>
            </tr>
            <tr>
                <td>CIPC Update</td>
                <td>Tax Administration Act/ SARS Objections, Appeals & Dispute Resolution</td>
            </tr>
            <tr>
                <td>Trust Law Update</td>
                <td>Tax Planning for Trusts & Deceased Estates</td>
            </tr>
            <tr>
                <td>Business Rescue</td>
                <td>Specialised VAT Issues</td>
            </tr>
            <tr>
                <td>IFRS for SMEs Update</td>
                <td>Entrepreneurial & Medium Enterprises</td>
            </tr>
            <tr>
                <td>Preparing Financial Statements for IFRS for SMEs</td>
                <td>Year-end Tax Update</td>
            </tr>
            <tr>
                <td>Accounting Cycle</td>
                <td>Small businesses</td>
            </tr>
            <tr>
                <td>From Trial Balance to Financial Statement</td>
                <td>Employees & Provisional Tax/Employees & Payroll Withholding</td>
            </tr>
            <tr>
                <td>Independent Review Standard Update</td>
                <td></td>
            </tr>
            <tr>
                <td>Preparing Working Papers for Independent Reviews</td>
                <td></td>
            </tr>
            </tbody>
        </table>

        <h4>Subscribers also qualify for access to the following webinars:</h4>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <td>Monthly accounting webinar (various topics)</td>
            </tr>
            <tr>
                <td>Monthly tax update webinar</td>
            </tr>
            <tr>
                <td>Monthly legislation update webinar</td>
            </tr>
            <tr>
                <td colspan="2">Monthly practice management webinar</td>
            </tr>
            </tbody>
        </table>
        <h4>CPD Hours</h4>
        <p>The total CPD hours for this package = 92</p>
        <hr>
        <h4>Pricing</h4>
        <p>The total price (including annual increase) will be R 445.00 PM, effective 1 December 2016.</p>

        @include('pages.about.cpd.includes.pi_insurance')
    </div>
</section>
@endsection