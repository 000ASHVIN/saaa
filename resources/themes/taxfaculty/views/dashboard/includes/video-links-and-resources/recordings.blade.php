{{-- <div class="border-box clearfix text-center">
    <p>When downloading the below recording we recommend using Google Chrome / Firefox . If older Internet browsers are used when downloading the recordings
        it is highly possible that your download will fail and that you will have to restart your download again.
    </p>
    <a href="https://goo.gl/Fn2dCf" target="_blank"><img  style="width:5%" src="http://imageshack.com/a/img923/8992/W1tHZQ.jpg" alt="Google Chrome"></a>
    <span style="padding-right: 20px"></span>
    <a href="https://goo.gl/7enrPw" target="_blank"><img  style="width:5%" src="http://imageshack.com/a/img922/6181/2OVJ8Q.jpg" alt="Firefox"></a>
</div>
<br> --}}

<div class="recordings">

    @if($video)
            <div class="row">
                    <div class="col-md-4">
                        <input type="hidden" id="video_id" value="{{ $video->id }}">
                        <div class="border-box" style="margin-bottom: 15px; padding: 0px; min-height: 240px; border-color: #ffffff; -webkit-box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); -moz-box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75);">
                            <a style="width: 110%!important;" class="lightbox wod_video_play" href="{!! $video->view_link !!}" data-plugin-options="{&quot;type&quot;:&quot;iframe&quot;}">
                                <img style="width: 100%!important; margin-bottom: 5px" src="/assets/frontend/images/webinar_on_demand_image.jpg" alt="...">
                            </a>
                            <div class="padding" style="padding: 15px">
                                <p class="text-center">{!! $video->title !!}</p>
                                <hr>
                               <div class="text-center">
                                    @if($video->view_link)<a href="{!! $video->view_link !!}" data-plugin-options="{&quot;type&quot;:&quot;iframe&quot;}" class="lightbox wod_video_play"> <i class="fa fa-play"></i> Play </a>@endif
                               </div>
                            </div>
                        </div>
                    </div>
            </div>
       
    @else
        <div class="row">
            <div class="col-md-12">
                <p>There are no recordings available at the moment.</p>
            </div>
        </div>
    @endif 
</div>