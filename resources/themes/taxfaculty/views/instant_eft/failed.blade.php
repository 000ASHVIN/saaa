@extends('app')

@section('content')

@section('title')
    Instant EFT Successful
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('instant_eft') !!}
@stop

<section style="background:url('/assets/frontend/images/demo/wall2.jpg')">
    <div class="container">
        <div class="row mix-grid">
            <div class="col-md-12">
                <div class="col-md-6 col-md-offset-3">
                    <div class="border-box" style="background-color: white">
                        <p class="text-center"><i class="fa fa-close fa-4x"></i></p>
                        <p class="text-center">Your payment was unsuccessful. <br> Your Instant EFT payment was unsuccessful, You may now close this window.</p>
                        <hr>
                        <p class="text-center"><i>For more information please email us at {{ config('app.email') }}</i></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection