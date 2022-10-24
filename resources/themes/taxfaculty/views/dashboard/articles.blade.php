@extends('app')

@section('title', 'My News Articles')

@section('breadcrumbs')
    <ol class="breadcrumb" style="padding: 0px">
        <li class="active">
            <ol class="breadcrumb" style="padding: 0px">
                <li class="active">
                    <ol class="breadcrumb">
                        <li><a href="/dashboard">Dashboard</a></li>
                        <li class="active">My Articles</li>
                    </ol>
                </li>
            </ol>
        </li>
    </ol>
@stop

@section('content')
    <section>
        <div class="container">
            @include('dashboard.includes.sidebar')

            <div class="col-lg-9 col-md-9 col-sm-8">
                @if(count($articles))
                    <div class="alert alert-bordered-dotted margin-bottom-30 text-center">
                        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                        <h4><strong><i class="fa fa-newspaper-o"></i> My News Articles</strong></h4>

                        <p>
                            This is where you can keep track of all the news articles that you have read on our website
                        </p>

                        <p>
                            You have read <strong>{{ count($articles) }}</strong> news articles.
                        </p>

                    </div>

                    <table class="table table-striped">
                        <thead>
                            <th>News Article</th>
                            <th>Category</th>
                            <th class="text-center">Comments</th>
                        </thead>
                        <tbody>
                            @foreach($articles as $article)
                                <tr style="height: 50px;">
                                    <td><i class="fa fa-newspaper-o"></i> <a href="{{ route('news.show', $article->slug) }}" target="_blank">{{ str_limit($article->title, 80) }}</a></td>
                                    <td>
                                        @foreach($article->categories as $categoy)
                                            {{ $categoy->title }}
                                        @endforeach
                                    </td>
                                    <td class="text-center"><span class="label label-success">{{ $article->acceptedComments()->count() }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                <div class="alert alert-bordered-dotted margin-bottom-30 text-center">
                    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                    <h4><strong><i class="fa fa-newspaper-o"></i> No news articles listed here?</strong></h4>

                    <p>
                        You have not read any news articles on our website, Once you read a news article it will be displayed <br> here so that you can keep track of it.
                    </p>

                    <hr>
                    <p><a href="{{ route('news.index') }}" class="btn btn-primary"><i class="fa fa-newspaper-o"></i> Start reading now</a></p>
                </div>
                @endif

                <div class="pull-right">
                    {!! $articles->render() !!}
                </div>
            </div>
        </div>
    </section>
@stop

@section('scripts')

@stop