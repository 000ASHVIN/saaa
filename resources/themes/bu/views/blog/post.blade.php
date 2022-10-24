@extends('app')

@section('meta_tags')
    <title>SAAA | SA Accounting Academy | CPD Provider</title>
    <meta name="description" content="{!! $post->title !!}">
    <meta name="Author" content="{!! ucfirst($post->author->first()->name) !!}"/>
@endsection

@section('content')

@section('title')
    {{ $post->title }}
@stop

@section('intro')
    Article: {{ $post->title }}
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('article', $post) !!}
@stop

<section>
    <div class="container">
        <div class="row">
            @include('blog.includes.sidebar')
            <div class="col-md-9 col-sm-9">
                <h4 class="blog-post-title">{{ $post->title }}</h4>

                <ul class="blog-post-info list-inline">
                    <li>
                        <i class="fa fa-clock-o"></i>
                        <span class="font-lato">{{ date_format(\Carbon\Carbon::parse($post->created_at), 'F d, Y') }}</span>
                    </li>
                    <li>
                        <i class="fa fa-folder-open-o"></i>
                        @foreach($post->categories as $category)
                            <span class="font-lato">{{ $category->title }}</span>
                        @endforeach
                    </li>
                    <li>
                        <i class="fa fa-user"></i>
                        <span class="font-lato">{{ ucfirst($post->author->first()->name) }}</span>
                    </li>
                </ul>


                <figure class="margin-bottom-20">
                    @if(! $post->image)
                        <img class="img-responsive article-image-single" src="/assets/frontend/images/default-blog.jpg" alt="Default">
                    @else
                        <img class="img-responsive article-image-single" src="{{ asset('storage/'.$post->image) }}" alt="">
                    @endif
                </figure>

                <p>{!! $post->description !!}</p>

                <div class="divider divider-dotted"><!-- divider --></div>

                <h4 class="page-header margin-bottom-60 size-20 clearfix">
                    <span>{{ count($post->comments) }}</span> COMMENTS
                    <div class="hidden-xs pull-right">
                        @include('blog.components.share', ['url' => Request::url()])
                    </div>
                </h4>


                @if(count($post->acceptedComments()))
                    <div class="comments">
                        @foreach($post->acceptedComments() as $comment)
                        <div class="comment-item">
                            <span class="user-avatar">
                                <img class="pull-left" src="/assets/frontend/images/user-icon.png" width="64" height="64" alt="">
                            </span>

                            <div class="media-body">
                                <h4 class="media-heading bold">{{ $comment->name }}</h4>
                                <small class="block">{{ date_format(\Carbon\Carbon::parse($comment->created_at), 'F d, Y') }}</small>
                                {!! nl2br(e($comment->description)) !!}
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-info">
                        There are not comments for this article at the moment, check back later.
                    </div>
                @endif

                <h4 class="page-header size-20 margin-bottom-60">
                    LEAVE A <span>COMMENT</span>
                </h4>

                @if(auth()->user())
                    {!! Form::open(['method' => 'post', 'route' => ['news.comment.store', $post->slug]]) !!}
                    <input type="hidden" value="{{ auth()->user()->full_name() }}" maxlength="100" name="name">
                    <input type="hidden" value="{{ auth()->user()->email }}" maxlength="100" name="email">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>COMMENT</label>
                                <textarea required="required" maxlength="1000" rows="5" class="form-control" name="description"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">

                            <button class="btn btn-3d btn-lg btn-reveal btn-black">
                                <i class="fa fa-check"></i>
                                <span>SUBMIT MESSAGE</span>
                            </button>

                        </div>
                    </div>
                    {!! Form::close() !!}
                @else
                    <div class="alert alert-info">
                        You must be logged in to add a comment, <a href="/login">log in now</a>.
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
        <script>
        var popupSize = {
            width: 780,
            height: 550
        };
        $(document).on('click', '.social-buttons > a', function(e){

            var
                verticalPos = Math.floor(($(window).width() - popupSize.width) / 2),
                horisontalPos = Math.floor(($(window).height() - popupSize.height) / 2);

            var popup = window.open($(this).prop('href'), 'social',
                'width='+popupSize.width+',height='+popupSize.height+
                ',left='+verticalPos+',top='+horisontalPos+
                ',location=0,menubar=0,toolbar=0,status=0,scrollbars=1,resizable=1');

            if (popup) {
                popup.focus();
                e.preventDefault();
            }

        });
    </script>
@endsection