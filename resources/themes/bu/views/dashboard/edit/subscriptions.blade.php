@extends('app')
@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-8">
                @include('dashboard.edit.nav')
                <div class="margin-top-20"></div>
                <a href="{{ route('dashboard.edit.subscription.cancel') }}" class="btn btn-danger">Cancel Subscription</a>
            </div>

            </div>
    </section>
@stop