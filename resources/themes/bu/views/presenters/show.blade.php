@extends('app')

@section('content')

@section('title')
    Meet Our Presenters
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('presenter.show', $presenter->name) !!}
@stop

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-xs-3 col-sm-3">
                <img src="{{ asset('storage/'.$presenter->avatar) }}" width="100%" class="thumbnail" alt="">
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
                        @foreach($presenter->events->sortBy('start_date') as $event)
                            <tr>
                                @if($event->is_redirect)
                                    <td><a href="{{ $event->redirect_url }}">{{ str_limit($event->name, '50') }}</a></td>
                                @else
                                    <td><a href="{{ route('events.show', $event->slug) }}" target="_blank">{{ str_limit($event->name, '50') }}</a></td>
                                @endif
                                <td>{{ date_format($event->start_date, 'd F Y') }}</td>
                                <td class="text-center"> {{ \Carbon\Carbon::parse($event->start_time)->diffInHours(\Carbon\Carbon::parse($event->end_time)) }}</td>
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

            </div>
        </div>
    </div>
</section>

@endsection