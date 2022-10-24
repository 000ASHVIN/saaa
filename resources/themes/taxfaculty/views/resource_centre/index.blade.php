@extends('app')

@section('content')

@section('title')
    Technical Resource Centre
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('resource_centre') !!}
@stop

<section id="slider" class="hidden-sm hidden-xs">
    <img src="/assets/themes/taxfaculty/img/resource_banner.jpg" alt="Technical Resource Centre" style="width: 100%">
</section>

@include('resource_centre.includes.search')

<section class="alternate">
    <div class="container">
        <div class="row">
            <div class="col-md-3 text-center">
                <div class="border-box" style="min-height: 200px; margin: 10px;">
                    <h4>Not a member</h4>
                    <hr>
                    <p>Gain access to this Technical Resource Centre through one of our subscriptions</p>
                    <a href="/subscription_plans" class="btn btn-primary">Read More</a>
                </div>
            </div>
           

            <div class="col-md-3 text-center">
                <div class="border-box" style="min-height: 200px; margin: 10px;">
                    <h4>Events</h4>
                    <hr>
                    <p>View upcoming events</p>
                    <a href="{{ '/events' }}" class="btn btn-primary">Read More</a>
                </div>
            </div>

            <div class="col-md-3 text-center">
                <div class="border-box" style="min-height: 200px; margin: 10px;">
                    <h4>Tax Library: FAQs</h4>
                    <hr>
                    <p>Get the answers to all your tax issues</p>
                    <a href="{{ route('resource_centre.technical_faqs.index') }}" class="btn btn-primary">Read More</a>
                </div>
            </div>

            <div class="col-md-3 text-center">
                <div class="border-box" style="min-height: 200px; margin: 5px;">
                    <h4>Webinars on-demand</h4>
                    <hr>
                    <p>Update yourself with our tax webinars-on-demand</p>
                    <a href="{{ route('webinars_on_demand.home') }}" class="btn btn-primary">Read More</a>
                </div>
            </div>
        </div>



        <br>

        <div class="row">

            <div class="col-md-3 text-center">
                <div class="border-box" style="min-height: 200px; margin: 10px;">
                    <h4>TaxFind: Ask our Expert</h4>
                    <hr>
                    <p>Get your technical questions answered by one of our tax faculty experts</p>
                    <a href="{{ route('support_ticket.create') }}" class="btn btn-primary">Read More</a>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="border-box" style="min-height: 200px; margin: 10px;">
                    <h4>Courses</h4>
                    <hr>
                    <p>Browse our relevant and practical courses</p>
                    <a href="{{  route('courses.index') }}" class="btn btn-primary">Read More</a>
                </div>
            </div>

            <div class="col-md-3 text-center">
                <div class="border-box" style="min-height: 200px; margin: 10px;">
                    <h4>Tax Library: Articles</h4>
                    <hr>
                    <p>View technical commentary articles from tax faculty contributors</p>
                    <a href="{{ url('/') }}/news" class="btn btn-primary">Read More</a>
                </div>
            </div>

            <div class="col-md-3 text-center">
                <div class="border-box" style="min-height: 200px; margin: 10px;">
                    <h4>Tax Library: Legislation</h4>
                    <hr>
                    <p>Access updated tax legislation, including latest amendments</p>
                    @if(auth()->check() && ((auth()->user()->subscribed('cpd') && auth()->user()->subscription('cpd')->plan->price != '0') || (auth()->user()->hasCompany() && auth()->user()->hasCompany()->company && auth()->user()->hasCompany()->company->admin()->subscription('cpd') && auth()->user()->hasCompany()->company->admin()->subscription('cpd')->plan->price != '0')))
                    <a href="https://taxfaculty.mylexisnexis.co.za/" target="_blank" class="btn btn-primary">Read More</a>
                    @else
                    <a href="{{ route('resource_centre.legislation.index') }}" class="btn btn-primary">Read More</a>
                    @endif
                </div>
            </div>

            {{-- <div class="col-md-3 text-center">
                <div class="border-box" style="min-height: 200px; margin: 10px;">
                    <h4>Get an opinion</h4>
                    <hr>
                    <p>Need an opinion from one of our Technical Experts</p>
                    <a href="{{ route('resource_centre.legislation.opinion') }}" class="btn btn-primary">Read More</a>
                </div>
            </div> --}}
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
@stop