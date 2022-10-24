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
                <img src="/assets/frontend/images/logo_light.png" alt="Logo">
            </a>

            <a class="logo pull-left visible-xs visible-sm visible-md" href="{{ '/' }}">
                <img src="//imageshack.com/a/img922/7016/Hr9euC.png" alt="Logo">
            </a>

            <div class="navbar-collapse pull-right nav-main-collapse collapse submenu-dark">
                <nav class="nav-main">
                    <ul id="topMain" class="nav nav-pills nav-main">
                        <li><a href="{!! '/' !!}">Home</a></li>

                        <li class="dropdown">
                            <a class="dropdown-toggle" href="#">
                                About
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="" href="{!! '/faq' !!}">FAQ</a></li>
                                <li><a href="{{ route('news.index') }}">News</a></li>
                                <li><a href="{{ route('rewards.index') }}">Rewards</a></li>
                                <li><a class="" href="{!! '/staff' !!}">Our Staff</a></li>
                                <li><a class="{{ isActive('about') }}" href="{!! route('about') !!}">About Us</a></li>
                                {{--<li><a class="" href="{!! '/partners' !!}">Our Partners</a></li>--}}
                                <li><a href="{{ '/contact' }}">Contact Us</a></li>
                                <!-- <li><a class="" href="{!! '/presenters' !!}">Our Presenters</a></li> -->
                                <li><a class="" href="{!! route('wod_index') !!}">Webinars On-Demand</a></li>
                            </ul>
                        </li>



                        <li class="dropdown">
                            <a class="dropdown-toggle" href="">CPD Subscriptions</a>
                            <ul class="dropdown-menu">
                                {{-- <li><a class="" href="{{ route('cpd') }}">CPD Subscriptions</a></li> --}}
                                <li><a class="" href="/subscription_plans">Subscription Plans</a></li>
                                <li><a class="" href="/profession/chartered-accountant">CPD for Chartered Accountants</a></li>
                                <li><a class="" href="/profession/professional-accountant">CPD for Professional Accountants</a></li>
                                {{--  <li><a class="" href="/profession/business-accountant-in-practice">CPD for Business Accountant</a></li>  --}}
                                <li><a class="" href="/profession/tax-practitioner">CPD for Tax Practitioners</a></li>
                                {{--<li><a class="" href="/profession/company-secretary">CPD for Company Secretaries</a></li>--}}
                                <li><a class="" href="/profession/certified-bookkeeper">CPD for Bookkeepers</a></li>
                                <li><a class="" href="/profession/monthly-legislation-update">CPD: Compliance update</a></li>
                                <li><a class="" href="/profession/build-your-own">CPD: Build your own</a></li>
                                <li><a class="" href="/profession/practice-management">CPD Series: Practice Management</a></li>
                            </ul>
                        </li>

                        <li class="dropdown">
                            <a class="dropdown-toggle" href="#">
                                Events
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="" href="{{ route('calendar') }}">Events Calendar</a></li>
                                <li><a class="" href="{{ '/events' }}">View upcoming events</a></li>
                                <li><a class="" href="{{ '/events/past' }}">Past events &amp; recordings</a></li>
                                <li><a class="" href="{{ route('webinars_on_demand.home') }}">Webinars On-Demand</a></li>
                            </ul>
                        </li>

                        <li><a href="{!! route('resource_centre.home') !!}">Resource Centre</a></li>
                        {{--<li style="background-color: black; background-image: url('https://imageshack.com/a/img923/8140/VeRFYZ.jpg')"><a style="color: red; font-weight: bold" href="{{ route('bf') }}">Black Friday</a></li>--}}
                        <li class="dropdown">
                            <a class="dropdown-toggle" href="/store">
                                Shop
                            </a>

                            <ul class="dropdown-menu">
                                <li><a class="" href="{{ route('store.index') }}">Shop</a></li>
                                <li><a class="" href="/courses">Courses</a></li>
                            </ul>
                        </li>

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
                                    <li><a class="{{ isActive('dashboard.general.index', true) }}" href="{{ route('dashboard.general.index') }}">Self Service</a></li>
                                    <li><a class="{{ isActive('dashboard.events', true) }}" href="{{ route('dashboard.events') }}">My Events</a></li>
                                    <li><a class="{{ isActive('dashboard.webinars_on_demand.index', true) }}" href="{{ route('dashboard.webinars_on_demand.index') }}">My Webinars on Demand</a></li>
                                    <li><a class="" href="{{ route('dashboard.cpd') }}">My CPD</a></li>
                                    <li><a class="" href="{{ route('dashboard.invoices') }}">Invoices</a></li>
                                    <li><a class="" href="{{ route('dashboard.invoice_orders') }}">Orders</a></li>
                                    <li><a class="" href="{{ route('dashboard.billing.index') }}">My Billing</a></li>
                                    <li><a class="" href="{{ route('dashboard.edit') }}">Edit Profile</a></li>

                                    @if(auth()->check() && auth()->user()->ViewResourceCenter())
                                        <li><a class="" href="{{ route('resource_centre.home') }}">Resource Centre</a></li>
                                    @endif

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