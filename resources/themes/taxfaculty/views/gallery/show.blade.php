@extends('app')

@section('content')

@section('title')
    Gallery: {{ $folder->title }}
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('gallery.show', $folder->slug) !!}
@stop

<section>
    <div class="container">
        @if(count($photos))
            <div class="row clearfix lightbox" data-plugin-options='{"delegate": "a", "gallery": {"enabled": true}}'>
                @foreach($photos->chunk(4) as $chunk)
                   <div class="row">
                       @foreach($chunk as $photo)
                           <div class="col-md-3">
                               <a class="image-hover" href="{{ asset('storage/'.$photo->url) }}">
                                   <img width="100%" class="thumbnail" src="{{ asset('storage/'.$photo->url) }}" alt="Gallery">
                               </a>
                           </div>
                       @endforeach
                   </div>
                @endforeach
            </div>
            <div class="text-center">
                {!! $photos->render() !!}
            </div>
            <div class="row">
                <a href="{{ route('gallery.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back to gallery</a>
            </div>
            @else
            <div class="row">
                <div class="col-md-12 text-center">
                    <h4>There are no photos at this moment</h4>
                    <p>Please check back later..</p>
                    <a href="{{ route('gallery.index') }}" class="btn btn-info"><i class="fa fa-arrow-left"></i> Back to gallery</a>
                </div>
            </div>
        @endif
    </div>
    {{--<div class="container clearfix lightbox" data-img-big="3" data-plugin-options="{&quot;delegate&quot;: &quot;a&quot;, &quot;gallery&quot;: {&quot;enabled&quot;: true}}">--}}
        {{--@if(count($folder->photos))--}}
            {{--@foreach($folder->photos->chunk(3) as $chunk)--}}
                {{--<div class="row">--}}
                    {{--@foreach($chunk as $photo)--}}
                        {{--<div class="col-md-4">--}}
                            {{--<a class="image-hover" href="{!! $photo->url !!}">--}}
                                {{--<img src="{!! $photo->url !!}" alt="..." width="100%">--}}
                            {{--</a>--}}
                        {{--</div>--}}
                    {{--@endforeach--}}
                {{--</div>--}}
            {{--@endforeach--}}
        {{--@else--}}
            {{--<div class="row">--}}
                {{--<div class="col-md-12 text-center">--}}
                    {{--<h4>There are no photos at this moment</h4>--}}
                    {{--<p>Please check back later..</p>--}}
                    {{--<a href="{{ route('gallery.index') }}" class="btn btn-info"><i class="fa fa-arrow-left"></i> Back to gallery</a>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--@endif--}}


    {{--</div>--}}
</section>
@endsection