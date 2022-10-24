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

            <div class="col-lg-7 col-md-7">
                <h4>WHAT IS THE TAX FACULTY ABOUT?</h4>
                <p>
                    Accredited as a non-profit tertiary and continuing professional development (CPD) provider, The Tax Faculty’s focus is solely on tax studies. As a level 1 contributor to B-BBEE,we take pride in our ability to provide students with the requisite skills required of the twenty-first century tax professional.
                </p>
                <p>
                    At the core of our educational methodology, The Tax Faculty teaches students to approach business and tax problems using critical thinking skills. In preparing future tax leaders with metacognitive skills, our tuition methodology develops higher order thinking skills through analysis, evaluation and synthesis. This unique tuition approach aims to develop sound reasoning, questioning and investigating, observing and describing, comparing and connecting, finding complexity, and exploring different viewpoints.
                </p>
                <p>
                    Our accreditation as a registered skills development partner under the Skills Development Act, 1998 (Act No 97 of 1998) by the Quality Council for Trades and Occupations (QCTO) enables us to offer the Tax Technician and Tax Professional occupational qualifications registered with the South African Qualifications Authority (SAQA).
                </p>
                <p>
                    As an approved CPD provider by the South African Institute of Tax Professionals (SAIT), a SARS recognised controlling body under the Tax Administration Act, 2011 (Act No 28 of 2011), we offer best-in-class tax CPD.
                </p>

                <hr>

                <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                        <br>
                        <h4>Our Values</h4>
                        <br>
                    </div>
                    <ul>
                        <li>Always Committed</li>
                        <li>Excellence in everything we do</li>
                        <li>Fresh, innovative in our approach</li>
                        <li>Passionate about people</li>
                        <li>Goal orientated</li>
                    </ul>
                </div>
                </div>

{{--  
                <div class="text-center">
                    <br>
                    <h4>Our Values</h4>
                    <br>
                </div>

                <div class="row">
                    <div class="col-md-3"><img width="100%" src="{{ Theme::asset('/img/values/TF Elements_3.png') }}"
                                               alt="Image">
                        <br>
                        <br>
                        <div class="text-center">
                            <small>We build our relationships through integrity & credibility.</small>
                        </div>
                    </div>
                    <div class="col-md-3"><img width="100%" src="{{ Theme::asset('/img/values/TF Elements_6.png') }}"
                                               alt="Image">
                        <br>
                        <br>
                        <div class="text-center">
                            <small>We act with open and honesty, communication - truth, integrity, transparency, objectivity, accountability.</small>
                        </div>
                    </div>
                    <div class="col-md-3"><img width="100%" src="{{ Theme::asset('/img/values/TF Elements_7.png') }}"
                                               alt="Image">
                        <br>
                        <br>
                        <div class="text-center">
                            <small>We are committed to diversity, inclusion and transformation.</small>
                        </div>
                    </div>
                    <div class="col-md-3"><img width="100%" src="{{ Theme::asset('/img/values/TF Elements_8.png') }}"
                                               alt="Image">
                        <br>
                        <br>
                        <div class="text-center">
                            <small>We make tax enjoyable.</small>
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <div class="row">

                    <div class="col-md-3"><img width="100%" src="{{ Theme::asset('/img/values/TF Elements_9.png') }}"
                                               alt="Image">
                        <br>
                        <br>
                        <div class="text-center">
                            <small>Bold, innovative and collaboration.</small>
                        </div>
                    </div>

                    <div class="col-md-3"><img width="100%" src="{{ Theme::asset('/img/values/TF Elements_10.png') }}"
                                               alt="Image">
                        <br>
                        <br>
                        <div class="text-center">
                            <small>We operation with passion and have fun making an impact.</small>
                        </div>
                    </div>

                    <div class="col-md-3"><img width="100%" src="{{ Theme::asset('/img/values/TF Elements_11.png') }}"
                                               alt="Image">
                        <br>
                        <br>
                        <div class="text-center">
                            <small>DEMONSTRATE OBJECTIVITY</small>
                        </div>
                    </div>
                </div>  --}}


            </div>

            <div class="col-sm-5 col-md-5">
                <div class="col-md-12" style="background-color: #F2F2F2; padding: 30px">
                    <h3 class="title" style="color: #008F88 !important">OUR VISION</h3>
                    <p>
                        Transforming and empowering the tax profession for a sustainable future 
                    </p>
                    <h3 class="title" style="color: #008F88 !important">OUR MISSION</h3>
                    <p>
                        Transforming and empowering the tax profession for a sustainable future by:
                    </p>
                    <ul>
                        <li>Being a credible Tax Education and lifelong learning provider. </li>
                        <li>Enhancing the practice of taxation with relevant, responsive tax education through collaboration</li>
                        <li>Building the Tax profession brand as an aspirational and credible first choice profession that attracts and nurtures future tax professionals.</li>
                        <li>Supporting fiscal citizenship through tax morality and further the national development agenda with an emphasis on job creation and enterprise development.</li>
                        <li>Supporting the Tax profession and taxpayers to be future fit.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection