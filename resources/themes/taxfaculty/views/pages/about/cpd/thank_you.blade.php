@extends('app')

@section('content')

@section('title')
    Success: {{ ucwords(str_replace("-"," ",$data['plan'])) }}
@stop

@section('intro')

@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('CPD') !!}
@stop
<section style="background-image: url('/assets/frontend/images/demo/wall2.jpg');">
    <div class="container">
        <div class="row">
           <div class="col-md-12">
               <div class="col-md-6 col-md-offset-3 text-center">
                   <div class="panel panel-default">
                       <div class="panel-heading">Registration complete.</div>
                       <div class="panel-body">
                           <p>Dear {{ $data['first_name'] }} {{ $data['last_name'] }}</p>
                           <p>Thank you for signing up for one of our 2017 CPD subscription packages.</p>
                           <p>Please note that your invoice will be sent before the end of November.</p>
                           <p>If you have any questions concerning your package, please email {{ config('app.email') }}</p>
                       </div>
                   </div>
               </div>
           </div>
        </div>
    </div>
</section>
@endsection