@extends('app')

@section('content')

@section('title')
    Technical Resource Centre
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('resource_centre') !!}
@stop

<section id="slider" class="hidden-sm hidden-xs">
    <img src="https://imageshack.com/a/img923/3048/80sCiK.jpg" alt="Technical Resource Centre" style="width: 100%">
</section>

@include('resource_centre.includes.search')

<section class="alternate">
    <div class="container">
        <div class="row">
            <h5 class="text-center">The SA Accounting Academy (SAAA) Technical Resource Centre is constantly updated by
                our team of experts to help you stay compliant and informed. <br>
                The Technical Resource Centre service is free for all SAAA Designation CPD subscriber members to
                use.
            </h5>
            <hr>
        </div>
        <div class="row">
            <div class="col-md-3 text-center">
                <div class="border-box" style="min-height: 200px; margin: 10px;">
                    <h4>Not a member</h4>
                    <hr>
                    <p>Gain access to this Technical Resource Centre through one of our subscriptions</p>
                    <a href="/cpd" class="btn btn-primary">Read More</a>
                </div>
            </div>

            <div class="col-md-3 text-center">
                <div class="border-box" style="min-height: 200px; margin: 10px;">
                    <h4>FAQs</h4>
                    <hr>
                    <p>A source of commonly asked technical questions</p>
                    <a href="{{ route('resource_centre.technical_faqs.index') }}" class="btn btn-primary">Read More</a>
                </div>
            </div>

            <div class="col-md-3 text-center">
                <div class="border-box" style="min-height: 200px; margin: 5px;">
                    <h4>Webinars on-demand</h4>
                    <hr>
                    <p>Browse our extensive range of relevant webinars</p>
                    <a href="/webinars_on_demand" class="btn btn-primary">Read More</a>
                </div>
            </div>

            <div class="col-md-3 text-center">
                <div class="border-box" style="min-height: 200px; margin: 10px;">
                    <h4>Ask an Expert</h4>
                    <hr>
                    <p>Get your questions answered by one of our Technical Experts</p>
                    <a href="{{ route('support_ticket.create') }}" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-md-3 text-center">
                <div class="border-box" style="min-height: 200px; margin: 10px;">
                    <h4>Courses</h4>
                    <hr>
                    <p>Browse our relevant and practical courses</p>
                    <a href="{{ url('/') }}/courses" class="btn btn-primary">Read More</a>
                </div>
            </div>

            <div class="col-md-3 text-center">
                <div class="border-box" style="min-height: 200px; margin: 10px;">
                    <h4>Articles</h4>
                    <hr>
                    <p>View technical articles written by our Technical Experts</p>
                    <a href="{{ url('/') }}/news" class="btn btn-primary">Read More</a>
                </div>
            </div>

            <div class="col-md-3 text-center">
                <div class="border-box" style="min-height: 200px; margin: 10px;">
                    <h4>Legislation</h4>
                    <hr>
                    <p>Access updated legislation including amendments</p>
                    <a href="{{ route('resource_centre.legislation.index') }}" class="btn btn-primary">Read More</a>
                </div>
            </div>

            <div class="col-md-3 text-center">
                <div class="border-box" style="min-height: 200px; margin: 10px;">
                    <h4>Get an opinion</h4>
                    <hr>
                    <p>Need an opinion from one of our Technical Experts</p>
                    <a href="{{ route('resource_centre.legislation.opinion') }}" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
    <script>
        function spin(this1) {
            this1.closest("form").submit();
            this1.disabled = true;
            this1.innerHTML = `<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
    </script>
@stop