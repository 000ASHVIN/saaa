<div id="header" class="sticky header-md light clearfix hidden-print" style="text-transform: uppercase !important;">
    <header id="topNav">
        <div class="container">

            <button class="btn btn-mobile" data-toggle="collapse" data-target=".nav-main-collapse">
                <i class="fa fa-bars"></i>
            </button>


            <ul class="pull-right nav nav-pills nav-second-main">
                @if(App\Store\Cart::getTotalQuantity() > 0)
                    <li class="quick-cart">
                        <a href="#">
                            <span class="badge badge-aqua btn-xs badge-corner">{{ App\Store\Cart::getTotalQuantity() }}</span>
                            <i class="fa fa-shopping-cart"></i>
                        </a>
                        <div class="quick-cart-box">
                            <h4>Shoping Cart</h4>
                            <div class="quick-cart-wrapper" style="overflow: hidden;">

                                <?php $cartItems = App\Store\Cart::getAllCartProductListings(); ?>
                                @foreach($cartItems as $cartItem)
                                    <a href="{{ $cartItem->listingUrl }}"><!-- cart item -->
                                        <div class="row">
                                            <div class="col-sm-9"><h6><span>{{ $cartItem->qty }}
                                                        x</span> {{ $cartItem->title }}</h6></div>
                                            <div class="col-sm-3">
                                                <small>{{ currency($cartItem->discountedPrice) }}</small>
                                            </div>
                                        </div>
                                    </a><!-- /cart item -->
                                @endforeach
                            </div>

                            <!-- quick cart footer -->
                            <div class="quick-cart-footer clearfix">
                                <a href="{{ route('store.cart') }}" class="btn btn-primary btn-xs pull-right">VIEW
                                    CART</a>
                                <span class="pull-left"><strong>TOTAL: {{ currency(App\Store\Cart::getTotalDiscountedPrice()) }}</strong></span>
                            </div>
                        </div>
                    </li>
                @endif
            </ul>

            <a class="logo pull-left hidden-xs hidden-sm hidden-md" href="{{ '/' }}">
                <img src="{{ Theme::asset('img/logo.png') }}" alt="Logo" style="height: 70%; margin-top: 8px;">
            </a>

            <a class="logo pull-left visible-xs visible-sm visible-md" href="{{ '/' }}">
                <img src="{{ Theme::asset('img/logo.png') }}" alt="Logo">
            </a>

            <div class="navbar-collapse pull-right nav-main-collapse collapse submenu-dark">
                <nav class="nav-main">
                    <ul id="topMain" class="nav nav-pills nav-main">
                        {{-- <li><a href="{!! '/' !!}">Home</a></li> --}}

                        <li class="dropdown">
                            <a class="dropdown-toggle" href="#">
                                COURSES
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="" href="/courses">Occupational certificates</a></li>
                                <li><a class="" href="/courses">Professional certificates</a></li>
                                <li><a class="" href="/courses">Short courses</a></li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a class="dropdown-toggle" href="">CPD</a>
                            <ul class="dropdown-menu">
                                <li><a class="" href="/subscription_plans">Subscription Plans</a></li>
                                <li><a class="" href="/profession/tax-technician">Tax Technician</a></li>
                                {{-- <li><a class="" href="/profession/general-tax-practitioner">Tax Practitioner</a></li> --}}
                                <li><a class="" href="/profession/tax-and-accounting">Tax Accountant</a></li>
                                <li><a class="" href="/profession/custom-practitioner">Build Your Own</a></li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a class="dropdown-toggle" href="#">
                                Events
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="" href="{{ route('calendar') }}">Events Calendar</a></li>
                                <li><a class="" href="{{ '/events' }}">Upcoming events</a></li>
                                <li><a class="" href="{{ '/events/past' }}">Past events &amp; recordings</a></li>
                                {{-- <li><a class="" href="{{ route('webinars_on_demand.home') }}">Webinars On-Demand</a></li> --}}
                            </ul>
                        </li>

                        <li>
                            <a  href="{{ route('webinars_on_demand.home') }}">
                                ON-DEMAND 
                            </a>
                        </li>

                        <li>
                            <a  href="{{ route('resource_centre.home') }}">
                                Resource Centre 
                            </a>
                        </li>

                        <li class="dropdown">
                            <a class="dropdown-toggle" href="#">
                                About
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="" href="{!! route('about') !!}">We are a NPO</a></li>
                                <li><a class="" href="{!! '/ourteam' !!}">Our Team</a></li>
                                <li><a class="" href="#">Accreditation</a></li>
                                 <li><a class="" href="https://courses.taxfaculty.ac.za/fund-a-learner/">Fund a Learner</a></li> 
                                <!-- <li><a class="" href="{!! '/presenters' !!}">Our Presenters</a></li> -->
                                <li><a class="" href="{!! '/faq' !!}">FAQs</a></li>
                                {{--<li><a href="{{ route('rewards.index') }}">Rewards</a></li>--}}
                                {{--<li><a class="" href="{!! '/partners' !!}">Our Partners</a></li>--}}
                                <li><a href="{{ '/contact' }}">Contact Us</a></li>
                                {{-- <li><a href="{{ '/terms_and_conditions' }}">Terms and Conditions</a></li>
                                <li><a href="{{ '/privacy_policy' }}">Privacy Policy</a></li> --}}

                            </ul>
                        </li>

{{--                        <li><a href="{!! route('resource_centre.home') !!}">Resource Centre</a></li>--}}
                        {{--<li style="background-color: black; background-image: url('https://imageshack.com/a/img923/8140/VeRFYZ.jpg')"><a style="color: red; font-weight: bold" href="{{ route('bf') }}">Black Friday</a></li>--}}
                        {{-- <li class="dropdown">
                            <a class="dropdown-toggle" href="/store">
                                Shop
                            </a>

                            <ul class="dropdown-menu">
                                <li><a class="" href="{{ route('store.index') }}">Shop</a></li>
                            </ul>
                        </li> --}}

                        {{--<li class="dropdown">--}}
                            {{--<a href="#" class="dropdown-toggle">Courses</a>--}}
                            {{--<ul class="dropdown-menu">--}}
                                {{--<li><a href="{{ route('courses.certificate_courses') }}">Internal Courses</a></li>--}}
                                {{--<li><a href="{{ route('courses.external_courses') }}">External Courses</a></li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}

                        {{--<li><a href="/store">Shop</a></li>--}}


                        @if(auth()->user())
                            <li class="dropdown">
                                <a class="dropdown-toggle" href="#">
                                    <i class="fa fa-user-secret"></i> My Account
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="{{ isActive('dashboard.general.index', true) }}" href="{{ route('dashboard.general.index') }}">My Dashboard</a></li>
                                    <li><a class="{{ isActive('dashboard.events', true) }}" href="{{ route('dashboard.events') }}">My Events</a></li>
                                    <li><a class="{{ isActive('dashboard.webinars_on_demand.index', true) }}" href="{{ route('dashboard.webinars_on_demand.index') }}">My Webinars on Demand</a></li>
                                    <li><a class="{{ isActive('dashboard.courses.index', true) }}" href="{{ route('dashboard.courses.index') }}">My Courses</a></li>

                                    @if(auth()->check() && auth()->user()->ViewResourceCenter())
                                        <li><a class="" href="{{ route('resource_centre.home') }}">Resource Centre</a></li>
                                    @endif

                                    <li><a class="" href="{{ route('dashboard.cpd') }}">My CPD Logbook</a></li>
                                    
                                    <li>
                                        <a class=" dropdown-btn"> <span style="padding-right: 4px;">My Account</span>
                                            <i class="fa fa-caret-down"></i>
                                        </a>
                                        <ul  class="nav nav-tabs nav-stacked nav-alternate" style="list-style-type:none; border-bottom: none;margin-left: 34px !important;display:none">
                                            <li class="{{ isActive(['dashboard.billing.index']) }}"><a href="{!! route('dashboard.billing.index') !!}"> My Billing</a></li>
                                            {{-- <li class="{{ isActive(['dashboard.wallet.index']) }}"><a href="{!! route('dashboard.wallet.index') !!}">  My U-Wallet</a></li> --}}
                                            <li class="{{ isActive(['dashboard.invoices','dashboard.invoices.overdue']) }}"><a href="{!! route('dashboard.invoices') !!}"> My Invoices</a></li>
                                            <li class="{{ isActive(['dashboard.invoice_orders','dashboard.invoice_orders']) }}"><a href="{!! route('dashboard.invoice_orders') !!}"> My Purchase Orders</a></li>
                                            {{-- <li class="{{ isActive('dashboard.statement') }}"><a href="{{ route('dashboard.statement') }}"> Account Statement</a></li> --}}
                                            <li class="{{ isActive('dashboard.edit', true) }}"><a href="{!! route('dashboard.edit') !!}"> Edit Profile</a></li>
                                        </ul>
                                    </li>

                                    {{-- <li><a class="" href="{{ route('dashboard.billing.index') }}">My Billing</a></li>
                                    <li><a class="" href="{{ route('dashboard.invoices') }}">My Invoices</a></li>
                                    <li><a class="" href="{{ route('dashboard.invoice_orders') }}">My Purchase Orders</a></li>
                                    <li><a class="" href="{{ route('dashboard.edit') }}">Edit Profile</a></li> --}}

                                    <li><a class="" href="{{ '/auth/logout' }}">Log Out</a></li>
                                </ul>
                            </li>
                        @endif


                    </ul>
                </nav>

            </div>

        </div>
    </header>

</div>