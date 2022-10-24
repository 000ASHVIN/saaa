@extends('app')

@section('content')

@section('title')
    Meet Our Presenters
@stop

@section('intro')
    SA Accounting Academy fantastic Presenters
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('Presenters') !!}
@stop

<style>
        img.thumbnail.rounded {
            height: 316px;
            width: 253px;
            filter: grayscale(100%);
        }
</style>

<section>
    <div class="container">
        @foreach($presenters->chunk(4) as $chunked)
            <div class="row">
                @foreach($chunked as $presenter)
                    <div class="col-sm-3 col-xs-3 col-md-3">
                        <a href="/presenters/show/{{$presenter->slug}}">
                            <div class="">
                                <?php
                                $url='';
                                if(isset($presenter->avatar)){
                                    // {{ asset('storage/'.$presenter->avatar) }} 
                                    if (!preg_match("~^(?:f|ht)tps?://~i",$presenter->avatar)) { 
                                        // If not exist then add http storage link
                                        $url = asset('storage/'.$presenter->avatar); 
                                    } 
                                    else{
                                        $url = $presenter->avatar;
                                    }
                                }
                            ?>
                            <img class="img-responsive rounded thumbnail"  height="50%" src="{{ $url }} " alt="">
                            </div>
                            <div class="caption text-center">
                               <p class="nomargin">{{ $presenter->name }}</p>
                           </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <hr>
        @endforeach

        <div class="text-center">
            {!! $presenters->render() !!}
        </div>
    </div>
</section>
@endsection