<div class="col-md-12">
    @if(count($videos))
        <div class="row">
            @foreach($videos->chunk(3) as $chunk)
                @foreach($chunk as $video)
                    @include('webinars_on_demand.includes.video')
                @endforeach
            @endforeach
        </div>
    @else
        <div class="alert alert-info no-webinars">
            <p><strong>Note..</strong></p>
            <p>Your search returned {{ count($videos) }} results, please check your search and try again.</p>
            <p><i>Suggestion: Try a diferent category or title</i></p>
        </div>
    @endif
</div>