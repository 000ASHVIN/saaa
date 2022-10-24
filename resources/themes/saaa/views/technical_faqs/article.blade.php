@extends('app')

@section('content')

    @section('meta_tags')
        <title>{{ $article->checkMetaTitle() }}</title>
        <meta name="description" content="{{ $article->meta_description }}">
    @stop

    @section('title')
        Frequently asked questions (FAQ)
    @stop

    <section class="page-header hidden-print">
        <div class="container">
            <h1>Frequently asked questions (FAQ)</h1>
        </div>
    </section>

   @if(auth()->check() && auth()->user()->ViewResourceCenter())
        
    <section class="theme-color hidden-print" style="padding: 0px;">
            <div class="container">
                <div class="row">
                    <ol class="breadcrumb" style="padding: 0px">
                        <li class="active">
                            <ol class="breadcrumb" style="padding: 0px">
                                <li class="active">
                                    <ol class="breadcrumb">
                                        <li><a href="{{ route('home') }}">Home</a></li>
                                        <li><a href="{{ route('resource_centre.home') }}">Resource Centre</a></li>
                                        <li><a href="{{ route('resource_centre.technical_faqs.index') }}">FAQs</a></li>
                                        <li class="active">{{ str_limit($article->question, 50) }}</li>
                                    </ol>
                                </li>
                            </ol>
                        </li>
                    </ol>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-9">
                        <h4>{{ $article->question }}</h4>
                        <hr>
                        {!! $article->answer !!}
                    </div>

                    <div class="col-md-3">
                        <h4>Article Tags</h4>
                        <hr>
                        <ul class="nav nav-pills mix-filter" style="text-transform: capitalize;">
                                @foreach($article->faq_tags as $tag)
                                    <li style="background-color: #e3e3e3!important; margin-top: 5px; margin-right: 3px"><a style="color: #173175" id="faqs_tags" href="#">{{ $tag->title }}</a></li>
                                @endforeach
                            </ul>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <div class="border-box text-center" style="background-color: #fbfbfb; min-height:300px">
                            <i class="fa fa-lock" style="font-size: 12vh"></i>
                            <p>You do not have access to this page. <br> The Technical Resource Centre is part of the Designation CPD Subscription Packages.</p>
                            <a class="btn btn-primary" href="{{ route('resource_centre.home') }}"><i class="fa fa-arrow-left"></i> Back</a>
                            <a class="btn btn-default" href="/subscription_plans"><i class="fa fa-arrow-right"></i> View Packages</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection