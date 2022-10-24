@extends('app')

@section('content')

@section('title')
    PI Insurance
@stop

@section('intro')

@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('CPD') !!}
@stop
<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>Dear {{ $data['first_name'] }} {{ $data['last_name'] }}</p>
            </div>
        </div>
    </div>
</section>
@endsection