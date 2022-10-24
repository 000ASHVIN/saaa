<!-- FOOTER -->
<footer id="footer" class="hidden-print">
    @if(Request::is('dashboard')||
        Request::is('dashboard/edit')||
        Request::is('dashboard/events')||
        Request::is('dashboard/cpd')||
        Request::is('dashboard/invoices')
    )

    @else
        <div style="background-color: #eee">
            <div class="container" style="color: #636363">
                <br>
                <br>
                <p><strong>The Tax Faculty .</strong></p>
                <p style="font-size: 12px;">
                    The Tax Faculty is registered as a skills development partner under the Skills Development Act, 1998
                    (Act No 97 of 1998) by the Quality Council for Trades and Occupations (QCTO). Accreditation number
                    QCTOSDP00180420. The Tax Technician and Tax Professional occupational qualifications offered by The
                    Tax Faculty are registered with the South African Qualifications Authority (SAQA).
                </p>
                <p style="font-size: 12px;">
                    The Tax Faculty is accredited as a Continuous Professional Development (CPD) provider by the South
                    African Institute of Tax Professionals, a South African Revenue Services Recognised Controlling Body
                    (RCB) under the Tax Administration Act, 2011 (Act No. 28 of 2011).
                </p>
                <br>
                <br>
            </div>
        </div>
        <br>
        <br>
        <div class="container hidden-print">
            <div class="row">
                <div class="col-md-3">
                    <img src="/assets/themes/taxfaculty/img/taxfaculty-logo-footer.png" alt="Logo">
                    <br>
                    <br>
                    <address>
                        <ul class="list-unstyled">
                            <li class="footer-sprite address">
                                Riverwalk Office Park, <br> 41 Matroosberg road, Block A, <br> Ground floor, Ashley
                                Gardens, <br> Pretoria
                            </li>
                            <li class="footer-sprite phone">
                                Phone: 012 943 7002
                            </li>
                            <li class="footer-sprite email">
                                <a href="mailto:info@taxfaculty.ac.za">info@taxfaculty.ac.za</a>
                            </li>
                        </ul>
                    </address>
                    <!-- /Contact Address -->

                     <!-- /Social Icons -->
                     <br>
                     <p>Stay connected with us</p>
                     <ul class="footer-social-icons list-inline">
                         <li class=""><a href="https://twitter.com/thetaxfaculty" target="_blank"><img src="{{ url('/') }}/assets/themes/taxfaculty/img/icons/twitter_new.png" alt="twitter"></a></li>
                         <li class=""><a href="https://www.linkedin.com/company/the-tax-faculty/" target="_blank"><img src="{{ url('/') }}/assets/themes/taxfaculty/img/icons/linkedin_new.png" alt="linkedIn"></a></li>
                         <li class=""><a href="https://www.facebook.com/TheTaxFaculty/" target="_blank"><img src="{{ url('/') }}/assets/themes/taxfaculty/img/icons/facebook_new.png" alt="facebook"></a></li>
                         <li class=""><a href="https://www.youtube.com/channel/UCdUuCo_4wflmQpcob64K5oQ/" target="_blank"><img src="{{ url('/') }}/assets/themes/taxfaculty/img/icons/youtube_new.png" alt="youtube"></a></li>
                     </ul>
                     <!-- /Social Icons -->
                </div>

                <div class="col-md-6">

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Links -->
                            <h4 class="letter-spacing-1">Quick Links</h4>
                            <ul class="footer-links list-unstyled">
                                <li><a href="/contact">Contact Us</a></li>
                                <li><a href="/about">About</a></li>

                                <li><a href="/">Accreditation</a></li>
                                <li><a href="https://courses.taxfaculty.ac.za/fund-a-learner/">Fund a learner</a></li>
                                <li><a class="" href="{!! '/faq' !!}">FAQs</a></li>
                                {{-- <li><a href="/ourteam">Our Team</a></li> --}}
                                {{--  <li><a href="/presenters">Our Presenters</a></li>  --}}
                                {{--<li><a href="/partners">Our Partners</a></li> --}}
                                {{-- <li><a href="/subscription_plans">CPD Subscription</a></li> --}}
                                
                            </ul>
                            <!-- /Links -->
                        </div>

                        <div class="col-md-6">
                            <h4 class="letter-spacing-1">Quick Links</h4>
                            <ul class="footer-links list-unstyled">
                                <li><a href="/courses">Courses</a></li>
                                <li><a href="/subscription_plans">CPD Subscriptions</a></li>
                                <li><a class="" href="{{ '/events' }}">Events</a></li>
                                <li><a  href="{{ route('webinars_on_demand.home') }}">ON-DEMAND</a></li>
                                <li><a  href="#">Testimonials</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">

                    <!-- Newsletter Form -->
                    <h4 class="letter-spacing-1">KEEP IN TOUCH</h4>
                    <p>Subscribe to our mailing list and you will receive our best posts every week on our live <a target="_blank" style="color: #8cc03c !important;" href="{{ url('/') }}/events" class="newsletter-link">CPD webinars</a>, <a target="_blank" style="color: #8cc03c !important;" href="{{ url('/') }}/courses" class="newsletter-link">upcoming courses</a> and <a target="_blank" style="color: #8cc03c !important;" href="{{ url('/') }}/webinars_on_demand" class="newsletter-link">webinars-on-demand</a></p>

                    {{--<form action="https://bulkro.com/admin/subscribe" method="POST" accept-charset="utf-8" id="subscribe_form">--}}
                    {{--<div class="form-inline">--}}
                    {{--<div class="form-group">--}}
                    {{--<input type="text" class="form-control" name="name" id="name"/>--}}
                    {{--<input type="text" class="form-control" name="email" id="email"/>--}}
                    {{--</div>--}}
                    {{--<input type="hidden" name="list" value="u8oFBpC7BPZMmMbdjV8U9g"/>--}}
                    {{--</div>--}}
                    {{--<input type="submit" class="btn btn-primary" value="subscribe" name="Send" id="submit"/>--}}
                    {{--</form>--}}

                    <form class="validate" action="/newsletter/subscribe" method="post"
                          data-success="Subscribed! Thank you!" data-toastr-position="bottom-right">
                        {!! csrf_field() !!}

                        <div style="display: flex">
                            <input type="text" class="form-control required" name="first_name"
                                   style="margin: 2px 2px 5px;" placeholder="First Name">
                            <input type="text" class="form-control" name="last_name" style="margin: 2px 2px 5px;"
                                   placeholder="Last Name">
                        </div>

                        <div style="display: flex">
                            <input type="email" id="email" name="email" class="form-control required"
                                   placeholder="Enter your Email" style="margin: 2px 2px 5px;">
                        </div>
                        <div class="telno">
                            <input type="text" id="cellform" name="cell" class="form-control required"
                                   placeholder="Enter your Mobile no" style="margin: 2px 2px 5px;">
                        </div>
                        @if(env('GOOGLE_RECAPTCHA_KEY'))	
                        <div class="row" >	
                            <div class="col-md-12">	
                                <div class="g-recaptcha"	
                                    data-sitekey="{{env('GOOGLE_RECAPTCHA_KEY')}}">	
                                </div>	
                            </div>	
                         </div>
                         @endif

                         <div>
                            <p style="margin-top: 10px;">By subscribing to our mailing list you accept our <a style="color: #8cc03c !important;" href="{{ route('terms_and_conditions') }}" target="_blank" class="newsletter-link terms">Terms of Service</a> and <a style="color: #8cc03c !important;" href="{{ route('privacy_policy') }}" target="_blank" class="newsletter-link privacy">Privacy Policy</a></p>
                        </div>

                        <div class="input-group-addon"
                             style="border: none; padding: 2px; color: white; background-color: #8cc03c; border-color: #8cc03c;">
                            <button class="btn btn-success btn"
                                    style="border: none; padding: 2px; color: white; background-color: #8cc03c; border-color: #8cc03c;"
                                    type="submit"><i class="fa fa-envelope"></i> Subscribe to newsletter
                            </button>
                        </div>
                    </form>
                    <!-- /Newsletter Form -->

                    <!-- Social Icons -->
                    {{-- <div class="margin-top-20">
                        <a href="https://www.facebook.com/TheTaxFaculty/" target="_blank"
                           class="social-icon social-icon-border social-facebook pull-left" data-toggle="tooltip"
                           data-placement="top" title="Facebook">

                            <i class="icon-facebook"></i>
                            <i class="icon-facebook"></i>
                        </a> --}}

                        {{--<a href="https://twitter.com/SAAA_Accounting" target="_blank"--}}
                        {{--class="social-icon social-icon-border social-twitter pull-left" data-toggle="tooltip"--}}
                        {{--data-placement="top" title="Twitter">--}}
                        {{--<i class="icon-twitter"></i>--}}
                        {{--<i class="icon-twitter"></i>--}}
                        {{--</a>--}}

                        {{--<a href="#" class="social-icon social-icon-border social-gplus pull-left" data-toggle="tooltip"--}}
                        {{--data-placement="top" title="Google plus">--}}
                        {{--<i class="icon-gplus"></i>--}}
                        {{--<i class="icon-gplus"></i>--}}
                        {{--</a>--}}

                        {{-- <a href="https://www.linkedin.com/company/the-tax-faculty/"
                           class="social-icon social-icon-border social-linkedin pull-left"
                           data-toggle="tooltip" data-placement="top" title="Linkedin">
                            <i class="icon-linkedin"></i>
                            <i class="icon-linkedin"></i>
                        </a> --}}

                        {{--<a href="#" class="social-icon social-icon-border social-rss pull-left" data-toggle="tooltip"--}}
                        {{--data-placement="top" title="Rss">--}}
                        {{--<i class="icon-rss"></i>--}}
                        {{--<i class="icon-rss"></i>--}}
                        {{--</a>--}}

                    {{-- </div> --}}
                    <!-- /Social Icons -->
                </div>
            </div>
        </div>
    @endif


    <div class="copyright hidden-print">
        <div class="container">
            <ul class="pull-right nomargin list-inline mobile-block">
                <li><a href="/terms_and_conditions">Terms &amp; Conditions</a></li>
                <li>&bull;</li>
                <li><a href="/privacy_policy">Privacy</a></li>
            </ul>
            &copy; The Tax Faculty
        </div>
    </div>
</footer>
<!-- /FOOTER -->