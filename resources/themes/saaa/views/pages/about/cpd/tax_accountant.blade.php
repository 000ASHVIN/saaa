@extends('app')

@section('content')

@section('title')
    Tax Accountant
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
            Our 2017 programme line-up for <i>Tax Accountant</i> includes the following seminars:
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
            </tbody>
        </table>

        <h4>Subscribers also qualify for access to the following webinars:</h4>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <td>Monthly accounting webinar (various topics)</td>
            </tr>
            <tr>
                <td>Monthly SARS and Tax Update webinar</td>
            </tr>
            <tr>
                <td>Monthly legislation update webinar</td>
            </tr>
            <tr>
                <td>Monthly practice management webinar</td>
            </tr>
            </tbody>
        </table>
        <h4>CPD Hours</h4>
        <p>The total CPD hours for this package = 108</p>
        <hr>
        <h4>Pricing </h4>
        <p>The total price (including annual increase) will be R 445.00 PM, effective 1 December 2016.</p>

        @include('pages.about.cpd.includes.pi_insurance')

    </div>
</section>
@endsection