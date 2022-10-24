@extends('app')

@section('title')
    CPD Certificate
@stop

@section('intro')

@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('dashboard.cpd.show',$cpd) !!}
@stop
<?php 
$video_id = [607,608,609,610,611,612,613,614,667,668];
?>
@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')
            <div class="col-lg-9 col-md-9 col-sm-8">
                <div class="col-sm-12 text-center">
                    <a href="{{ route('dashboard.cpd.certificate.download',[$cpd->id]) }}"
                       class="btn btn-sm btn-primary">Download PDF</a>
                    <br><br>
                </div>
                @if($cpd->certificate->view_path == 'certificates.wob' && $cpd->certificate->source && in_array($cpd->certificate->source->id,$video_id))
                @include("certificates.saaawod",['pdf' => false, 'cpd' => $cpd]);
                @else
                @include($cpd->certificate->view_path,['pdf' => false, 'cpd' => $cpd]);
                @endif
                
            </div>
        </div>
    </section>
@stop

@section('scripts')

@stop