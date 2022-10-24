<div class="row">
    <div class="col-md-12">
        @if(count($videos))
            @foreach($videos->chunk(3) as $chunk)
                @foreach($chunk as $video)
                        <div class=" new-events-view event-container-box clearfix">
                            <div class="event-container-inner">
                                <div style="width:70%"><h4>{{ str_limit($video->title) }}</h4></div>
                                <div class="row">
                                    <div class="col-md-5 col-lg-6">
                                        <h5>
                                            <i class="fa fa-plus"></i> {{ ($video->hours ? : 0) }} Hours |
                                            R{{ number_format($video->amount, 2, ".", "") }}
                                        </h5>
                                        @if(count($video->presenters))
                                            <?php 
                                                $presenter = [];
                                            ?>
                                            @foreach($video->presenters as $presenters)
                                                <?php
                                                $presenter[] = $presenters->name; ?>
                                            @endforeach
                                            {{ implode(', ', $presenter)}}
                                        @endif
                                    </div>
                                    <input type="hidden" id="video_id" value="{{ $video->id }}">
                                    <div class="col-md-7 col-lg-6 browse-webinar-button-container">

                                        <a href="{{ route('webinars_on_demand.show', $video->slug) }}" class="btn btn-default my-webinars-btn-container">Read More</a>

                                        @if($video->view_link)
                                            @if($video->view_resource)
                                            <a href="{{ route('dashboard.video.links-and-resources', $video->slug) }}" class="btn btn-default my-webinars-btn-container">Resources</a>
                                        @endif
                                            
                                            <a href="{!! $video->view_link !!}" data-plugin-options="{&quot;type&quot;:&quot;iframe&quot;}" class="btn btn-primary my-webinars-btn-container lightbox wod_video_play"> <i class="fa fa-play"></i>Play</a> 
                                        @endif
                                    </div>        
                                </div>
                            </div>  
                        </div>
                @endforeach
            @endforeach
        @else
            <div class="alert alert-info">
                <p><strong>Note..</strong></p>
                <p>Your search returned {{ count($videos) }} results, please check your search and try again.</p>
                <p><i>Suggestion: Try a diferent category or title</i></p>
            </div>
        @endif
    </div>
</div>


<div class="row text-center">
    {!! $videos->render() !!}
</div>
