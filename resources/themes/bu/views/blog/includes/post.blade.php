<div class="col-md-4">
    <div class="blog-post-item">
        <!-- IMAGE -->
        <figure class="margin-bottom-20">
            @if(! $post->image)
                <img class="img-responsive article-image" src="/assets/frontend/images/default-blog.jpg" alt="Default">
            @else
                <img class="img-responsive article-image" src="{{ asset('storage/'.$post->image) }}" alt="">
            @endif
        </figure>

        <h4><a href="{{ route('news.show', $post->slug) }}">{{ str_limit($post->title, 50) }}</a></h4>

        <ul class="blog-post-info list-inline">
            <li>
                <i class="fa fa-clock-o"></i>
                <span class="font-lato">{{ date_format(\Carbon\Carbon::parse($post->created_at), 'F d') }}</span>
            </li>
            <li>
                <i class="fa fa-folder-open-o"></i>
                <span class="font-lato">{{ $category->title }}</span>
            </li>
        </ul>

        <p>{!! str_limit($post->short_description, 100)  !!}</p>
        <hr>
        <a href="{{ route('news.show', $post->slug) }}" class="btn btn-reveal btn-primary">
            <i class="fa fa-plus"></i>
            <span>Read More</span>
        </a>

    </div>
</div>