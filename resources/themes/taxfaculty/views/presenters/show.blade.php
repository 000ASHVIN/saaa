@extends('app')

@section('meta_tags')
    <title>{{ $presenter->checkMetaTitle() }}</title>
    <meta name="description" content="{{ $presenter->meta_description }}">
    
@endsection

@section('content')

@section('title')
    Meet Our Presenters
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('presenter.show', $presenter->name) !!}
@stop
@section('styles')
<style>
img.thumbnail {
    filter: grayscale(100%);
}
</style>
@stop
<section>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-xs-3 col-sm-3">
                <?php
                    $url='';
                    if(isset($presenter->avatar)){
                        if (!preg_match("~^(?:f|ht)tps?://~i",$presenter->avatar)) { 
                            // If not exist then add http storage link
                            $url = asset('storage/'.$presenter->avatar); 
                        } 
                        else{
                            $url = $presenter->avatar;
                        }
                    }
                ?>
                <img src="{{ $url }}" width="100%" class="thumbnail" alt="">
            </div>
            <div class="col-md-9 col-sx-9 col-sm-9">
                <h4>{{ $presenter->name }} <br>
                    <small>{{ $presenter->title }}</small>
                </h4>
                <div class="divider margin-top-0 margin-bottom-0"></div>
                <p>
                    {!! $presenter->bio !!}
                </p>

                <hr>

                <h4>Events that was hosted by {{ $presenter->name }}</h4>
                <table class="table table-striped">
                    <thead>
                        <th>Event</th>
                        <th>Start Date</th>
                        <th class="text-center">CPD Hours</th>
                        <th class="text-center">Status</th>
                    </thead>
                    <tbody>
                    @if(count($presenter->events))
                        @foreach($presenter->events->sortByDesc('start_date') as $event)
                            <tr>
                                @if($event->is_redirect)
                                    <td><a href="{{ $event->redirect_url }}">{{ str_limit($event->name, '50') }}</a></td>
                                @else
                                    <td><a href="{{ route('events.show', $event->slug) }}" target="_blank">{{ str_limit($event->name, '50') }}</a></td>
                                @endif
                                <td>{{ date_format($event->start_date, 'd F Y') }}</td>
                                <td class="text-center"> {{ $event->cpd_hours }}</td>
                                <td class="text-center">
                                    @if($event->end_date <= \Carbon\Carbon::now())
                                        <div class="label label-default">Event Past</div>
                                    @else
                                        <div class="label label-primary">Coming Soon</div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">No Events Available</td>
                        </tr>
                    @endif
                    </tbody>
                </table>

                <h4>Webinars on Demand presented by {{ $presenter->name }}</h4>
                <table class="table table-striped">
                    <thead>
                        <th>Title</th>
                        <th class="text-center">Hours</th>
                    </thead>
                    <tbody>
                    @if(count($presenter->videos))
                        @foreach($presenter->videos as $video)
                            <tr>
                                <td width="70%"><a href="{{ route('webinars_on_demand.show', $video->slug) }}" target="_blank">{{ $video->title }}</a></td>
                                <td width="15%" class="text-center">{{ $video->hours }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">No Webinars Available</td>
                        </tr>
                    @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</section>

@endsection