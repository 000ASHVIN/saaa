@extends('app')

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
                @if(count($categories))
                    @foreach($categories as $category)
                        <div class="heading-title heading-dotted">
                            <h3><i class="fa fa-newspaper-o"></i> {{ $category->title }} <span>News</span></h3>
                        </div>

                        <div class="row">
                            @if(count($category->posts))
                                @foreach($category->posts->take(3) as $post)
                                    @include('blog.includes.post')
                                @endforeach
                            @else
                            <div class="alert alert-info">
                                We have no {{ $category->title }} articles available at the moment.
                            </div>
                            @endif
                        </div>
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