@extends('app')


@section('meta_tags')
    <title>Latest News Articles | {{ config('app.name') }}</title>
    <meta name="description" content="Stay up to date with the latest tax related news articles. Gain access to them here. Individuals Tax News. Corporate Tax News. International Tax News & more.">
    <meta name="Author" content="{{ config('app.name') }}"/>
@endsection

@section('content')

@section('title')
    Latest News Articles
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
                    <h3><i class="fa fa-newspaper-o"></i> Latest <span>News</span></h3>
                </div>
                  @if(count($posts)) 
                    <div class="row">
                        @foreach($posts as $post)
                            @if($post->categories)
                                @foreach($post->categories as $category)
                                    @include('blog.includes.post')
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                @endif 

                @if(count($categories))
                    @foreach($categories as $category)
                        @if(count($category->posts))
                        <div class="heading-title heading-dotted">
                            <h3><i class="fa fa-newspaper-o"></i> {{ $category->title }} <span>News</span></h3>
                        </div>

                        <div class="row">
                                @foreach($category->posts->take(3) as $post)
                                    @include('blog.includes.post')
                                @endforeach
                        </div>
                        @endif
                    @endforeach 
                @else
                    <div class="alert alert-info">
                        There are no news articles at the moment, check back later.
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection