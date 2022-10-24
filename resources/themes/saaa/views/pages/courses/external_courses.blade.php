@extends('app')

@section('title')
    ICAEW Financial Reporting and Clarity ISA learning programmes
@stop

@section('content')
    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <div class="thumbnail"><img src="http://imageshack.com/a/img921/9803/0bz5Z6.gif" alt=""></div>
                </div>
                <h4>ICAEW Financial Reporting and Clarity ISA learning programmes </h4>
                <p>
                    ICAEW (The Institute of Chartered Accountants in England and Wales) is a world-leading
                    professional membership organisation that promotes, develops and supports over 145,000 chartered
                    accountants worldwide. They provide qualifications and professional development, share their
                    knowledge, insight and technical expertise and protect the quality and integrity of the
                    accountancy and finance profession.
                </p>
                <p>
                    SA Accounting Academy and SAIBA are proud to offer the ICAEW financial reporting and Clarity ISA
                    online learning programmes to our clients. The following certificate-level programmes are
                    available to both SAIBA members and SA Accounting Academy clients:
                </p>
                <hr>
            </div>

            <div class="col-md-12">
                <div class="row">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a class="btn btn-primary btn-xs pull-right" target="_blank" href="https://academyone.co.za/ifrs-for-smes-certificate-course/">Register</a>
                            <a class="btn btn-default btn-xs pull-right" style="margin-right: 5px" href="{{ route('courses.ifrs_for_smes_learning_and_assessment_programme')  }}">Show</a>
                            <h2 class="panel-title">
                                <a href="{{ route('courses.ifrs_for_smes_learning_and_assessment_programme')  }}">IFRSfor SMEs learning and assessment programme</a></h2>
                        </div>
                        <div class="panel-body">
                            Developed by the ICAEW's leading specialists in IFRS, this programme is appropriate even if
                            you have no prior knowledge of the IFRS for SMEs . If you are already familiar with the
                            subject area, then the course provides in-depth learning and support.
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection