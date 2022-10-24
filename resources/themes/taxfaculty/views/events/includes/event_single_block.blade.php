<div class="event-container-box clearfix">
    <div class="ribbon"><div class="ribbon-inner {{ ($event->type == 'webinar' ? "" : "custom-ribbon") }}">{{ ucfirst($event->type) }}</div></div>
    <div class="event-container-inner">
        <h4>{!! $event->name !!}</h4>
        <h5>
            <i class="fa fa-plus"></i> {{ $event->cpd_hours }} Hours |
            <i class="fa fa-clock-o"></i> {{ $event->start_time }} |
            <i class="fa fa-calendar-o"></i> {{ $event->next_date->toFormattedDateString() }} -
            <i class="fa fa-calendar-o"></i> {{ date_format($event->end_date, 'd F Y') }}
        </h5>

        <div class="venue-container">
            @foreach($event->scopeActiveVenues  as $venue)
                @if($venue->city && $venue->city != '')
                    <li class="styledLi">{{ $venue->city }}</li>
                @elseif($venue->type == 'online')
                    <li class="styledLi">Online</li>
                @endif
            @endforeach
        </div>

        <p>{{str_limit(preg_replace('/[^a-zA-Z0-9 .]/', '', $event->short_description), 200)}}</p>

        @if($event->is_redirect)
            <a href="{{$event->redirect_url}}" class="btn btn-primary"><i class="fa fa-eye"></i> Read More</a>
            @if(\Carbon\Carbon::parse($event->end_date)->format('Y-m-d')>=\Carbon\Carbon::now()->format('Y-m-d'))
            <a href="{{$event->redirect_url}}" class="btn btn-default">Register Now</a>
            @else
            <a href="{{$event->redirect_url}}" class="btn btn-default">Buy Recording</a>
            @endif
        @else
            <a href="{{ route('events.show', $event->slug) }}" class="btn btn-primary"><i class="fa fa-eye"></i> Read More</a>
            @if(\Carbon\Carbon::parse($event->end_date)->format('Y-m-d')>=\Carbon\Carbon::now()->format('Y-m-d'))
            <a href="{{ route('events.register', $event->slug) }}" class="btn btn-default">Register Now</a>
            @else
            <a href="{{ route('events.register', $event->slug) }}" class="btn btn-default">Buy Recording</a>
            @endif
        @endif
    </div>
</div>