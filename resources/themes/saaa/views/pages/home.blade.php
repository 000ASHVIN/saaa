@extends('app')

@section('meta_tags')
    <title>SAAA | SA Accounting Academy | CPD Provider</title>
    <meta name="description" content="The SA Accounting Academy (SAAA) offers Continuing Professional Development (CPD) training for accountants, auditors, bookkeepers, company secretaries and tax practitioners. We offer a range of live seminars and conferences and online webinars, seminar recordings, certificate courses and DVDs on both technical and business-related topics.">
    <meta name="Author" content="SA Accounting Academy"/>
@endsection

@section('content')
    <section class="alternate">
        <div class="container">

        <div class="col-md-12">
@if(isset($acts) || isset($faqs) || isset($articles) || isset($webinars) || isset($events) || isset($tickets))
            <div class="row mix-grid">
                <ul class="nav nav-pills mix-filter" style="text-transform: capitalize;">
                    @if (count($acts))<li data-filter="acts" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Acts ({{ $actsCount }})</a></li>@endif
                    @if (count($faqs))<li data-filter="faq" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> FAQ's ({{ $faqsCount }})</a></li>@endif
                    @if (count($articles))<li data-filter="articles" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Articles ({{ $articlesCount }})</a></li>@endif
                    @if (count($webinars))<li data-filter="webinars" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Webinars on-demand ({{ $webinarsCount }})</a></li>@endif
                    @if (count($events))<li data-filter="events" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Upcoming Events ({{ $eventsCount }})</a></li>@endif
                    @if (count($tickets))<li data-filter="tickets" class="custom_filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Tickets ({{ $ticketsCount }})</a></li>@endif
                </ul>

                <div class="divider" style="margin: 0px"></div>

                <div class="toggle toggle-transparent toggle-bordered-simple">
                @if(count($allRecords))
                    @foreach($allRecords as $item)
                        @if($item->search_type == 'articles')
                            <div class="toggle articles mix filter_result">
                                @include('resource_centre.includes.item', ['created' => date_format($item->created_at, 'd F Y'), 'link' => route('news.show', $item->slug), 'title' => $item->title, 'description' => str_limit(strip_tags($item->description), 250)])
                            </div>
                        @endif

                        @if($item->search_type == 'faqs')
                            <div class="toggle faq mix filter_result">
                                @include('resource_centre.includes.item', ['created' => date_format($item->created_at, 'd F Y'), 'link' => route('resource_centre.technical_faqs.index'), 'title' => $item->question, 'description' => str_limit(strip_tags($item->answer), 250)])
                            </div>
                        @endif

                        @if($item->search_type == 'acts')
                            <div class="toggle acts mix filter_result">
                                @include('resource_centre.includes.item', ['created' => date_format($item->created_at, 'd F Y'), 'link' => route('resource_centre.acts.show', $item->slug), 'title' => $item->name, 'description' => str_limit(strip_tags($item->description), 250)])
                            </div>
                        @endif

                        @if($item->search_type == 'webinars')
                            <div class="toggle webinars mix filter_result">
                                @include('resource_centre.includes.item', ['created' => date_format($item->created_at, 'd F Y'), 'link' => route('webinars_on_demand.show', $item->slug), 'title' => $item->title, 'description' => str_limit(strip_tags($item->description), 250)])
                            </div>
                        @endif

                        @if($item->search_type == 'events')
                            <div class="toggle events mix filter_result">
                                @include('resource_centre.includes.item', ['created' => date_format($item->created_at, 'd F Y'), 'link' => ($item->is_redirect ? $item->redirect_url : route('events.show', $item->slug)), 'title' => $item->name, 'description' => str_limit(strip_tags($item->description), 250)])
                            </div>
                        @endif

                        @if($item->search_type == 'tickets')
                            <div class="toggle tickets mix filter_result">
                                @include('resource_centre.includes.item', ['created' => date_format($item->created_at, 'd F Y'), 'link' => route('resource_centre.legislation.show', $item->thread_id), 'title' => $item->subject, 'description' => str_limit(strip_tags($item->description), 250)])
                            </div>
                        @endif
                    @endforeach
                @else
                    <div class="toggle tickets mix filter_result">
                            <h2>No data found</h2>
                    </div>
                    <hr>
                @endif
                </div>  
            </div>

            @if($actsCount > 5 || $articlesCount > 5 || $faqsCount > 5 || $eventsCount > 5 || $webinarsCount > 5  || $ticketsCount > 5)
            <div class="row">
            <div class="col-md-10">
                <!-- <a href="{{ route('home') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back Home</a> -->
            </div>
            <div class="col-md-2">
                {!! Form::open(['method' => 'post', 'route' => 'resource_centre.search']) !!}
                    <input type="hidden" value="{{ request()->search }}" name="search">
                    
                    <button type="submit" name="submit" value="all_records" class="btn btn-primary">All Records <i class="fa fa-arrow-right"></i></button>
                {!! Form::close() !!}
            </div>
            </div>
            @endif
        </div>  
@endif


            <div class="row">
                <div class="col-sm-6">
                    <header class="margin-bottom-40">
                        <h1 class="weight-300">Resource Centre & CPD Subscription</h1>
                        <h2 class="weight-300 letter-spacing-1 size-13"><span>Join more than {{ $users -1, 2 }} Accountants, Auditors and Tax Practitioners</span></h2>
                    </header>

                    <ul class="list-unstyled list-icons">
                        <li><i class="fa fa-check text-success"></i> Core and elective CPD topics</li>
                        <li><i class="fa fa-check text-success"></i> Tax and Accounting Topics</li>
                        <li><i class="fa fa-check text-success"></i> Watch webinars at your convenience</li>
                        <li><i class="fa fa-check text-success"></i> CPD Tracker / Logbook</li>
                        <li><i class="fa fa-check text-success"></i> Printable CPD Certificates</li>
                        <li><i class="fa fa-check text-success"></i> Searchable Technical Resource Centre</li>
                        <li><i class="fa fa-check text-success"></i> Access to experts</li>
                        <li><i class="fa fa-check text-success"></i> Reference guides </li>
                        <li><i class="fa fa-check text-success"></i> Interactive sessions </li>
                        <li><i class="fa fa-check text-success"></i> Online Q&A </li>
                        <li><i class="fa fa-check text-success"></i> Practical examples </li>
                        <li><i class="fa fa-check text-success"></i> Affordable </li>
                        <li><i class="fa fa-check text-success"></i> Accredited </li>
                    </ul>

                    <a href="/subscription_plans" class="btn btn-primary btn-lg"><i class="fa fa-lock"></i> Find Out More <Now></Now></a>
                </div>

                <div class="col-sm-6">
                    <div class="owl-carousel buttons-autohide controlls-over"
                         data-plugin-options='{"items": 1, "autoHeight": true, "navigation": true, "pagination": true, "autoPlay": 10000, "transitionStyle":"backSlide"}'>
                        <div>
                            <img class="img-responsive" src="/assets/frontend/images/demo/desktop_slider-4.png" alt="">
                        </div>
                        <div>
                            <img class="img-responsive" src="/assets/frontend/images/demo/desktop_slider-3.png" alt="">
                        </div>
                        <div>
                            <img class="img-responsive" src="/assets/frontend/images/demo/desktop_slider-6.png" alt="">
                        </div>
                        <div>
                            <img class="img-responsive" src="/assets/frontend/images/demo/desktop_slider-5.png" alt="">
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="callout-dark heading-arrow-top">
        <a href="/auth/register" class="btn btn-xlg btn-primary size-10 fullwidth nomargin bopadding noradius ">
            <span class="font-lato size-30"><span style="font-size: 20px" class="countTo" data-speed="3000">{{ $users -1, 2  }}</span> <span style="font-size: 20px">Accountants, Auditors and Tax Practitioners are already part of our network of subscribers</span></span>
            <span class="block font-lato">Why don't you <span style="text-decoration: underline;">join</span> the network and be number <span class="countTo" data-speed="3000">{{ $users - 1 +1 }}</span> ?</span>
        </a>
    </div>

    <section>
        <div class="row">
            <div class="col-md-12">
                <header class="margin-bottom-40 text-center">
                    <h1 class="weight-600">Our Partners</h1>
                    <h2 class="weight-300 letter-spacing-1 size-13"><span>We have teamed up with..</span></h2>
                </header>
            </div>
        </div>
        @if (count($premium_sponsers) > 0)
        
        <div class="row">
            <div class="col-md-12">
                <header class="margin-bottom-40 text-center">
                    <h3 class="weight-500">Premium Partners</h3>
                </header>
            </div>
        </div>
        <div class="container">
            <ul class="row clients-dotted list-inline premium-partners">
                @foreach ($premium_sponsers as $sponser)
                    <li class="col-md-5th col-sm-5th col-6">
                        <a href="{{ route('rewards.show', $sponser->slug) }}" target="_blank"><img class="img-fluid" width="70%" src="{{ asset('storage/'.$sponser->logo) }}" alt="{{ $sponser->title }}" /></a>
                    </li>
                @endforeach
            </ul>
        </div>
        
        @endif
        
        @if (count($normal_sponsers) > 0)
        
        <div class="row">
            <div class="col-md-12">
                <header class="margin-bottom-40 text-center">
                    <h3 class="weight-500">Partners</h3>
                </header>
            </div>
        </div>
        <div class="container">
            <ul class="row clients-dotted list-inline">
                @foreach ($normal_sponsers as $sponser)
                    <li class="col-md-5th col-sm-5th col-6">
                        <a href="{{ route('rewards.show', $sponser->slug) }}" target="_blank"><img class="img-fluid" width="70%" src="{{ asset('storage/'.$sponser->logo) }}" alt="{{ $sponser->title }}" /></a>
                    </li>
                @endforeach
            </ul>
        </div>

        @endif
        {{-- <div class="container">
            <ul class="row clients-dotted list-inline">
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="/assets/frontend/images/sponsors/saiba.jpg" alt="SAIBA" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="/assets/frontend/images/sponsors/draftworx.jpg" alt="Draftworx" />
                </li>
               
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="/assets/frontend/images/sponsors/quickbooks.jpg" alt="Quickbooks" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="/assets/frontend/images/sponsors/acts.jpg" alt="Acts Online" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="https://imageshack.com/a/img924/3893/lOmls8.jpg" alt="The SAIT" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="https://imageshack.com/a/img923/9456/CCsy59.jpg" alt="UNISA" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="https://imageshack.com/a/img921/8710/3Jv5Z3.jpg" alt="The Tax Shop" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="https://imageshack.com/a/img923/1850/DqUSr2.jpg" alt="The Tax Faculty" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="https://imageshack.com/a/img924/382/QmBAkg.jpg" alt="ICAEW" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="/assets/frontend/images/sponsors/OldMutual.png" alt="OldMutual" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="/assets/frontend/images/sponsors/Practice-Ignition.png" alt="Practice-Ignition" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="/assets/frontend/images/sponsors/infodocs-partner-logo-square.png" alt="InfoDoc" />
                </li>
                <li class="col-md-5th col-sm-5th col-6">
                    <img class="img-fluid" width="70%" src="/assets/frontend/images/sponsors/EdNVest logo.png" alt="EdNVest logo" />
                </li>
            </ul>
        </div> --}}
    </section>
@endsection


@section('scripts')
<script src="{{ Theme::asset('js/pages/home.js', null, true) }}"></script>
@stop