@extends('app')

@section('content')

@section('title')
    Search Results
@stop

@section('intro')
    Read some news articles online..
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('news') !!}
@stop

<section>
    <div class="container">
        <div class="row">
            @include('blog.includes.sidebar')
            <div class="col-md-9 col-sm-9">

                <div class="heading-title heading-dotted">
                    <h3><i class="fa fa-newspaper-o"></i> Your Search <span>Results</span></h3>
                </div>

                <div class="row">
                    @foreach($posts as $post)
                        <div class="blog-post-item col-md-4">
                            <!-- IMAGE -->
                            <figure class="margin-bottom-20">
                                <img class="img-responsive article-image" src="{{ asset('storage/'.$post->image) }}" alt="">
                            </figure>

                            <h4><a href="{{ route('news.show', $post->slug) }}">{{ str_limit($post->title, 50) }}</a></h4>

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
                            </ul>

                            <p>{!! str_limit($post->short_description, 100)  !!} </p>

                            <hr>
                            <a href="{{ route('news.show', $post->slug) }}" class="btn btn-reveal btn-primary">
                                <i class="fa fa-plus"></i>
                                <span>Read More</span>
                            </a>

                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

@endsection