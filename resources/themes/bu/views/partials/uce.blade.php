@if(count($events))
    <section class="alternate">
        <div class="container">
            <div class="heading-title heading-dotted text-center">
                <h3>UPCOMING EVENTS</h3>
            </div>

            <div class="owl-carousel text-left owl-padding-10 buttons-autohide controlls-over" data-plugin-options='{"singleItem": false, "items":"3", "autoPlay": 4000, "navigation": true, "pagination": false}'>

                @if(count($events))
                    @foreach($events as $event)

                        <div class="border-box" style="background-image: url('/assets/frontend/images/demo/content_slider/background.jpg'); padding: 15px; min-height: 370px ;background-size: cover; display: flex; justify-content: center; align-items: center;">
                            <div class="event-name">
                                @if($event->is_redirect)
                                    <a style="font-size: 14px; color: white;" href="{{ $event->redirect_url }}">{!! str_limit($event->name, '70') !!}</a>
                                @else
                                    <a style="font-size: 14px; color: white;" href="{!! route('events.show', $event->slug) !!}">{!! str_limit($event->name, '70') !!}</a>
                                @endif
                                    <div class="divider divider-left" style="margin: 10px 0;">
                                        <i class="fa fa-ticket"></i>
                                    </div>
                                <p style="color: white; word-break: break-all;">{{ strip_tags(str_limit($event->short_description, '140'))  }}</p>
                                <i class="fa fa-calendar-o" style="color: white"></i> <span style="color: white">{!! $event->start_date->format('F, Y') !!}</span>
                                <hr>
                                @if($event->is_redirect)
                                        <a href="{{ $event->redirect_url }}" class="btn btn-sm btn-default">Register Today</a>
                                        <a href="{{ $event->redirect_url }}" class="btn btn-default btn-sm hidden-sm hidden-md" style="background-color: #4c4c4c; color: white;">Read More..</a>
                                        <a href="{{ $event->redirect_url }}" class="btn btn-default btn-sm hidden-lg" style="background-color: #4c4c4c; color: white; text-align: center!important;">&nbsp;<i class="fa fa-eye"></i></a>
                                @else
                                        <a href="{!! route('events.register', $event->slug) !!}" class="btn btn-sm btn-default">Register Today</a>
                                        <a href="{!! route('events.show', $event->slug) !!}" class="btn btn-default btn-sm hidden-xs hidden-sm hidden-md" style="background-color: #4c4c4c; color: white;">Read More..</a>
                                        <a href="{!! route('events.show', $event->slug) !!}" class="btn btn-default btn-sm hidden-xs hidden-lg" style="background-color: #4c4c4c; color: white; text-align: center!important;">&nbsp;<i class="fa fa-eye"></i></a>
                                @endif

                            </div>
                        </div>

                    @endforeach
                @else
                    <div class="text-center">
                        <p>
                            There are no events to display
                        </p>
                    </div>
                @endif
            </div>

            <div class="text-center">
                <a href="/events" class="btn btn-primary">Show All Events</a>
            </div>
        </div>
    </section>
@endif