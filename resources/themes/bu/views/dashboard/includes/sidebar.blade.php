<div class="col-lg-3 col-md-3 col-sm-4 hidden-print">
    <div class="thumbnail profile-pic text-center hidden-sm hidden-xs" style="width: 100%;">
        <div class="fileinput-new" style="height: 100%; width: 100%">
            <img src="{{ asset($user->avatar) }}"
                 alt="{{ $user->first_name }} {{ $user->last_name }}"
                 onError="this.onerror=null; this.src='http://imageshack.com/a/img923/8590/LXahIE.png'" class="sidebar-image-profile">

                @if(count($user->hasCompany()))
                    <div class="ribbon">
                        <div class="ribbon-inner">Practice</div>
                    </div>
                @endif

        </div>
        <div class="edit"><a style="vertical-align: middle; position: relative; top: 100px;" href="{{ '/dashboard/edit/avatar' }}"><i class="fa fa-upload fa-lg fa-3x"></i></a></div>
    </div>

    @if($user->balanceInRands() > 0)
        <a href="{{ route('dashboard.invoices') }}">
            <div class="alert alert-danger margin-bottom-20">
                <strong style="font-weight: 600">Your Account Balance: </strong> R{{ number_format($user->balanceInRands(), 2) }}
            </div>
        </a>
    @else
        <a href="{{ route('dashboard.invoices') }}">
            <div class="alert alert-success margin-bottom-20">
                <strong style="font-weight: 600">Your Account Balance: </strong> R{{ number_format($user->balanceInRands(), 2) }}
            </div>
        </a>
    @endif

    <div class="border-box text-center" style="background-color: white">
        <h2 class="size-18 margin-bottom-0">{{ $user->first_name }} {{ $user->last_name }}</h2>
        <p>Member Since <i>{{ $user->created_at->diffForHumans() }}</i></p>

        <div class="label label-default">
            {{ ($user->subscribed('cpd')? $user->subscription('cpd')->plan->name : "No Membership") }}
        </div>

        <div class="margin-top-20"></div>

        @if($user->overdueInvoices()->where('type', 'subscription')->count() > 0)
            <a href="{{ route('dashboard.invoices') }}" class="btn btn-danger btn-block"><i class="fa fa-close"></i> Account Suspended</a>
        @else
            @if(! $user->subscribed('cpd'))
                <hr>

                @if(! $user->hasCompany())
                    <a href="/cpd" class="btn btn-success btn-block">Signup for Subscription</a>
                @else
                    <i class="fa fa-users"></i> Shared Subscription
                    <hr>
                    <div class="label label-default">
                        <strong>{{ ucfirst($user->hasCompany()->company->title) }}</strong>
                    </div>
                @endif

            @elseif($user->subscribed('cpd') && $user->subscription('cpd')->plan->name == 'Free Plan')
                <a href="/cpd" class="btn btn-success btn-block">Upgrade Subscription</a>
            @endif
        @endif
        <div class="margin-top-20"></div>
    </div>

    {{--@if(count($user->hasCompany()))--}}
        {{--<div class="margin-top-20"></div>--}}
        {{--<div class="border-box text-center" style="background-color: rgb(250, 250, 250)">--}}
            {{--<i class="fa fa-users"></i> Shared Subscription--}}
            {{--<hr>--}}
            {{--<strong>{{ ucfirst($user->hasCompany()->company->title) }}</strong>--}}
            {{--<hr>--}}
        {{--</div>--}}
    {{--@endif--}}

    @if(count($user->cycles))
        <div class="margin-top-20"></div>
        <div class="border-box text-center" style="background-color: rgb(250, 250, 250)">
            <h4>My CPD Compliance</h4>
            @foreach($user->cycles as $cycle)
                <small><strong>{{ date_format($cycle->start_date, 'd F Y') }} - {{ date_format($cycle->end_date, 'd F Y') }}</strong></small>
                <hr>
                @include('dashboard.includes.CpdCycle.cycle_progess')
            @endforeach
            <hr>
        </div>
    @endif

    <div class="margin-top-20"></div>
    <ul class="nav nav-tabs nav-stacked nav-alternate">
        <li class="{{ isActive('dashboard.general') }}"><a href="{!! route('dashboard.general.index') !!}"><i class="fa fa-angle-double-right"></i> Self Service</a></li>
        <li class="{{ isActive('dashboard.webinars_on_demand', true) }}"><a href="{{ route('dashboard.webinars_on_demand.index') }}"><i class="fa fa-angle-double-right"></i> My Webinars</a></li>
        <li class="{{ isActive('dashboard') }}"><a href="{!! route('dashboard') !!}"><i class="fa fa-angle-double-right"></i> My Dashboard</a></li>

        @if($user->subscribed('cpd') && $user->subscription('cpd')->plan->is_practice)
            <li class="{{ isActive('dashboard.company*') }}"><a href="{!! route('dashboard.company.index') !!}"><i class="fa fa-angle-double-right"></i> My Company</a></li>
        @endif

        <li class="{{ isActive(['dashboard.billing.index']) }}"><a href="{!! route('dashboard.billing.index') !!}"><i class="fa fa-angle-double-right"></i> My Billing</a></li>
        <li class="{{ isActive(['dashboard.wallet.index']) }}"><a href="{!! route('dashboard.wallet.index') !!}"> <i class="fa fa-angle-double-right"></i> My U-Wallet</a></li>
        <li class="{{ isActive(['dashboard.events','dashboard.tickets.links-and-resources']) }}"><a href="{!! route('dashboard.events') !!}"><i class="fa fa-angle-double-right"></i> My Events</a></li>
        <li class="{{ isActive(['dashboard.courses']) }}"><a href="{!! route('dashboard.courses.index') !!}"><i class="fa fa-angle-double-right"></i> My Courses</a></li>
        <li class="{{ isActive(['dashboard.articles']) }}"><a href="{!! route('dashboard.articles') !!}"><i class="fa fa-angle-double-right"></i> My Articles</a></li>
        <li class="{{ isActive('dashboard.products') }}"><a href="{!! route('dashboard.products') !!}"><i class="fa fa-angle-double-right"></i> My Products</a></li>
        <li class="{{ isActive('dashboard.cpd') }}"><a href="{!! route('dashboard.cpd') !!}"><i class="fa fa-angle-double-right"></i> My CPD</a>
        <li class="{{ isActive(['dashboard.invoices','dashboard.invoices.overdue']) }}"><a href="{!! route('dashboard.invoices') !!}"><i class="fa fa-angle-double-right"></i> My Invoices</a>
        <li class="{{ isActive(['dashboard.invoice_orders','dashboard.invoice_orders']) }}"><a href="{!! route('dashboard.invoice_orders') !!}"><i class="fa fa-angle-double-right"></i> My Orders</a><li class="{{ isActive(['dashboard.rewards','dashboard.rewards']) }}"><a href="{!! route('dashboard.rewards') !!}"><i class="fa fa-angle-double-right"></i> My Rewards</a>
        <li class="{{ isActive('dashboard.statement') }}"><a href="{{ route('dashboard.statement') }}"><i class="fa fa-angle-double-right"></i> Account Statement</a></li>
        <li class="{{ isActive('dashboard.edit', true) }}"><a href="{!! route('dashboard.edit') !!}"><i class="fa fa-angle-double-right"></i> Edit Profile</a>
        @if($user->subscribed('cpd') && $user->subscription('cpd')->plan->price != '0')
            <li class="{{ isActive('dashboard.support_tickets', true) }}"><a href="{!! route('dashboard.support_tickets') !!}"><i class="fa fa-angle-double-right"></i> Tickets</a>
            <li class="{{ isActive('resource_centre.home', true) }}"><a href="{!! route('resource_centre.home') !!}"><i class="fa fa-angle-double-right"></i> Resource Centre</a>
        @endif
        <li class="{{ isActive('faq', true) }}"><a href="{!! route('faq') !!}"><i class="fa fa-angle-double-right"></i> FAQ</a>
        <li><a href="{{ '/auth/logout' }}"><i class="fa fa-angle-double-right"></i> Log Out </a></li>
    </ul>
</div>