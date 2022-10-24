@extends('app')

@section('title')
    ClickMeeting Test
@stop

@section('intro')
    Clickmeeting Test
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('events') !!}
@stop

@section('styles')
    <link rel="stylesheet" href="/assets/frontend/css/theme4.css">
    <style>
        .navbar-brand{
            display: none!important;
        }
    </style>
@endsection

@section('content')
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <script type="text/javascript" src="https://embed.clickmeeting.com/embed_conference.html?r=17518171477666&w=1024&h=768"></script>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script type="text/javascript" src="https://addevent.com/libs/atc/1.6.1/atc.min.js"></script>
    <script>
        $('document').ready(function () {
            $('.navbar').hidden;
        })
    </script>
@endsection