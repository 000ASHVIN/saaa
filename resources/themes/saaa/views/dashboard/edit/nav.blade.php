<ul class="nav nav-tabs nav-top-border">
    {{--<li class="{{ isActive('dashboard.edit.subscription') }}"><a href="{{ route('dashboard.edit.subscription') }}">Manage Subscription</a></li>--}}
    <li class="{{ isActive('dashboard.edit') }}"><a href="{{ route('dashboard.edit') }}">General</a></li>
    {{--<li class="{{ isActive('dashboard.edit.privacy') }}"><a href="{{ route('dashboard.edit.privacy') }}">Emails & Notifications</a></li>--}}
    <li class="{!! isActive('dashboard.edit.addresses') !!}"><a href="{{ route('dashboard.edit.addresses') }}">Addresses</a></li>
    <li class="{{ isActive('dashboard.edit.avatar') }}"><a href="{{ route('dashboard.edit.avatar') }}">Avatar</a></li>
    <li class="{{ isActive('dashboard.edit.password') }}"><a href="{{ route('dashboard.edit.password')}}">Password</a></li>
</ul>