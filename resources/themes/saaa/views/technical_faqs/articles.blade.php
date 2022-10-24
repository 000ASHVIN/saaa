@extends('app')

@section('styles')
    <style>
        .nav>li>a:focus, .nav>li>a:hover {
            text-decoration: none;
            background-color: #f2f2f2!important;
            color: #173175!important;
        }
    </style>
@endsection

@section('content')
    <section class="page-header hidden-print">
        <div class="container">
            <h1>Category: {{ $category->title }}</h1>
        </div>
    </section>

    <section class="theme-color hidden-print" style="padding: 0px;">
        <div class="container">
            <div class="row">
                <ol class="breadcrumb" style="padding: 0px">
                    <li class="active">
                        <ol class="breadcrumb" style="padding: 0px">
                            <li class="active">
                                <ol class="breadcrumb">
                                    <li><a href="{{ route('home') }}">Home</a></li>
                                    <li><a href="{{ route('resource_centre.technical_faqs.index') }}">Technical Faqs</a></li>
                                    <li class="active">{{ $category->title }}</li>
                                </ol>
                            </li>
                        </ol>
                    </li>
                </ol>
            </div>
        </div>
    </section>

 
    <section>
        <div class="row mix-grid">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="nav mix-filter" style="text-transform: capitalize;">
                            @if(count($categories))
                                @foreach($categories->unique() as $tag)
                                    <li style="background-color: #173175!important; margin-top: 10px; margin-bottom: 10px" data-filter="{{ $tag }}" class="filter"><a style="color: #ffffff" id="faqs_tags" href="#">{{ $tag }}</a></li>
                                @endforeach
                            @else
                                <li data-filter="#" class="filter active"><a href="#">No Tags</a></li>
                            @endif
                        </ul>
                    </div>

                    <div class="col-md-8">
                        @if(count($articles))
                            @foreach($articles as $article)
                                <div class="panel panel-default mix {{$article->tags->title}}">
                                    <div class="panel-heading"><a href="{{ route('resource_centre.technical_faqs.view_solutions', $article->id) }}">{{ $article->question }}</a></div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection