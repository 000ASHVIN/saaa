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
                      <p class="text-center"><i class="fa fa-check-circle-o fa-4x"></i></p>
                      <p class="text-center">Thank you for your payment. <br> Your Instant EFT payment was successful, You may now close this window.</p>
                      <hr>
                      <p class="text-center"><i>For more information please contact us on 010 593 0466 or email us at <a href="mailto:info@accountingacademy.co.za">info@accountingacademy.co.za</a></i></p>
                  </div>
              </div>
            </div>
        </div>
    </div>
</section>
@endsection