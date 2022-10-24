<section class="page-header hidden-print">
    <div class="container">

        <h1>@yield('title', 'Page')</h1>
        {{--<span>@yield('intro', 'Page Intro Text')</span>--}}

    </div>
</section>

<section class="theme-color hidden-print" style="padding: 0px;">
    <div class="container">
        <div class="row">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb" style="padding: 0px">
                        <li class="active">
                            @yield('breadcrumbs')
                        </li>
                    </ol>
                </li>
            </ol>
        </div>
    </div>
</section>