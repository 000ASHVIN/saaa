<div class="col-md-3 col-sm-3">
    <div class="inline-search clearfix margin-bottom-30">
        {!! Form::open(['method' => 'post', 'route' => 'news.search', 'class' => 'widget_search']) !!}
            <input type="search" placeholder="Start Searching..." name="search" class="serch-input">
            <button type="submit">
                <i class="fa fa-search" style="color: white"></i>
            </button>
        {!! Form::close() !!}
    </div>

    <div class="side-nav margin-bottom-60 margin-top-30">

        <div class="side-nav-head">
            <button class="fa fa-bars"></button>
            <h4>CATEGORIES</h4>
        </div>

        <ul class="list-group list-group-bordered list-group-noicon uppercase">
            @foreach($categories as $category)
                @if(count($category->publishedPosts))
                    <li class="list-group-item {{ isActive('news/'.$category->slug, true) }}"><a href="{{ route('news.index', $category->slug) }}"><span class="size-11 text-muted pull-right">({{ count($category->publishedPosts) }})</span>{{ $category->title }}</a></li>
                @endif
            @endforeach
                <li class="list-group-item"><a href="{{ route('news.index') }}"><span class="size-11 text-muted pull-right"></span> Show All</a></li>
        </ul>
    </div>

    <hr>

    @if(count($comments))
        <div style="display:none;" class="tabs nomargin-top hidden-xs">
            <ul class="nav nav-tabs nav-bottom-border nav-justified">
                <li class="active">
                    <a href="#tab_1" data-toggle="tab" aria-expanded="true">
                        Recent Comments
                    </a>
                </li>
            </ul>


            <div class="tab-content margin-top-30">
                <div id="tab_1" class="tab-pane active">
                    @foreach($comments as $comment)
                        <div class="row tab-post">
                            <div class="col-md-3 col-sm-3 col-xs-3">
                                <a href="{{ route('news.show', $comment->post->slug) }}">
                                    <img src="/assets/frontend/images/user-icon.png" width="50" alt="">
                                </a>
                            </div>
                            <div class="col-md-9 col-sm-9 col-xs-9">
                                <a href="{{ route('news.show', $comment->post->slug) }}" class="tab-post-link">{{ str_limit($comment->post->title, 60) }}</a>
                                <small style="font-weight: bold">{{ date_format(\Carbon\Carbon::parse($comment->created_at), 'F d Y') }} - {{ $comment->name }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>