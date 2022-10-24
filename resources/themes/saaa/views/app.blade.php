@include('includes.header')
{{-- @include('includes.slider-top') --}}

<div id="wrapper">
    @include('includes.top-bar')
    @include('includes.nav')
    <a href="/rewards/show/draftworx" class="btn btn-xlg btn-primary size-10 fullwidth nomargin bopadding noradius ">
        <img src="/assets/frontend/images/demo/1200x800/Draftworx 728 x 90 - Leaderboard Banner 28 feb.png" alt="Slide 2" >
    </a>
    @if(Request::is('/'))
        @include('includes.slider')
    @elseif(
        Request::is('auth/login') ||
        Request::is('register_test') ||
        Request::is('join')||
        Request::is('login')||
        Request::is('subscriptions')||
        Request::is('auth/register')||
        Request::is('dashboard/edit/privacy')||
        Request::is('dashboard/courses')||
        Request::is('dashboard/wallet')||
        Request::route()->getName() == 'dashboard.assessments.show'||
        Request::route()->getName() == 'dashboard.invoices.overdue'||
        Request::is('password/email')||
        Request::is('password/reset/*') ||
        Request::is('test') ||
        Request::is('BlackFriday') ||
        Request::is('BlackFriday/Draftworx') ||
        Request::is('subscriptions/2020/BlackFriday') ||
        Request::is('subscriptions/2020/one-day-only') ||
        Request::is('subscriptions/2019/One-Day-Only') ||
        Request::is('subscriptions/2020/One-Day-Only') ||
        Request::is('subscriptions/LastMinute') ||
        Request::is('events/*') ||
        Request::is('profession/*') ||
        Request::is('resource_centre') ||
        Request::is('subscriptions/2018/saiba_member') ||
        Request::is('support_tickets/thread/*') ||
        Request::is('resource_centre/*') ||
        Request::is('support_tickets/create')
    )
    @else
        @include('includes.page-header')
    @endif

    <div id="app">
        @include('includes.login')
        @include('includes.quick_reg')
        @if(auth()->check())
        @include('subscriptions.2017.include.debit')
        @endif
        @yield('content')

        <!-- Need Help Button -->
        @include('resource_centre.includes.support-ticket')
        
    </div>
    @include('includes.newsletter_popup')
    @include('includes.top-footer')

    @if(auth()->user()) 
        @include('includes.chatbox')
    @endif
</div>

@include('includes.side-panel')
@include('includes.footer')