@extends('app')

@section('content')

@section('title')
    Independent Reviewer
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
            Our 2017 programme line-up for <i>Independent Reviewers</i> includes the following seminars:
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
                <td>Independent Review Standard Update</td>
            </tr>
            <tr>
                <td>Preparing Working Papers for Independent Reviews</td>
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
                <td>Monthly legislation update webinar</td>
            </tr>
            <tr>
                <td>Monthly practice management webinar</td>
            </tr>
            </tbody>
        </table>
        <h4>CPD Hours</h4>
        <p>The total CPD hours for this package = 56</p>
        <hr>
        <h4>Pricing</h4>
        <p>The total price will be R 270.00 PM, effective 1 December 2016.</p>

        @include('pages.about.cpd.includes.pi_insurance')

    </div>
</section>
@endsection