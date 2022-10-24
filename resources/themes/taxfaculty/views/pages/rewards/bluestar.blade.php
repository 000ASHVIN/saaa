@extends('app')

@section('content')

@section('title')
    First for BlueStar
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
                        <div class="col-md-12">
                            <h4>First for BlueStar</h4>
                            <p>First For BlueStar is a committed team of financial planners situated in Constantia Kloof, Johannesburg. We are the only financial service provider in South Africa that focuses exclusively on recognised professionals. Our valued clients, who pass our Professional Vitality™ assessment, are entitled to rewards, practice support services, and tailor-made insurance solutions for their specific needs.</p>
                            <p>In addition to this, we also assist and enable professional firms to enter into the financial planning market without administrative burden and advice risk. We enable firms to diversify their revenue stream by facilitating the offering of financial advisory services to their clients.</p>
                            <p><strong>Insurance for professionals</strong></p>
                            <p>Reduced premiums based on your commitment to quality and professional conduct.</p>

                            <div class="col-md-4">
                                <img src="/assets/frontend/images/sponsors/sanlam.jpg" width="100%" class="thumbnail" alt="Sanlam">
                            </div>
                            <div class="col-md-4">
                                <img src="/assets/frontend/images/sponsors/santam.jpg" width="100%" class="thumbnail" alt="Santam">
                            </div>
                            <div class="col-md-4">
                                <img src="/assets/frontend/images/sponsors/aon.jpg" width="100%" class="thumbnail" alt="AON PI">
                            </div>

                            <p><strong>Professional Indemnity Cover:</strong></p>
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
                            <p><strong>Income protection</strong> - What happens if you fall ill or get injured, and cant practice?</p>
                            <p><strong>Retirement</strong> - Maintain your lifestyle during your retirement by saving over the long term.</p>
                            <p><strong>Short term insurance</strong> - personal and business asset risk solutions for car, household, hobby and specialist assets.</p>

                            <ol>
                                <li>Not yet a SAAA subscribers? Signup for a free CPD Subscription Package.</li>
                                <li>Login to your account and complete the form under rewards and a qualified FSCA accredited financial planner from First For BlueStar will contact you to explain how you can reduce your insurance cost by adhering to professional and quality control standards.</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <img src="/assets/frontend/images/sponsors/bluestar.jpg" width="100%" class="thumbnail" alt="SAIBA">
                <h4>Need more information ?</h4>
                {!! Form::open(['method' => 'post', 'route' => ['rewards.store', 'saiba']]) !!}
                @include('questionnaire.includes.form', ['product' => ['aon' => 'AON', 'sanlam' => 'Sanlam', 'santam' => 'Santam']])
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
