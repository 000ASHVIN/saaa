@extends('app')

@section('title')
    CPD Certificate
@stop

@section('intro')

@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('dashboard.cpd.show',$cpd) !!}
@stop

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
                @include($cpd->certificate->view_path,['pdf' => false, 'cpd' => $cpd]);
            </div>
        </div>
    </section>
@stop

@section('scripts')

@stop