@include('includes.header')
<link href="/assets/frontend/css/layout-shop.css" rel="stylesheet" type="text/css"/>

<div id="wrapper">
    @include('includes.top-bar')
    @include('includes.nav')

    @if(Request::is('/'))
        @include('includes.slider')
    @elseif(
        Request::is('auth/login') ||
        Request::is('join')||
        Request::is('auth/register')||
        Request::is('dashboard')||
        Request::is('dashboard/edit')||
        Request::is('dashboard/edit/addresses')||
        Request::is('dashboard/edit/avatar')||
        Request::is('dashboard/edit/password')||
        Request::is('dashboard/edit/privacy')||
        Request::is('dashboard/events')||
        Request::is('dashboard/courses')||
        Request::is('dashboard/cpd')||
        Request::is('dashboard/invoices')||
        Request::is('password/email')||
        Request::is('password/reset') ||
        Request::is('events/*/register') 
    )

    @else
        @include('includes.page-header')
    @endif

    <div id="app">

        @yield('content')
        @include('includes.welcome')
    </div>

    @include('includes.top-footer')    
</div>

@include('includes.side-panel')
@include('includes.footer')
