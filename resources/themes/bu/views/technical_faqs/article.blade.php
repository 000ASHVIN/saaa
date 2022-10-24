@extends('app')

@section('content')

    <section class="page-header hidden-print">
        <div class="container">
            <h1>{{ $article->title }}</h1>
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
                                    <li><a href="{{ route('resource_centre.home') }}">Resource Centre</a></li>
                                    <li><a href="{{ route('resource_centre.technical_faqs.solutions',\App\SolutionSubFolder::where('sub_folder_id', $article->solution_sub_folder_id)->first()->sub_folder_id ) }}">{{ \App\SolutionSubFolder::where('sub_folder_id', $article->solution_sub_folder_id)->first()->name }}</a></li>
                                    <li class="active">{{ str_limit($article->title, 50) }}</li>
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
                    <h4>{{ $article->title }}</h4>
                    <hr>
                    {!! $article->description !!}
                </div>

                <div class="col-md-3">
                    <h4>Article Tags</h4>
                    <hr>
                    <ul class="nav nav-pills mix-filter" style="text-transform: capitalize;">
                        @foreach($article->tags as $tag)
                            <li style="background-color: #e3e3e3!important; margin-top: 10px; margin-bottom: 10px"><a style="color: #800000" id="faqs_tags" href="#">{{ $tag }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection