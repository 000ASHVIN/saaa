@extends('app')

@section('title')
    Certificate Courses
@stop

@section('content')
    <section>
        <div class="container">
            <div class="col-md-12">
                <div class="row">
                    {{--<div class="panel panel-default">--}}
                        {{--<div class="panel-heading">--}}
                            {{--<a class="btn btn-default btn-xs pull-right" href="{{ route('courses.ifrs_for_smes') }}">Show</a>--}}
                            {{--<h2 class="panel-title"><a href="{{ route('courses.ifrs_for_smes') }}">--}}
                                    {{--Certificate in IFRS for SMEs</a></h2>--}}
                        {{--</div>--}}
                        {{--<div class="panel-body">--}}
                            {{--If you are a professional accountant or auditor, qualified in accordance with your national--}}
                            {{--accounting standards, then this qualification is for you. If you have experience, but no--}}
                            {{--formal qualifications in accounting and auditing, you may still be able to apply for this--}}
                            {{--certificate.--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a class="btn btn-default btn-xs pull-right"
                               href="{{ route('courses.independent_review_engagements')  }}">Show</a>
                            <h2 class="panel-title"><a href="{{ route('courses.independent_review_engagements')  }}">
                                    Certificate in Independent Review Engagements</a></h2>
                        </div>
                        <div class="panel-body">
                            The aim of the Certificate in Independent Review Engagements is to develop knowledge,
                            understanding and application of the review engagement standard (ISRE 2400), the
                            international standard on quality control (ISQC1) and the concepts and principles which
                            underpin them.
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a class="btn btn-default btn-xs pull-right"
                               href="{{ route('courses.practising_licence_independent_review_engagements') }}">Show</a>
                            <h2 class="panel-title"><a
                                    href="{{ route('courses.practising_licence_independent_review_engagements') }}">Practising
                                    Licence: Independent Review Engagements</a></h2>
                        </div>
                        <div class="panel-body">
                            The Practising Licence: Independent Review Engagements is a credit-by-examination programme
                            aimed at practitioners interested in performing review engagements. Practitioners attain
                            recognition by demonstrating knowledge in review engagements.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection