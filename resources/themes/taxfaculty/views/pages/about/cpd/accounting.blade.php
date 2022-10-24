@extends('app')

@section('content')

@section('title')
    Accounting Only
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
            Our 2017 programme line-up for <i>Accounting Only</i> includes the following seminars:
            <span class="pull-right">
                <a href="/auth/register" class="btn btn-primary">Register Now</a>
            </span>
        </p>
        <br>

        <table class="table table-bordered table-striped">
            <thead>
                <th>Accounting</th>
            </thead>
            <tbody>
            <tr>
                <td>Accounting Officer Engagements</td>
            </tr>
            <tr>
                <td>CIPC Update</td>
            </tr>
            <tr>
                <td>Trust Law Update</td>
            </tr>
            <tr>
                <td>Business Rescue</td>
            </tr>
            <tr>
                <td>IFRS for SMEs Update</td>
            </tr>
            <tr>
                <td>Preparing Financial Statements for IFRS for SMEs</td>
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
                <td>Monthly Accounting Update webinar</td>
            </tr>
            <tr>
                <td>Monthly practice management webinar</td>
            </tr>
            </tbody>
        </table>
        <h4>CPD Hours</h4>
        <p>The total CPD hours for this package = 64</p>
        <hr>
        <h4>Pricing </h4>
        <p>The total price (including annual increase) will be R 350.00 PM, effective 1 December 2016.</p>

        @include('pages.about.cpd.includes.pi_insurance')

    </div>
</section>
@endsection