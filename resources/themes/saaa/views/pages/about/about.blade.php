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
                <h4><b>Creating opportunities to connect our partners to succeed</b></h4>

                <p>
                    SA Accounting Academy was incorporated during February 2007 as a Continuous Professional Development (CPD) training provider for accountants, auditors, bookkeepers, and company secretaries.
                </p>

                <p>
                    Training offered by the Academy is compliant with the International Education Standard (IES7) issued by the International Federation of Accountants and recognised for CPD by relevant professional bodies including ACCA, CIMA, IAC, IIA, SAIBA, SAICA and SAIPA.
                </p>

                <p>
                    We also offer in-house / onsite training on our technical and business-related topics.
                </p>

                <h4><b>Our Accreditations</b></h4>

                <p>SA Accounting Academy (SAAA) is accredited by the following institutions:
                    <ul>
                        <li>South African Institute of Chartered Accountants (SAICA) - AT(SA) training provider. Accreditation number ATS0063/0821.</li>
                        <li>Southern African Institute for Business Accountants (SAIBA) - Accredited Training Services Provider.</li>
                        <li>Financial Planning Institute (FPI) - CPD provider.</li>
                    </ul>
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