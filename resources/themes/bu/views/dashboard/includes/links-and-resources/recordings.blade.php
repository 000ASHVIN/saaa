<div class="border-box clearfix text-center">
    <p>When downloading the below recording we recommend using Google Chrome / Firefox . If older Internet browsers are used when downloading the recordings
        it is highly possible that your download will fail and that you will have to restart your download again.
    </p>
    <a href="https://goo.gl/Fn2dCf" target="_blank"><img  style="width:5%" src="http://imageshack.com/a/img923/8992/W1tHZQ.jpg" alt="Google Chrome"></a>
    <span style="padding-right: 20px"></span>
    <a href="https://goo.gl/7enrPw" target="_blank"><img  style="width:5%" src="http://imageshack.com/a/img922/6181/2OVJ8Q.jpg" alt="Firefox"></a>
</div>
<br>

<div class="recordings">
    @if(count($recordings) > 0)
        @foreach($recordings->chunk(3) as $chunk)
            <div class="row">
            @foreach($chunk as $recording)
                    <div class="col-md-4">
                        <div class="border-box" style="margin-bottom: 15px; padding: 0px; min-height: 240px; border-color: #ffffff; -webkit-box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); -moz-box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75);">
                            <a style="width: 110%!important;" class="lightbox" href="{!! $recording->video->view_link !!}" data-plugin-options="{&quot;type&quot;:&quot;iframe&quot;}">
                                <img style="width: 100%!important; margin-bottom: 5px" src="/assets/frontend/images/webinar_on_demand_image.jpg" alt="...">
                            </a>
                            <div class="padding" style="padding: 15px">
                                <p class="text-center">{!! $recording->video->title !!}</p>
                                <hr>
                               <div class="text-center">
                                   <a href="#" data-toggle="modal" data-target="#webinar{{$recording->id}}"> <i class="fa fa-download"></i> Save</a>
                                   @include('dashboard.includes.download_video')
                               </div>
                            </div>
                        </div>
                    </div>

                {{--<div class="col-md-3 text-center">--}}
                    {{--<a style="width: 100%!important;" class="image-hover lightbox" href="{!! $recording->video->view_link !!}" data-plugin-options='{"type":"iframe"}'>--}}
                    {{--<span class="image-hover-icon image-hover-light">--}}
                        {{--<i class="fa fa-play" style="color: #800000"></i>--}}
                    {{--</span>--}}
                        {{--<img style="width: 100%!important;" class="thumbnail" src="{!! $recording->video->cover !!}" alt="...">--}}
                    {{--</a>--}}
                    {{--@if(auth()->user()->profile->id)--}}
                        {{--<a href="#" data-toggle="modal" data-target="#webinar{{$recording->id}}" class="fa fa-download"> Download</a>--}}
                    {{--@endif--}}
                    {{--<hr>--}}
                    {{--<p>{!! $recording->video->title !!}</p>--}}
                    {{--<hr>--}}
                    {{--@include('dashboard.includes.download_video')--}}
                {{--</div>--}}
            @endforeach
            </div>
            {{--<div class="col-md-2 clearfix">--}}
                {{----}}
                {{--<div class="col-md-8">--}}
                    {{--<p>Title: {!! $recording->video->title !!}</p>--}}
                    {{--<p>Video Reference: {!! $recording->video->reference !!}</p>--}}
                    {{--@if($recording->video->can_be_downloaded)--}}
                        {{--<a href="{{ $recording->video->download_link }}"--}}
                           {{--class="btn btn-primary btn-sm">--}}
                            {{--Download Video--}}
                        {{--</a>--}}
                    {{--@endif--}}
                {{--</div>--}}
            {{--</div>--}}
        @endforeach
    @else
        <div class="row">
            <div class="col-md-12">
                <p>There are no recordings available at the moment.</p>
            </div>
        </div>
    @endif
</div>