<!-- FOOTER -->
<footer id="footer" class="hidden-print">
    @if(Request::is('dashboard')||
        Request::is('dashboard/edit')||
        Request::is('dashboard/events')||
        Request::is('dashboard/cpd')||
        Request::is('dashboard/invoices')
    )

    @else
        <div class="container hidden-print">
            <div class="row">
                <div class="col-md-3">
                    <h4 class="letter-spacing-1">Contact Us</h4>
                    <address>
                        <ul class="list-unstyled">
                            <li class="footer-sprite address">
                                Ground Floor, Block 3<br>
                                Fourways Office Park,<br>
                                Cnr Roos and Fourways Boulevard<br>
                                Fourways<br>
                            </li>
                            <li class="footer-sprite phone">
                                Phone: 010 593 0466
                            </li>
                            <li class="footer-sprite email">
                                <a href="mailto:support@accountingacademy.co.za">support@accountingacademy.co.za</a>
                            </li>
                        </ul>
                    </address>
                    <!-- /Contact Address -->

                </div>

                <div class="col-md-3">

                    <!-- Links -->
                    <h4 class="letter-spacing-1">EXPLORE SAAA</h4>
                    <ul class="footer-links list-unstyled">
                        <li><a href="/">Home</a></li>
                        <li><a href="/about">About Us</a></li>
                        <li><a href="/staff">Our Team</a></li>
                        <li><a href="/presenters">Our Presenters</a></li>
                        {{--<li><a href="/partners">Our Partners</a></li>--}}
                        <li><a href="/cpd">CPD Subscription</a></li>
                        <li><a href="/contact">Contact Us</a></li>
                    </ul>
                    <!-- /Links -->

                </div>

                <div class="col-md-3">

                    <!-- Newsletter Form -->
                    <h4 class="letter-spacing-1">KEEP IN TOUCH</h4>
                    <p>Subscribe to our mailing list and we'll send you an email when we have a new event coming up.</p>

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
                            <input type="text" class="form-control required" name="first_name" style="margin: 2px 2px 5px;" placeholder="First Name">
                            <input type="text" class="form-control" name="last_name" style="margin: 2px 2px 5px;" placeholder="Last Name">
                        </div>

                        <div style="display: flex">
                            <input type="email" id="email" name="email" class="form-control required" placeholder="Enter your Email" style="margin: 2px 2px 5px;">
                        </div>

                        <div class="input-group-addon" style="border: none; padding: 2px; color: white; background-color: #800000; border-color: #800000;">
                            <button class="btn btn-success btn" style="border: none; padding: 2px; color: white; background-color: #800000; border-color: #800000;" type="submit"> <i class="fa fa-envelope"></i> Subscribe to newsletter </button>
                        </div>
                    </form>
                    <!-- /Newsletter Form -->

                    <!-- Social Icons -->
                    <div class="margin-top-20">
                        <a href="https://www.facebook.com/SaAccountingAcademy/" target="_blank"
                           class="social-icon social-icon-border social-facebook pull-left" data-toggle="tooltip"
                           data-placement="top" title="Facebook">

                            <i class="icon-facebook"></i>
                            <i class="icon-facebook"></i>
                        </a>

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

                        <a href="https://www.linkedin.com/company/sa-accounting-academy-saaa/" class="social-icon social-icon-border social-linkedin pull-left"
                           data-toggle="tooltip" data-placement="top" title="Linkedin">
                            <i class="icon-linkedin"></i>
                            <i class="icon-linkedin"></i>
                        </a>

                        {{--<a href="#" class="social-icon social-icon-border social-rss pull-left" data-toggle="tooltip"--}}
                           {{--data-placement="top" title="Rss">--}}
                            {{--<i class="icon-rss"></i>--}}
                            {{--<i class="icon-rss"></i>--}}
                        {{--</a>--}}

                    </div>
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
            &copy; South African Accounting Academy
        </div>
    </div>
</footer>
<!-- /FOOTER -->