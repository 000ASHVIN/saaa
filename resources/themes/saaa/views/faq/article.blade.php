@extends('app')

@section('content')

    @section('meta_tags')
        <title>SAAA | FAQ | {{ $article->question }}</title>
        <meta name="description" content="{{ $article->question }}">
        <meta name="Author" content="SA Accounting Academy"/>
    @stop

    @section('title')
        Frequently asked questions (FAQ)
    @stop

    @section('breadcrumbs')
        {!! Breadcrumbs::render('general_faq_question', $article) !!}
    @stop

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
@endsection