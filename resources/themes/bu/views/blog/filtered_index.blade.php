@extends('app')

@section('content')

@section('title')
    {{ $category->title }} News Articles
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
                    <h3><i class="fa fa-newspaper-o"></i> {{ $category->title }} <span>News</span></h3>
                </div>

                <div class="row">
                    @if(count($category->posts))
                        @foreach($category->posts as $post)
                            @include('blog.includes.post')
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            We have no {{ $category->title }} articles available at the moment.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection