@extends('app')

@section('content')

@section('title')
    About Us
@stop

@section('intro')
    Creating opportunities to connect our partners to succeed
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('About') !!}
@stop

<section>
    <div class="container">
        <div class="row">

            <div class="col-lg-6">
                <h4>Creating opportunities to connect our partners to succeed</h4>

                <p>
                    The SA Accounting Academy (SAAA) offers Continuing Professional Development (CPD) training for accountants, auditors, bookkeepers, company secretaries and tax practitioners. We offer a range of live seminars and conferences and
                    online webinars, seminar recordings, certificate courses and DVDs on both technical and business-related topics.
                </p>

                <p>
                    All training offered by the Academy is recognised for CPD hours by the relevant professional bodies:
                    SAICA, AAT, ACCA, SAIPA, ICBA, SAIBA, IAC, IIA and CIMA. <br> <b>The CPD policy is compliant with IFAC IES7.</b>
                </p>

                <p>
                    We also offer an event management service - if you run your own workshops, seminars or conferences and would like to outsource the planning and
                    administration of the events to our professional team, please <a href="{{ '/contact' }}">contact us</a>.
                </p>
            </div>

            <div class="col-sm-6 col-md-6">
                <div class="owl-carousel buttons-autohide controlls-over" data-plugin-options='{"singleItem": true, "autoPlay": true, "navigation": true, "pagination": true, "transitionStyle":"goDown"}'>
                    <div>
                        <img class="img-responsive" src="/assets/frontend/images/about/image-3.jpg" alt="">
                    </div>
                    <div>
                        <img class="img-responsive" src="/assets/frontend/images/about/image-4.jpg" alt="">
                    </div>
                    <div>
                        <img class="img-responsive" src="/assets/frontend/images/about/image-2.jpg" alt="">
                    </div>
                    <div>
                        <img class="img-responsive" src="/assets/frontend/images/about/image-1.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection