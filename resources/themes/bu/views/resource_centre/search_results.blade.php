@extends('app')

@section('content')

@section('title')
    Search Results
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('resource_centre') !!}
@stop

@section('styles')
    <style>
        section .nav-pills>li>a:hover, section .nav-pills>li>a:focus, section .nav-pills>li.active>a, section .nav-pills>li.active>a:hover, section .nav-pills>li.active>a:focus {
            background-color: #800000ad !important;
        }
    </style>
@endsection

<section id="slider" class="hidden-sm hidden-xs">
    <img src="https://imageshack.com/a/img923/3048/80sCiK.jpg" alt="Technical Resource Centre" style="width: 100%">
</section>

@include('resource_centre.includes.search')

<section class="alternate">
    <div class="container">
        <div class="col-md-12">

            <div class="row mix-grid">
                <ul class="nav nav-pills mix-filter" style="text-transform: capitalize;">
                    @if (count($acts))<li data-filter="acts" class="filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Acts ({{ count($acts) }})</a></li>@endif
                    @if (count($faqs))<li data-filter="faq" class="filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> FAQ's ({{ count($faqs) }})</a></li>@endif
                    @if (count($articles))<li data-filter="articles" class="filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Articles ({{ count($articles) }})</a></li>@endif
                    @if (count($webinars))<li data-filter="webinars" class="filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Webinars ({{ count($webinars) }})</a></li>@endif
                    @if (count($events))<li data-filter="events" class="filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Events ({{ count($events) }})</a></li>@endif
                    @if (count($tickets))<li data-filter="tickets" class="filter"><a class="btn btn-primary" style="color: #ffffff" href="#"><i class="fa fa-filter"></i> Tickets ({{ count($tickets) }})</a></li>@endif
                </ul>

                <div class="divider" style="margin: 0px"></div>

                <div class="toggle toggle-transparent toggle-bordered-simple">

                    @if(count($acts))
                        @foreach($acts->sortBy('created_at') as $act)
                            <div class="toggle acts mix">
                                @include('resource_centre.includes.item', ['created' => date_format($act->created_at, 'd F Y'), 'link' => route('resource_centre.acts.show', $act->slug), 'title' => $act->name, 'description' => str_limit(strip_tags($act->description), 250)])
                            </div>
                        @endforeach
                    @endif

                    @if(count($faqs))
                        @foreach($faqs->sortBy('created_at') as $faq)
                            <div class="toggle faq mix">
                                @include('resource_centre.includes.item', ['created' => date_format($faq->created_at, 'd F Y'), 'link' => route('resource_centre.technical_faqs.index'), 'title' => $faq->question, 'description' => str_limit(strip_tags($faq->answer), 250)])
                            </div>
                        @endforeach
                    @endif

                    @if(count($articles))
                        @foreach($articles->sortBy('created_at') as $article)
                            <div class="toggle articles mix">
                                @include('resource_centre.includes.item', ['created' => date_format($article->created_at, 'd F Y'), 'link' => route('news.show', $article->slug), 'title' => $article->title, 'description' => str_limit(strip_tags($article->description), 250)])
                            </div>
                        @endforeach
                    @endif

                    @if(count($webinars))
                        @foreach($webinars->sortBy('created_at') as $video)
                            <div class="toggle webinars mix">
                                @include('resource_centre.includes.item', ['created' => date_format($video->created_at, 'd F Y'), 'link' => route('webinars_on_demand.show', $video->slug), 'title' => $video->title, 'description' => str_limit(strip_tags($video->description), 250)])
                            </div>
                        @endforeach
                    @endif

                    @if(count($events))
                        @foreach($events->sortBy('created_at') as $event)
                            <div class="toggle events mix">
                                @include('resource_centre.includes.item', ['created' => date_format($event->created_at, 'd F Y'), 'link' => ($event->is_redirect ? $event->redirect_url : route('events.show', $event->slug)), 'title' => $event->name, 'description' => str_limit(strip_tags($event->description), 250)])
                            </div>
                        @endforeach
                    @endif

                    @if(count($tickets))
                        @foreach($tickets->sortBy('created_at') as $ticket)
                            <div class="toggle tickets mix">
                                @include('resource_centre.includes.item', ['created' => date_format($ticket->created_at, 'd F Y'), 'link' => route('resource_centre.legislation.show', $ticket->thread_id), 'title' => $ticket->subject, 'description' => str_limit(strip_tags($ticket->description), 250)])
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="row">
                <a href="{{ route('resource_centre.home') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back Home</a>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
    <script>
        function spin(this1)
        {
            this1.closest("form").submit();
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
    </script>
@stop