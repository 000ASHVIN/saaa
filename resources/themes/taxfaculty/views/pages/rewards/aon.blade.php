@extends('app')

@section('content')

@section('title')
    First for Accountants Professional Indemnity Programme
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
                                <img src="/assets/frontend/images/sponsors/aon.jpg" width="80%" class="thumbnail" alt="AON">
                            </center>
                        </div>
                        <div class="col-md-8">
                            <h4>First for Accountants Professional Indemnity Programme</h4>
                            <p><strong>Discount</strong>: Qualify for PI Insurance from as little as R399 per month for R2 000 000 cover.</p>
                            <p><strong>Steps:</strong></p>
                            <ol>
                                <li>Register as an SAAA CPD Subscriber.</li>
                                <li>Complete the form to the right and a qualified FSB accredited financial planner will contact you to explain how you can reduce your insurance cost by adhering to professional and quality control standards.</li>
                            </ol>
                            <p>According to the AON master policy, if you pay an estimated R399 per year you have access to either R2 000 000 or R5 000 000 to cover:</p>
                            <ul>
                                <li>Actual or alleged negligence and defence costs.</li>
                                <li>World wide claims.</li>
                                <li>Employee dishonesty.</li>
                                <li>Free Retroactive cover.</li>
                                <li>Thirty six (36) months post practice claims.</li>
                                <li>Subcontracted duties.</li>
                                <li>Computer Crime.</li>
                                <li>Public liability.</li>
                                <li>Commercial crime.</li>
                                <li>Directors’ & Officers’ Liability.</li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <h4>Need more information ?</h4>
                {!! Form::open(['method' => 'post', 'route' => ['rewards.store', 'aon']]) !!}
                @include('questionnaire.includes.form', ['product' => ['aon' => 'AON']])
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