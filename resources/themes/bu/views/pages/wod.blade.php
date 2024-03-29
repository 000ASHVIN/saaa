@extends('app')

@section('meta_tags')
    <title>SAAA | SA Accounting Academy | CPD Provider</title>
    <meta name="description" content="A library of on demand learning videos covering a wide range of accountancy and practice management topics that are always available for viewing.">
    <meta name="Author" content="SA Accounting Academy"/>
@endsection

@section('content')

@section('title')
    Webinars On-Demand
@stop

@section('intro')
    Webinars On-Demand
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('Contact') !!}
@stop

<section class="alternate">
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <div class="col-md-6">
                    <img src="https://imageshack.com/a/img921/3670/F3KCbo.jpg" width="100%" class="thumbnail" alt="Image">
                    <center><small>This is a beta release of Webinars on-demand and new functionality with be added soon.</small></center>
                    <br>
                    <br>
                    <a href="{{ route('webinars_on_demand.home') }}" class="btn btn-block btn-primary"><i class="fa fa-play"></i> Start Watching</a>

                </div>
                <div class="col-md-6">
                    <h4>What is Webinars on-demand ?</h4>
                    <p>A library of on demand learning videos covering a wide range of accountancy and practice management topics that are always available for viewing. </p>
                    <p>Topics are put together to develop and maintain your professional competence by technical experts so you can perform competently within your professional environment.</p>
                    <p>Completion of any webinars on-demand will award you the relevant CPD hours.</p>

                    <ul>
                        <li>Pay for what your watch</li>
                        <li>Cancel any time</li>
                        <li>No obligations</li>
                        <li>Discount for subscribers</li>
                        <li>In addition to existing package subscriptions</li>
                    </ul>

                    <p><a href="{{ route('webinars_on_demand.home') }}">Browse webinars based</a> on categories and select your option.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <div class="col-md-6">
                    <img style="border: 1px solid #e3e3e3; padding: 5px; margin: 5px" src="https://imageshack.com/a/img923/9448/CUjSAA.png" class="portfolio-item development" alt="" width="47%">
                    <img style="border: 1px solid #e3e3e3; padding: 5px; margin: 5px" src="https://imageshack.com/a/img923/8536/ctlOqH.png" class="portfolio-item development" alt="" width="47%">
                    <hr>
                    <img style="border: 1px solid #e3e3e3; padding: 5px; margin: 5px" src="https://imageshack.com/a/img922/4592/dPClFt.png" class="portfolio-item development" alt="" width="47%">
                    <img style="border: 1px solid #e3e3e3; padding: 5px; margin: 5px" src="https://imageshack.com/a/img924/6024/bZR8zt.png" class="portfolio-item development" alt="" width="47%">
                    <br>
                </div>
                <div class="col-md-6">
                    <h4>SA Accounting Academy Mobile App! <br> <small>To find the app search for “<strong>SA Accounting Academy</strong>”</small></h4>

                    <p>Download our mobile app and start watching your webinars on any smart devices!</p>
                    <ol>
                        <li>Download the Mobile App.</li>
                        <li>Login with your SA Accounting Academy email address and password.</li>
                        <li>Start watching your latest webinars.</li>
                        <li>Download webinars to your device.</li>
                        <li>Never miss an upcoming event again.</li>
                    </ol>

                    <p>The app is now available on Google Play and Apple Store!</p>
                    <img src="https://imageshack.com/a/img924/9505/TzXbUL.jpg" width="8%" alt="Apple Store">
                    <img src="https://imageshack.com/a/img924/2204/QAYTls.png" width="8%" alt="Google Play">

                    <hr>

                    <h4>Google Play</h4>
                    <img src="https://imageshack.com/a/img921/2697/o0WyFz.png" alt="" width="20%">
                </div>
            </div>

        </div>
    </div>
</section>

@endsection