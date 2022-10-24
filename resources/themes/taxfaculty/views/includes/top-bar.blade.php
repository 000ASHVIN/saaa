<!--[if lt IE 7]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->

<div id="topBar" class="hidden-print">
    <div class="container">

        <ul class="top-links list-inline pull-right">
            @if($user)

                @if(key_exists('orig_user', Session::all()))
                    <li class="hidden-xs"><a href="/switch/stop"><i class="fa fa-hand-stop-o" style="color: red"></i> Impersonation</a></li>
                    {{--<a href="/switch/stop" class="btn btn-xs btn-warning hidden-xs"><i class="fa fa-lock"></i> Stop Impersonating</a>--}}
                @endif

                <li class="text-welcome hidden-xs">Welcome
                    <strong>
                        <a href="{{ '/dashboard' }}">{!! $user->first_name .' '.$user->last_name!!} </a>
                    </strong>
                </li>

                @permission('access-admin-section')
                    <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                @endpermission

                <li>
                    <a href="#">Balance: R{{ number_format(auth()->user()->balance / 100, 2) }}</a>
                </li>

                {{--  @if (count(auth()->user()->support_tickets) && auth()->user()->replyReceived())
                        <li>
                            <a href="#">
                                <span class="badge badge-aqua btn-xs badge-corner" style="top: 0px!important; right: 0px!important">{{ count(auth()->user()->replyReceived()) }}</span>
                                <i class="fa fa-support"></i>
                            </a>
                        </li>
                @endif  --}}

            @else
            <li><a class="no-text-underline" href="/auth/login"><i class="fa fa-unlock hidden-xs"></i>LOGIN</a></li>
            <li><a class="no-text-underline" href="/auth/register"><i class="fa fa-lock hidden-xs"></i>JOIN US</a></li>
            @endif
        </ul>

        <ul class="top-links list-inline">
            <li class=""><a href="https://twitter.com/thetaxfaculty" target="_blank"><img src="/assets/themes/taxfaculty/img/icons/twitter_new.jpg" alt="twitter"></a></li>
            <li class=""><a href="https://www.linkedin.com/company/the-tax-faculty/" target="_blank"><img src="/assets/themes/taxfaculty/img/icons/linkedin_new.jpg" alt="linkedIn"></a></li>
            <li class=""><a href="https://www.facebook.com/TheTaxFaculty/" target="_blank"><img src="/assets/themes/taxfaculty/img/icons/facebook_new.jpg" alt="facebook"></a></li>
            <li class=""><a href="https://www.youtube.com/channel/UCdUuCo_4wflmQpcob64K5oQ/" target="_blank"><img src="/assets/themes/taxfaculty/img/icons/youtube_new.jpg" alt="youtube"></a></li>
        </ul>


    </div>
</div>