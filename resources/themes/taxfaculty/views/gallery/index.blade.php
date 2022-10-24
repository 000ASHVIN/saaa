@extends('app')

@section('content')

@section('title')
    Event Gallery
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('gallery.index') !!}
@stop
<section class="section">
    <div class="container">
        @if(count($folders))
            @foreach($folders->chunk(3) as $chuck)
                <div class="row">
                    @foreach($chuck as $folder)
                        <div class="col-md-4">

                            <a href="{{ route('gallery.show', $folder->slug) }}">
                                <figure class="thumbnail" style="display: flex; justify-content: center; align-items: center;">
                                    <div class="gallery-text" style="position: absolute; word-break: break-word; max-width: 80%">
                                        <h5 class="text-center" style="color: white">{!! $folder->title !!}</h5>
                                    </div>

                                    <img class="img-responsive" src="http://imageshack.com/a/img923/8225/Qr9gqC.jpg" alt="" />
                                </figure>

                                <div class="clearfix text-center">
                                    <div class="label label-primary"> <i class="fa fa-calendar-o"></i> {!! \Carbon\Carbon::parse($folder->date)->diffForHumans() !!}</div>
                                    <div class="label label-primary"><i class="fa fa-tag"></i> {!! ucfirst($folder->type) !!}</div>
                                    <div class="label label-success"><i class="fa fa-photo"></i> {!! count($folder->photos) !!} Photos</div>
                                </div>
                            </a>

                            <br>
                        </div>
                    @endforeach
                </div>
                <hr>
            @endforeach
                <div class="text-center">
                    {!! $folders->render() !!}
                </div>
        @else
        <div class="row">
            <div class="col-md-12 text-center">
                <h4>There are no folders at this moment</h4>
                <p>Please check back later..</p>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection