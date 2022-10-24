<div class="row">
    <div class="col-md-12">
        @if(count($browse_videos))
            @foreach($browse_videos->chunk(3) as $chunk)
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
                                    <div class="col-md-7 col-lg-6 browse-webinar-button-container">
                                        @if(auth()->user() && auth()->user()->webinars->contains($video->id))

                                            @if ($video->type != 'series' && $video->view_resource)
                                                <a href="{{ route('dashboard.video.links-and-resources', $video->slug) }}" class="btn btn-default my-webinars-btn-container">Resources</a>    
                                            @elseif($video->type == 'series')
                                                <a href="{{ route('webinars_on_demand.show', $video->slug) }}" class="btn btn-default my-webinars-btn-container" target="_blank">Resources</a>
                                            @endif

                                            @if ($video->type != 'series')
                                                <a href="{{ route('webinars_on_demand.show', $video->slug) }}" class="btn btn-default my-webinars-btn-container">Read More</a>
                                            @endif

                                            @if($video->view_link)
                                                <a href="{!! $video->view_link !!}" data-plugin-options="{&quot;type&quot;:&quot;iframe&quot;}" class="btn btn-primary my-webinars-btn-container lightbox wod_video_play"> <i class="fa fa-play"></i>Play</a> 
                                            @endif
                                        @else
                                            <a href="{{ route('webinars_on_demand.show', $video->slug) }}" class="btn btn-default my-webinars-btn-container">Read More</a>
                                            
                                            <a href="{{ route('webinars_on_demand.checkout', $video->slug) }}" class="btn btn-primary "><i class="fa fa-shopping-cart"></i> Buy Now</a>
                                            @if($video->videoInCart())
                                                <a href="#" class="btn btn-info"><i class="fa fa-shopping-cart"></i> Already In cart</a>
                                            @else
                                                <a href="{{ route('webinars_on_demand.add-to-cart', $video->slug) }}" class="btn btn-primary"><i class="fa fa-shopping-cart"></i> Add To Cart</a>    
                                            @endif
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
                <p>Your search returned {{ count($browse_videos) }} results, please check your search and try again.</p>
                <p><i>Suggestion: Try a diferent category or title</i></p>
            </div>
        @endif
    </div>
</div>


<div class="row text-center">
    {!! $browse_videos->render() !!}
</div>
