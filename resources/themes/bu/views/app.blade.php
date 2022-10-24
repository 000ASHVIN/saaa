@include('includes.header')
{{-- @include('includes.slider-top') --}}

<div id="wrapper">
    @include('includes.top-bar')
    @include('includes.nav')

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
        Request::is('subscriptions/2017/BlackFriday') ||
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
        @yield('content')
    </div>

    @include('includes.top-footer')
</div>

@include('includes.side-panel')
@include('includes.footer')