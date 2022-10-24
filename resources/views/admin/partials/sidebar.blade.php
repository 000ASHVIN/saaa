<div class="sidebar app-aside" id="sidebar">
    <div class="sidebar-container perfect-scrollbar">
        <nav>
            <!-- start: SEARCH FORM -->
            <div class="search-form">
                <a class="s-open" href="#">
                    <i class="ti-search"></i>
                </a>

                <center>
                    <form role="search" method="POST" action="/admin/search">
                        {!! csrf_field() !!}
                        <br>
                        <div class="form-group">
                            <select name="tag" id="tag" class="form-control" style="width: 95%;">
                                <option value="name">Name & Surname</option>
                                <option value="email">Email Address</option>
                                <option value="cell">Cell Number</option>
                                <option value="id_number">ID Number</option>
                                <option value="invoice">Invoice Number</option>
                                <option value="order">Purchase Order</option>
                                <option value="company">Company Name</option>
                                <option value="wallet">Wallet Reference</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <input  type="text" name="search" placeholder="Search..." class="form-control" style="width: 95%;">
                        </div>

                        <div class="form-group">
                            <button type="button" style="width: 95%" onclick="spin(this)" class="btn btn-primary btn-wide btn-scroll btn-scroll-top ti-search"><span>Search Now</span></button>
                        </div>
                    </form>
                </center>
            </div>
            <!-- end: SEARCH FORM -->
            <hr>
            <!-- start: MAIN NAVIGATION MENU -->
            <ul class="main-navigation-menu">
                <li class="{{isActive('admin')}}">
                    <a href="{!! route('admin.dashboard') !!}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-home"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Dashboard </span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="{{ isActive('admin/chat', true) }}">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-angle-double-right"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Chat</span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>

                    <ul class="sub-menu" style="display: none;">

                        <li class="#">
                            <a href="{{ route('admin.chat') }}">Chats</a>
                        </li>

                        <li class="#">
                            <a href="{{ route('admin.chat.history') }}">Chat History</a>
                        </li>

                    </ul>
                </li>
                
                <li class="{{ isActive('admin.search', true) }}">
                    <a href="{!! route('admin.search') !!}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-user"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Members Search </span>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="{{ isActive('admin.index') }} {{ isActive('admin.leads.status.*') }}">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-help-alt"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Leads</span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu" style="display: none;">
                        <li class="{{ isActive('admin.leads.index') }}">
                            <a href="{!! route('admin.leads.index') !!}">
                                Lead Search
                            </a>
                        </li>
                        <li class="{{ isActive('admin.leads.status.*') }}">
                            <a href="{{ route('admin.leads.status.index') }}">Lead Statuses</a>
                        </li>
                    </ul>
                </li>
                <li class="{{ isActive('admin/plans', true) }}">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-angle-double-right"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Subscriptions</span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu" style="display: none;">
                        <li class="">
                            <a href="javascript:;">
                                <span>Professions</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display: none">
                                <li class="{{ isActive('admin.professions.index') }}">
                                    <a href="{!! route('admin.professions.index') !!}">
                                        All Profesions
                                    </a>
                                </li>
                                <li class="{{ isActive('admin.professions.create') }}">
                                    <a href="{!! route('admin.professions.create') !!}">
                                        Create New Profession
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="">
                            <a href="javascript:;">
                                <span>Subscription Plans</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display: none">
                                <li class="{{ isActive('admin.plans.index') }}">
                                    <a href="{!! route('admin.plans.index') !!}">
                                        All Subscription Plans
                                    </a>
                                </li>
                                <li class="{{ isActive('admin.plans.create') }}">
                                    <a href="{!! route('admin.plans.create') !!}">
                                        Create New Plan
                                    </a>
                                </li>
                                <li class="{{ isActive('admin.pricing_group.index') }}">
                                        <a href="{!! route('admin.pricing_group.index') !!}">
                                            Pricing Group
                                        </a>
                                    </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <span>Subscription Features</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display: none;">
                                <li class="{{ isActive('admin.plans.features.index') }}">
                                    <a href="{!! route('admin.plans.features.index') !!}">
                                        All Subscription Features
                                    </a>
                                </li>
                                <li class="{{ isActive('admin.plans.features.create') }}">
                                    <a href="{!! route('admin.plans.features.create') !!}">
                                        Create New Feature
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ isActive('admin.plans.features.byo_default.index') }}">
                            <a href="{{ route('admin.plans.features.byo_default.index') }}">
                                Default BYO features
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="{{ isActive('admin/events', true) }}">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-angle-double-right"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Events</span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu" style="display: none;">
                        <li class="">
                            <a href="javascript:;">
                                <span>General</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display: none">
                                <li class="{{ isActive('admin.events.index') }}">
                                    <a href="{!! route('admin.events.index') !!}">All Events</a>
                                </li>
                                <li class="{{ isActive('admin.synced_events.index') }}">
                                    <a href="{!! route('admin.synced_events.index') !!}">Synced Events</a>
                                </li>
                                <li class="{{ isActive('admin.event.create') }}">
                                    <a href="{{ route('admin.event.create') }}">Create Event</a>
                                </li>
                                <li class="{{ isActive('admin.promo_code.index') }}">
                                    <a href="{{ route('admin.promo_code.index') }}">Promo Codes</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <span>Event Resources</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display=none">
                                <li>
                                    <a href="{{ route('admin.webinars') }}">
                                        <span>Webinar Links & Passcode</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.event_files') }}">
                                        <span>All Resources</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <span>Event Videos</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display=none">
                                <li>
                                    <a href="{{ route('admin.videos.index') }}">
                                        <span>All Videos</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.videos.create') }}">
                                        <span>Create New</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <span>Webinar Series</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display=none">
                                <li>
                                    <a href="{{ route('admin.webinar_series.index') }}">
                                        <span>All Series</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.webinar_series.create') }}">
                                        <span>Create New</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <span>Event CPD</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display=none">
                                <li>
                                    <a href="{{ route('admin.cpd.assign') }}">
                                        <span>Assign Seminar CPD</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.cpd.assign_webinars') }}"><span>Assign Webinar</span></a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <span>Event Presenters</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display=none">
                                <li>
                                    <a href="{{ route('admin.presenters.index') }}">
                                        <span>All</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.presenters.create') }}">
                                        <span>Create New</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="{!! route('admin.events.assign-to-plans') !!}">
                                Assign to plans
                            </a>
                        </li>
                        <li class="">
                            <a href="javascript:;">
                                <span>Live Webinars</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display: none">
                                <li class="{{ isActive('admin.live_webinars.index') }}">
                                    <a href="{!! route('admin.live_webinars.index') !!}">All Webinars</a>
                                </li>
                                <li class="{{ isActive('admin.live_webinars.create') }}">
                                    <a href="{{ route('admin.live_webinars.create') }}">Create Webinar</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('dashboard.downloaded_webinars') }}">Downloaded Webinars</a>
                        </li>
                        <li class="{{ isActive('admin/folders', true) }}">
                            <a href="javascript:;">
                                <span>Event Gallery</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display: none;">
                                <li class="{{ isActive('admin.folders.index') }}">
                                    <a href="{!! route('admin.folders.index') !!}">All</a>
                                </li>

                                <li class="{{ isActive('admin.folders.create') }}">
                                    <a href="{!! route('admin.folders.create') !!}">Create New</a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ isActive('admin/event/notifications', true) }}">
                            <a href="javascript:;">
                                <span>Event Notifications</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display: none;">
                                <li class="{{ isActive('admin.event.notifications.index') }}">
                                    <a href="{!! route('admin.event.notifications.index') !!}">All</a>
                                </li>

                                <li class="{{ isActive('admin.event.notifications.create') }}">
                                    <a href="{!! route('admin.event.notifications.create') !!}">Create New</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li class="{{ isActive('admin/courses', true) }}">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-angle-double-right"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Courses</span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>

                    <ul class="sub-menu" style="display: none;">

                        <li class="#">
                            <a href="{{ route('admin.courses.index') }}">All Courses</a>
                        </li>

                        <li class="#">
                            <a href="{{ route('admin.courses.create') }}">Create New</a>
                        </li>

                    </ul>
                </li>


                <li class="{{ isActive('admin/folders', true) }}">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-angle-double-right"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">News Articles</span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>

                    <ul class="sub-menu" style="display: none;">
                        <li class="#">
                            <a href="{{ route('admin.news.index') }}">Articles</a>
                        </li>
                        <li class="#">
                            <a href="{{ route('admin.authors.index') }}">Authors</a>
                        </li>

                    </ul>
                </li>


                <li class="{{isActive('categories/index')}}">
                    <a href="{{ route('admin.categories.index') }}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-anchor"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Categories </span>
                            </div>
                        </div>
                    </a>
                </li>


                <li class="{{isActive('rewards/index')}}">
                    <a href="{{ route('admin.rewards.index') }}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-angle-double-right"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Rewards Data </span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="{{isActive('admin/industries/index')}}">
                    <a href="{{ route('admin.industries.index') }}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-angle-double-right"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Industries Data </span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="{{isActive('admin/unsubscribers/index')}}">
                    <a href="{{ route('admin.unsubscribers.index') }}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-angle-double-right"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Unsubscribers Data </span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="{{isActive('admin/resubscribers/index')}}">
                    <a href="{{ route('admin.resubscribers.index') }}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-angle-double-right"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Resubscribers Data </span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="{{isActive('sponsor/index')}}">
                    <a href="{{ route('admin.sponsor.index') }}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-angle-double-right"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Sponsor Data </span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="{{ isActive('admin.agent_groups') }} {{ isActive('admin.resource_centre.tickets.index') }}">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-help-alt"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Help Desk</span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu" style="display: none;">
                        <li class="{{ isActive('admin.agent_groups.index') }}">
                            <a href="{!! route('admin.agent_groups.index') !!}">
                                Agent Groups
                            </a>
                        </li>
                        <li class="{{ isActive('admin.resource_centre.tickets.index') }}">
                            <a href="{{ route('admin.resource_centre.tickets.index') }}">Tickets</a>
                        </li>
                    </ul>
                </li>

                <!-- Resource Centre -->
                {{-- <li class="{{ isActive('admin/folders', true) }}">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-search"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Resource Centre</span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>

                    <ul class="sub-menu" style="display: none;">
                        <li class="{{ isActive('admin/folders', true) }}">
                            <a href="javascript:;">
                                <span>Categories</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display: none;">
                                <li>
                                    <a href="{{ route('admin.resource_centre.categories.index') }}">List Categories</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.resource_centre.categories.create') }}">Create New</a>
                                </li>
                            </ul>
                        </li>

                        <li class="{{ isActive('admin/folders', true) }}">
                            <a href="javascript:;">
                                <span>Sub Categories</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display: none;">
                                <li>
                                    <a href="{{ route('admin.resource_centre.sub_categories.index') }}">List Sub Categories</a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.resource_centre.sub_categories.create') }}">Create New</a>
                                </li>
                            </ul>
                        </li>

                        <li class="{{ isActive('admin/folders', true) }}">
                            <a href="javascript:;">
                                <span> Ask an Expert</span> <i class="icon-arrow"></i>
                            </a>

                            <ul class="sub-menu" style="display: none;">
                                <li>
                                    <a href="{{ route('admin.resource_centre.tickets.index') }}">List Questions</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li> --}}

                <li class="{{ isActive('admin/professional_bodies', true) }}">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-light-bulb"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Professional Bodies</span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>

                    <ul class="sub-menu" style="display: none;">
                        <li class="{{ isActive('admin.professional_bodies.index') }}">
                            <a href="{!! route('admin.professional_bodies.index') !!}">
                                List All
                            </a>
                        </li>

                        <li class="{{ isActive('admin.professional_bodies.create') }}">
                            <a href="{!! route('admin.professional_bodies.create') !!}">
                                Create New
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ isActive('admin.stats', true) }}">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-bar-chart"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Stats </span>
                                <i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        {{--<li class="{{ isActive('admin.stats.members') }}">--}}
                            {{--<a href="{!! route('admin.stats.members') !!}">--}}
                                {{--Members--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li class="{{ isActive('admin.stats.installments') }}">--}}
                            {{--<a href="{!! route('admin.stats.installments') !!}">--}}
                                {{--Subscriptions Installments--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        <li class="{{ isActive('admin.stats.orders') }}">
                            <a href="{!! route('admin.stats.orders') !!}">
                                Store Orders
                            </a>
                        </li>
                        <li class="{{ isActive('admin.stats.registrations') }}">
                            <a href="{!! route('admin.stats.registrations') !!}">
                                Event Registrations
                            </a>
                        </li>
                        <li class="{{ isActive('admin.stats.payment_methods') }}">
                            <a href="{!! route('admin.stats.payment_methods') !!}">
                                Payment Methods
                            </a>
                        </li>
                        <li class="{{ isActive('admin.stats.cpd_courses_dashboard') }}">
                            <a href="{!! route('admin.stats.cpd_courses_dashboard') !!}">
                                CPD & Courses
                            </a>
                        </li>
                    </ul>
                </li>
                {{--<li class="{{ isActive('admin.cpd_report', true) }}">--}}
                    {{--<a href="javascript:void(0)">--}}
                        {{--<div class="item-content">--}}
                            {{--<div class="item-media">--}}
                                {{--<i class="ti-alarm-clock"></i>--}}
                            {{--</div>--}}
                            {{--<div class="item-inner">--}}
                                {{--<span class="title">CPD</span>--}}
                                {{--<i class="icon-arrow"></i>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</a>--}}
                    {{--<ul class="sub-menu">--}}
                        {{--<li class="{{ isActive('admin.cpd_report.pre_registration') }}">--}}
                            {{--<a href="{!! route('admin.cpd_report.pre_registration') !!}">--}}
                                {{--CPD Pre Registration--}}
                            {{--</a>--}}
                        {{--</li>--}}
                        {{--<li class="{{ isActive('admin.cpd_report.cpd_renewals') }}">--}}
                            {{--<a href="{!! route('admin.cpd_report.cpd_renewals') !!}">--}}
                                {{--CPD Renewals--}}
                            {{--</a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</li>--}}
                <li class="{{ isActive('admin.members', true) }}">
                    <a href="{!! route('admin.members.index') !!}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-user"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Members </span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="{{ isActive('admin/reps/', true) }}">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-plus"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Sales Reps</span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>

                    <ul class="sub-menu" style="display: none;">
                        <li class="{{ isActive('admin.reps.index', true) }}">
                            <a href="{!! route('admin.reps.index') !!}">
                                List All
                            </a>
                        </li>

                        <li class="{{ isActive('admin.reps.create', true) }}">
                            <a href="{!! route('admin.reps.create') !!}">
                                Create New
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="{{ isActive('admin.debit_orders', true) }}">
                    <a href="{!! route('admin.debit_orders.index') !!}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-money"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Debit Orders</span>
                            </div>
                        </div>
                    </a>
                </li>
                {{--<li class="{{ isActive('admin.custom_payments_selected', true) }}">--}}
                    {{--<a href="{!! route('admin.custom_payments_selected') !!}">--}}
                        {{--<div class="item-content">--}}
                            {{--<div class="item-media">--}}
                                {{--<i class="ti-money"></i>--}}
                            {{--</div>--}}
                            {{--<div class="item-inner">--}}
                                {{--<span class="title">Custom Payments</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</a>--}}
                {{--</li>--}}
                <li class="{{ isActive('admin.roles', true) }}">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-lock"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Roles & Permissions </span>
                                <i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="javascript:;">
                                <span>Roles</span>
                                <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="{{ route('admin.member_roles') }}">
                                        View Available roles
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.member_roles.create') }}">
                                        Create New Role
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:;">
                                <span>Permisions</span>
                                <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu">
                                <li>
                                    <a href="{{ route('admin.permissions') }}">
                                        Show all Permissions
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.permissions_new') }}">
                                        Create new Permission
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.permissions_assign') }}">
                                        Assign Permission to Role
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-package"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Store </span>
                                <i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="{{ route('admin.store_categories.index') }}">
                                <span>Store Categories</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.listings.index') }}">
                                <span>Store Listings</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.products.index') }}">
                                <span>Store Products</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.orders.index') }}">
                                <span>Store Orders</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{isActive('admin.assessments')}}">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-check-box"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Assessments </span>
                                <i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="{{ route('admin.assessments.index') }}">
                                <span>All</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{isActive('admin.jobs')}}">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-suitcase"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Careers </span>
                                <i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="{{route('jobs.index')}}">
                                <span class="title">View Available Jobs</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin.departments.create')}}">
                                <span class="title">Create a New Department</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('jobs.create')}}">
                                <span class="title">Create a New Job</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('applications')}}">
                                <span class="title">View Job Applications</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="#">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-list-ol"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> FAQ </span>
                                <i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="{{ route('faq.categories') }}">
                                <span class="title">FAQ Categories</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('faq.tags') }}">
                                <span class="title">Create New Category</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('faq.questions') }}">
                                <span class="title">Questions & Answers</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('faq.all') }}">
                                <span class="title">Show All</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-import"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Imports </span>
                                <i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <?php $importProviderMenuEntries = App\ImportProvider::getMenuEntries(); ?>
                        @foreach($importProviderMenuEntries as $providerId => $providerMenuText)
                            <li>
                                <a href="javascript:;">
                                    <span>{{ $providerMenuText }}</span>
                                    <i class="icon-arrow"></i>
                                </a>

                                <ul class="sub-menu">
                                    <li>
                                        <a href="{!! route('admin.import.provider',[$providerId]) !!}">
                                            New import
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{!! route('admin.import.provider.imports',[$providerId]) !!}">
                                            <span>Imports</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endforeach
                            <li>
                                <a href="javascript:;">
                                    <span>Claimed / Refunded Invoices</span>
                                    <i class="icon-arrow"></i>
                                </a>

                                <ul class="sub-menu">
                                    <li>
                                        <a href="/admin/imports/claimed_invoices">
                                            New import
                                        </a>
                                    </li>
                                </ul>
                            </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-export"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Exports </span>
                                <i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="{{ route('admin.exports.event_registrations') }}">
                                <span>Event Registrations</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.exports.export_email_address') }}">
                                <span>Email Lists</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-suitcase"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Reports </span>
                                <i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <li class="">
                            <a href="javascript:;">
                                <span>Sales Report</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display: none">
                                <li>
                                    <a href="{{ route('admin.reports.sales.agent_report') }}">
                                        <span class="title">Agent Report</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="">
                            <a href="javascript:;">
                                <span>Course Download Brochure</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display: none">
                                <li>
                                    <a href="{{ route('admin.reports.payments.download_course') }}">
                                        <span class="title">Brochure Download Report</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="">
                            <a href="javascript:;">
                                <span>Talk to a Human</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display: none">
                                <li>
                                    <a href="{{ route('admin.reports.payments.talk_to_human') }}">
                                        <span class="title">Talk to a Human Report</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="">
                            <a href="javascript:;">
                                <span>CPD Members</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display: none">
                                <li>
                                    <a href="{{ route('admin.reports.payments.cpd-members-report') }}">
                                        <span class="title">CPD Members Report</span>
                                    </a>
                                </li>
                            </ul>
                        </li>


                        <li class="">
                            <a href="javascript:;">
                                <span>Ledger & Income</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display: none">
                                <li>
                                    <a href="{{route('admin.reports.payments.ledger')}}">
                                        <span class="title">Global Ledger</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('admin.reports.payments.income')}}">
                                        <span class="title">Income Report</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{route('admin.payments.payments_per_day')}}">
                                        <span class="title">View payments</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{route('admin.payments.payments_per_day.summary')}}">
                                        <span class="title">Payments Summary</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="">
                            <a href="javascript:;">
                                <span>U-Wallet</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display: none">
                                <li>
                                    <a href="{{ route('admin.wallet_transactions') }}">
                                        <span class="title">Wallet Transactions</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="">
                            <a href="javascript:;">
                                <span>Debtors Report</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display: none">
                                {{--<li>--}}
                                    {{--<a href="{{route('admin.reports.payments.debtors')}}">--}}
                                        {{--<span class="title">Debtors Report</span>--}}
                                    {{--</a>--}}
                                {{--</li>--}}

                                <li>
                                    <a href="{{route('admin.reports.payments.custom-transactions')}}">
                                        <span class="title">Custom Debtors Export</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{route('admin.reports.payments.aging_report')}}">
                                        <span class="title">Download Aging Report</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="">
                            <a href="javascript:;">
                                <span>Invoices Reports</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display: none">
                                <li>
                                    <a href="{{route('admin.reports.payments.claim_invoices')}}">
                                        <span class="title">Claim Invoices</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('admin.reports.payments.outstanding-invoices')}}">
                                        <span class="title">Outstanding Invoices</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{route('admin.reports.payments.credited_invoices')}}">
                                        <span class="title">Credited Invoices</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('admin.reports.payments.outstanding_p_p')}}">
                                        <span class="title">Outstanding P.P</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('admin.reports.payments.extract_invoices')}}">
                                        <span class="title">Extract Invoices</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('admin.reports.payments.extract_transactions')}}">
                                        <span class="title">Extract Transactions</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{route('admin.reports.payments.monthly_income_report')}}">
                                        <span class="title">Monthly Income Report</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="">
                            <a href="javascript:;">
                                <span>Invoice Orders</span> <i class="icon-arrow"></i>
                            </a>
                            <ul class="sub-menu" style="display: none">
                                <li>
                                    <a href="{{route('admin.reports.payments.purchase_orders.extract')}}">
                                        <span class="title">Extract Orders</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="{{route('admin.reports.payments.outstanding_orders_p_p')}}">
                                        <span class="title">Outstanding P.P</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="">
                            <a href="{{route('admin.reports.reward_export')}}">
                                <span>Reward Reports</span> 
                            </a>
                            
                        </li>


                        <li class="">
                            <a href="{{route('admin.reports.professional_body')}}">
                                <span>Professional Body Reports</span> 
                            </a>
                        </li>
                        
                        <li class="">
                            <a href="{{route('admin.reports.upcoming_renewal')}}">
                                <span>Upcoming Renewal Reports</span> 
                            </a>
                        </li>

                        <li class="">
                            <a href="{{route('admin.reports.events.stat.extract')}}">
                                <span>Event Stat Reports</span> 
                            </a>
                        </li>

                        <li class="">
                            <a href="{{route('admin.reports.wod_export')}}">
                                <span>WOD's Reports</span>
                            </a>
                        </li>

                        <li class="">
                            <a href="{{route('admin.reports.get_courses_report')}}">
                                <span>Courses Reports</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-envelope"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Email Subscribers </span>
                                <i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="{{ route('NewsletterSubscribers') }}">
                                <span>View Email Subscribers</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="{{ isActive('admin.settings', true) }}">
                    <a href="{!! route('admin.settings') !!}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-anchor"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Settings </span>
                            </div>
                        </div>
                    </a>
                </li>
                {{-- <li class="{{ isActive('upgrade_subscription.pendinglist', true) }}">
                    <a href="{!! route('upgrade_subscription.pendinglist') !!}">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-anchor"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> Upgrade Request </span>
                            </div>
                        </div>
                    </a>
                </li> --}}

                <li>
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-package"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title"> SEO Module </span>
                                <i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu">
                        <li>
                            <a href="{{ route('seo') }}">
                                <span>SEO Module</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.redirect.index') }}">
                                <span>Redirect Rules</span>
                            </a>
                        </li>
                    </ul>
                </li>

                @if (env('APP_THEME') == 'taxfaculty')
                    <li>
                        <a href="{{ route('admin.donations.index') }}">
                            <div class="item-content">
                                <div class="item-media">
                                    <i class="ti-money"></i>
                                </div>
                                <div class="item-inner">
                                    <span class="title"> Donations </span>
                                </div>
                            </div>
                        </a>
                    </li>
                @endif


            </ul>
        </nav>
    </div>
</div>