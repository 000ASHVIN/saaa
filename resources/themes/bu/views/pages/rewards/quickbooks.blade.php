@extends('app')

@section('content')

@section('title')
    QuickBooks for Accountants
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('rewards') !!}
@stop

@section('styles')
    <style>
        .verticalLine {
            border-right: thick solid #e3e3e3;
        }
    </style>
@endsection

<section class="alternate">
    <div class="container">
        <div class="row">
            <div class="col-md-8 verticalLine">
                <div style="background-color: white; padding: 10px; border: 1px solid #e3e3e3">
                    <div class="row">
                        <div class="col-md-4">
                            <center>
                                <img src="/assets/frontend/images/sponsors/quickbooks.jpg" width="80%" class="thumbnail" alt="AON">
                            </center>
                        </div>
                        <div class="col-md-8">
                            <h4>QuickBooks for Accountants</h4>
                            <p>QuickBooks is the world’s no. 1 cloud accounting solution, chosen by over 700k accounting partners around the world to manage and grow their practice.</p>
                            <p>How our product delivers:</p>
                            <ul>
                                <li><strong>Manage workflows effortlessly: </strong>Track clients and projects in one place from start to finish so nothing falls through the cracks.</li>
                                <li><strong>Collaborate in real-time: </strong>Access your client books anytime, anywhere to quickly answer questions. Set permissions within your team to control access to client data.</li>
                                <li><strong>Comply with complete confidence: </strong>Ensure that your clients’ books are done right with advanced audit trails, VAT compliance, and the ability to close prior periods.</li>
                                <li><strong>Grow your practice: </strong>Connect to local small businesses in need of help from experts like you.</li>
                                <li><strong>Get up to speed quickly: </strong>Free, flexible training and certification options plus unlimited phone and email support from QuickBooks experts will give you the confidence you need to fully support your clients.</li>
                            </ul>
                            <p>Not yet a QuickBooks Online for Accountant user?</p>
                            <ul>
                                <li><a target="_blank" href="https://quickbooks.intuit.com/za/accountants/?cid=SAAA_academy_co:za">Sign up</a> for QBOA for free</li>
                                <li>Get certified - attract new clients</li>
                                <li>Pass on 50% savings to your clients</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <h4>Need more information ?</h4>
                {!! Form::open(['method' => 'post', 'route' => ['rewards.store', 'aon']]) !!}
                @include('questionnaire.includes.form', ['product' => ['quickbooks' => 'QuickBooks']])
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
    <script>
        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
    </script>
@endsection