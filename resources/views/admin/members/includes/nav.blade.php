<?php
    $user_has_access = userHasAccess(auth()->user());
?>
<div class="col-md-3 col-sm-3">

    <img class="repsonsive thumbnail" src="{{ asset($member->avatar) }}"
         alt="{{ $member->first_name }} {{ $member->last_name }}"
         onError="this.onerror=null;this.src='http://imageshack.com/a/img923/8590/LXahIE.png';" style="width: 100%;">

    <hr>
        <div class="center"><h4 class="text-info">U-Wallet: <strong>R{{ number_format($member->wallet->amount, 2) }}</strong></h4></div>
    <hr>

    <ul class="nav nav-tabs nav-stacked" style="text-align: left!important; padding: 5px; border: 1px solid rgb(236, 236, 236)">

        <li class="{{ isActive('admin.members.show', true) }}"><a href="{{ route('admin.members.show', $member) }}"><i class="ti-angle-double-right"></i> Overview</a></li>

        @if($member->subscribed('cpd') && $member->subscription('cpd')->plan->is_practice)
            <li class="{{ isActive('admin.member.company', true) }}"><a href="{{ route('member.company', $member) }}"><i class="ti-angle-double-right"></i>Company</a></li>
        @endif

        @role('super')
        <li class="{{ isActive('admin.member.wallet', true) }}"><a href="{{ route('member.wallet', $member) }}"><i class="ti-angle-double-right"></i> U-Wallet</a></li>
        @endrole

        <li class="{{ isActive('admin.member.activity_log', true) }}"><a href="{{ route('member.activity_log', $member) }}"><i class="ti-angle-double-right"></i> Activity</a></li>
        <li class="{{ isActive('admin.member.account_notes', true) }}"><a href="{{ route('member.account_notes', $member) }}"><i class="ti-angle-double-right"></i> Account Notes</a></li>

        @if($member->subscription('cpd') && $member->subscription('cpd')->active() == true)
            {{--<li class="{{ isActive('admin.member.payment_method', true) }}"><a href="{{ route('member.payment_method', $member) }}"><i class="ti-angle-double-right"></i> Payment Method</a></li>--}}
            @if($member->subscription('cpd')->plan->is_custom)
                <li class="{{ isActive('admin.member.custom_topics', true) }}"><a href="{{ route('member.custom_topics', $member) }}"><i class="ti-angle-double-right"></i> Comprehensive Topics</a></li>
            @endif
        @endif

        <hr>

        <li class="{{ isActive('admin.member.edit', true) }}"><a href="{{ route('member.edit', $member) }}"><i class="ti-angle-double-right"></i> Edit Account</a></li>
        <li class="{{ isActive('admin.member.addresses', true) }}"><a href="{{ route('member.addresses', $member) }}"><i class="ti-angle-double-right"></i>Profile Addresses</a></li>

        <hr>

        @role('super|sales|accounts|accounts-administrator|support')
        <li class="{{ isActive('admin.member.invoices', true) }}"><a href="{{ route('member.invoices', $member) }}"><i class="ti-angle-double-right"></i> Invoices</a></li>
        @endrole
        @role('super|accounts|accounts-administrator|support')
        <li class="{{ isActive('admin.member.generate_invoices', true) }}"><a href="{{ route('member.generate_invoices', $member) }}"><i class="ti-angle-double-right"></i> New Invoice</a></li>
        @endrole

        <li class="{{ isActive('admin.member.orders', true) }}"><a href="{{ route('member.orders', $member) }}"><i class="ti-angle-double-right"></i> Purchase Orders</a></li>
        <li class="{{ isActive('admin.member.generate_order', true) }}"><a href="{{ route('member.generate_order', $member) }}"><i class="ti-angle-double-right"></i> New Purchase Order</a></li>

        <li class="{{ isActive('admin.member.generate_webinars_order', true) }}"><a href="{{ route('member.generate_webinars_order', $member) }}"><i class="ti-angle-double-right"></i> New Webinars Order</a></li>
        
        <li class="{{ isActive('admin.member.generate_course_order', true) }}"><a href="{{ route('member.generate_course_order', $member) }}"><i class="ti-angle-double-right"></i> New Course Order</a></li>

        <li class="{{ isActive('admin.member.generate_practice_plan', true) }}"><a href="{{ route('member.generate_practice_plan', $member) }}"><i class="ti-angle-double-right"></i> New Practice Plan Order</a></li>

        <li class="{{ isActive('admin.member.statement', true) }}"><a href="{{ route('member.statement', $member) }}"><i class="ti-angle-double-right"></i> Account Statement</a></li>

        <hr>

        @if($member->subscription('cpd') && $member->subscription('cpd')->plan->is_practice && $member->company_admin())
            <li class="{{ isActive('admin.member.renew_company_subscription', true) }}"><a href="{{ route('member.renew_company_subscription', $member) }}"><i class="ti-angle-double-right"></i> Renew Company Subscription</a></li>
        @endif
        
        <li class="{{ isActive('admin.member.upgrade_subscription', true) }}"><a href="{{ route('member.upgrade_subscription', $member) }}"><i class="ti-angle-double-right"></i> Change Subscription</a></li>

        @if (auth()->user()->is('super'))
            @if($user_has_access)
                <li class="{{ isActive('admin.member.book_event', true) }}"><a href="{{ route('member.book_event', $member) }}"><i class="ti-angle-double-right"></i> Book Events</a></li>
            @endif
        @endif

        @if($user_has_access)
            <li class="{{ isActive('admin.member.assessments', true) }}"><a href="{{ route('member.assessments', $member) }}"><i class="ti-angle-double-right"></i> Assessments </a></li>
            <li class="{{ isActive('admin.member.cpd_hours', true) }}"><a href="{{ route('member.cpd_hours', $member) }}"><i class="ti-angle-double-right"></i>CPD Hours</a></li>
        @endif
        <li class="{{ isActive('admin.member.events', true) }}"><a href="{{ route('member.events', $member) }}"><i class="ti-angle-double-right"></i> Events</a></li>
        <li class="{{ isActive('admin.member.courses', true) }}"><a href="{{ route('member.courses', $member) }}"><i class="ti-angle-double-right"></i> Courses</a></li>

        <hr>

        @if(! $member->isAdmin() || auth()->user()->is('super'))
            <li class="{{ isActive('admin.member.sms', true) }}"><a href="{{ route('member.sms', $member) }}"><i class=" ti-email"></i> Send SMS <sup><span class="label label-success">{{ count($member->smses) }}</span></sup></a></li>
            <li><a href="/switch/start/{{$member->id}}"><i class=" ti-unlock"></i> Sign in as this user</a></li>
            <li><a href="{{ route('admin.members.reset-password',[$member->id]) }}"><i class=" ti-lock"></i> Reset Password</a></li>
        @endif
    </ul>
</div>


