@extends('app')

@section('content')

@section('title')
    System Test Drive
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('test_drive') !!}
@stop

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <figure class="box-shadow-7 lightbox" data-plugin-options='{"type":"image"}' href="https://imagizer.imageshack.us/v2/1375x706q90/922/WKo66J.jpg">
                    <img class="img-responsive" src="https://imagizer.imageshack.us/v2/1375x706q90/922/WKo66J.jpg" alt="Home Page" />
                </figure>
            </div>
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <div class="heading-title heading-dotted text-left">
                    <h4>Visit our new <span>Home Page</span></h4>
                </div>
                <p>Go to <a href="{{ url('/') }}" target="_blank">{{ url('/') }}</a> and sign up as a user. </p>
            </div>
        </div>

        <div class="divider divider-circle divider-center">
            <i class="fa fa-arrow-down"></i>
        </div>

        <div class="row">
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <div class="heading-title heading-dotted text-left">
                    <h4>Start by <span>joining us today</span></h4>
                </div>
                <p>Select the free plan to gain access to the CIPC Update online event.</p>
            </div>
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <figure class="box-shadow-7 lightbox" data-plugin-options='{"type":"image"}' href="https://imagizer.imageshack.us/v2/1375x710q90/923/huW72j.jpg">
                    <img class="img-responsive" src="https://imagizer.imageshack.us/v2/1375x710q90/923/huW72j.jpg" alt="SignUp Page" />
                </figure>
            </div>
        </div>

        <div class="divider divider-circle divider-center">
            <i class="fa fa-arrow-down"></i>
        </div>

        <div class="row">
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <figure class="box-shadow-7 lightbox" data-plugin-options='{"type":"image"}' href="https://imagizer.imageshack.us/v2/1375x705q90/921/pZefmC.jpg">
                    <img class="img-responsive" src="https://imagizer.imageshack.us/v2/1375x705q90/921/pZefmC.jpg" alt="Fill In your Details" />
                </figure>
            </div>
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <div class="heading-title heading-dotted text-left">
                    <h4>Create your <span>Free Profile</span></h4>
                </div>
                <p>Provide some additional contact details to complete your profile.</p>
            </div>
        </div>

        <div class="divider divider-circle divider-center">
            <i class="fa fa-arrow-down"></i>
        </div>

        <div class="row">
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <div class="heading-title heading-dotted text-left">
                    <h4>Congratulations you now have your <span>own profile</span></h4>
                </div>
                <p>View your profile information and CPD hours gained for the year so far.</p>
            </div>
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <figure class="box-shadow-7 lightbox" data-plugin-options='{"type":"image"}' href="https://imagizer.imageshack.us/v2/1375x711q90/921/owp0pj.jpg">
                    <img class="img-responsive" src="https://imagizer.imageshack.us/v2/1375x711q90/921/owp0pj.jpg" alt="Fill In your Details" />
                </figure>
            </div>
        </div>

        <div class="divider divider-circle divider-center">
            <i class="fa fa-arrow-down"></i>
        </div>

        <div class="row">
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <figure class="box-shadow-7 lightbox" data-plugin-options='{"type":"image"}' href="http://imagizer.imageshack.us/a/img921/7262/Tpfx5M.jpg">
                    <img class="img-responsive" src="http://imagizer.imageshack.us/a/img921/7262/Tpfx5M.jpg" alt="Events Page" />
                </figure>
            </div>
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <div class="heading-title heading-dotted text-left">
                    <h4>Head on over to our <span>Events Page</span></h4>
                </div>
                <p>Go to the top of the page and click on Events to navigate to our event index page.</p>
            </div>
        </div>

        <div class="divider divider-circle divider-center">
            <i class="fa fa-arrow-down"></i>
        </div>

        <div class="row">
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <div class="heading-title heading-dotted text-left">
                    <h4>Click on <span>CIPC Update</span></h4>
                </div>
                <p>Search and select the CIPC Update event.</p>
            </div>
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <figure class="box-shadow-7 lightbox" data-plugin-options='{"type":"image"}' href="http://imagizer.imageshack.us/a/img924/7819/g7aNVk.jpg">
                    <img class="img-responsive" src="http://imagizer.imageshack.us/a/img924/7819/g7aNVk.jpg" alt="CIPC Update Page" />
                </figure>
            </div>
        </div>

        <div class="divider divider-circle divider-center">
            <i class="fa fa-arrow-down"></i>
        </div>

        <div class="row">
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <figure class="box-shadow-7 lightbox" data-plugin-options='{"type":"image"}' href="https://imagizer.imageshack.us/v2/1375x710q90/924/PZKSLp.jpg">
                    <img class="img-responsive" src="https://imagizer.imageshack.us/v2/1375x710q90/924/PZKSLp.jpg" alt="CIPC Update Page" />
                </figure>
            </div>
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <div class="heading-title heading-dotted text-left">
                    <h4>Register for <span>CIPC Update</span></h4>
                </div>
                <p>Click on register and complete the registration form</p>
            </div>
        </div>

        <div class="divider divider-circle divider-center">
            <i class="fa fa-arrow-down"></i>
        </div>

        <div class="row">
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <div class="heading-title heading-dotted text-left">
                    <h4>Fill in all your details & your <span style="font-size: 18px; font-weight: bold">100% Discount Code</span></h4>
                </div>
                <p>Make sure you complete all fields and insert your discount code. The price should indicate Rnil.</p>
            </div>
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <figure class="box-shadow-7 lightbox" data-plugin-options='{"type":"image"}' href="https://imagizer.imageshack.us/v2/1375x707q90/924/GfBHnz.jpg">
                    <img class="img-responsive" src="https://imagizer.imageshack.us/v2/1375x707q90/924/GfBHnz.jpg" alt="CIPC Update Page" />
                </figure>
            </div>
        </div>

        <div class="divider divider-circle divider-center">
            <i class="fa fa-arrow-down"></i>
        </div>

        <div class="row">
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <figure class="box-shadow-7 lightbox" data-plugin-options='{"type":"image"}' href="https://imagizer.imageshack.us/v2/1375x704q90/923/6gH6Ym.jpg">
                    <img class="img-responsive" src="https://imagizer.imageshack.us/v2/1375x704q90/923/6gH6Ym.jpg" alt="CIPC Update Page" />
                </figure>
            </div>
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <div class="heading-title heading-dotted text-left">
                    <h4>Success! <span> You have completed event registration</span></h4>
                </div>
                <p>Your registration is now complete and you will navigate to your online profile. Your event is loaded under My Events and you have access to the recording and course notes.</p>
            </div>
        </div>

        <div class="divider divider-circle divider-center">
            <i class="fa fa-arrow-down"></i>
        </div>

        <div class="row">
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <div class="heading-title heading-dotted text-left">
                    <h4>Need to watch your <span> Webinar Recordings ? </span></h4>
                </div>
                <p>Click on My Events and select and watch your CIPC Videos.</p>
            </div>
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <figure class="box-shadow-7 lightbox" data-plugin-options='{"type":"image"}' href="https://imagizer.imageshack.us/v2/1375x705q90/924/dbl93a.jpg">
                    <img class="img-responsive" src="https://imagizer.imageshack.us/v2/1375x705q90/924/dbl93a.jpg" alt="CIPC Update Page" />
                </figure>
            </div>
        </div>

        <div class="divider divider-circle divider-center">
            <i class="fa fa-arrow-down"></i>
        </div>

        <div class="row">
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <figure class="box-shadow-7 lightbox" data-plugin-options='{"type":"image"}' href="https://imagizer.imageshack.us/v2/1375x708q90/922/p99EQm.jpg">
                    <img class="img-responsive lightbox" src="https://imagizer.imageshack.us/v2/1375x708q90/922/p99EQm.jpg" alt="CIPC Update Page" />
                </figure>
            </div>
            <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                <div class="heading-title heading-dotted text-left">
                    <h4>Have you lost your <span> Presentation or Notes? </span></h4>
                </div>
                <p>The course notes are available under My Events, under the tab Links</p>
            </div>
        </div>

        {{--<div class="divider divider-circle divider-center">--}}
            {{--<i class="fa fa-arrow-down"></i>--}}
        {{--</div>--}}

       {{--<div class="row">--}}
          {{--<div class="col-md-12">--}}
              {{--<div class="heading-title heading-dotted text-center">--}}
                  {{--<h2>Congratulations! <span>You have completed your training</span></h2>--}}
              {{--</div>--}}
          {{--</div>--}}
       {{--</div>--}}

    </div>
</section>
@endsection