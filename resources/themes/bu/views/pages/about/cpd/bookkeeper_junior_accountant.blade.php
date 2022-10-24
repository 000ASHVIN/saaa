@extends('app')

@section('content')

@section('title')
    Bookkeeper/Junior Accountant
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
            Our 2017 programme line-up for <i>Bookkeepers/Junior Accountants</i> includes the following seminars:
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
                <td>Accounting Cycle</td>
                <td>Taxation of small businesses</td>
            </tr>
            <tr>
                <td>Bookkeeping: From Trial Balance to Financial Statements</td>
                <td>Individual Tax Returns</td>
            </tr>
            <tr>
                <td></td>
                <td>Year-end Tax Update</td>
            </tr>
            <tr>
                <td></td>
                <td>Budget & Tax Update</td>
            </tr>
            <tr>
                <td></td>
                <td>Employees & Provisional Tax/Employees & Payroll Withholding</td>
            </tr>
            </tbody>
        </table>

        <h4>Subscribers also qualify for access to the following webinars:</h4>
        <table class="table table-bordered table-striped">
            <tbody>
            <tr>
                <td>Monthly accounting update webinar</td>
            </tr>
            <tr>
                <td>Monthly tax update webinar</td>
            </tr>
            <tr>
                <td>Tax Administration Act Update webinar (once-off)</td>
            </tr>
            </tbody>
        </table>
        <h4>CPD Hours</h4>
        <p>The total CPD hours for this package = 60</p>
        <hr>
        <h4>Pricing</h4>
        <p>The total price will be R 330.00 PM, effective 1 December 2016.</p>

        @include('pages.about.cpd.includes.pi_insurance')

    </div>
</section>
@endsection