@extends('admin.layouts.master')

@section('title', 'News Articles')
@section('description', 'All Available Articles')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <div class="container">
                {!! Form::open(['method' => 'get']) !!}
                    {!! Form::input('hidden', 'search', '1') !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group @if ($errors->has('title')) has-error @endif">
                                {!! Form::label('title', 'Title') !!}
                                {!! Form::input('text', 'title', request('title')?request('title'):null, ['class' => 'form-control']) !!}
                                @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group @if ($errors->has('categories')) has-error @endif">
                                {!! Form::label('categories', 'Category') !!}
                                {!! Form::select('categories', $categories->pluck('title', 'id'), request('categories')?request('categories'):null, ['class' => 'form-control', 'autocomplete' => 'off', 'placeholder'=>'Select Category']) !!}
                                @if ($errors->has('categories')) <p class="help-block">{{ $errors->first('categories') }}</p> @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group @if ($errors->has('from_date')) has-error @endif">
                                {!! Form::label('from_date', 'From Date') !!}
                                {!! Form::input('text', 'from_date', request('from_date')?request('from_date'):null, ['class' => 'form-control is-datepicker', 'autocomplete' => 'off']) !!}
                                @if ($errors->has('from_date')) <p class="help-block">{{ $errors->first('from_date') }}</p> @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group @if ($errors->has('to_date')) has-error @endif">
                                {!! Form::label('to_date', 'To Date') !!}
                                {!! Form::input('text', 'to_date', request('to_date')?request('to_date'):null, ['class' => 'form-control is-datepicker', 'autocomplete' => 'off']) !!}
                                @if ($errors->has('to_date')) <p class="help-block">{{ $errors->first('to_date') }}</p> @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-primary" onclick="spin(this)"  style="top: 22px;"><i class="fa fa-search" ></i> Search</button>
                            @if (request('search'))
                                <a href="{{ route('admin.news.index') }}" class="btn btn-default"  style="top: 22px;"><i class="fa fa-times" ></i> Clear</a>
                            @endif
                        </div>
                    </div>
                {!! Form::close() !!}
    
                </div>
              
            </div>
            <div class="col-md-12">
                @if(count(\App\Blog\Category::all()))
                    <a href="{{ route('admin.news.create') }}" class="btn btn-success"><i class="fa fa-newspaper-o"></i> New Article</a>
                @endif
                <hr>
                <table class="table table-striped table-hover">
                    <thead>
                        <th>Article</th>
                        <th>Date</th>
                        <th>Author</th>
                        <th>Comments</th>
                        <th>Views</th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </thead>
                    <tbody>
                    @if(count($articles))
                        @foreach($articles as $article)
                            <tr style="height: 50px">
                                <td>{{ str_limit($article->title, 100) }}</td>
                                <td>{{ date_format($article->created_at, 'F d Y') }}</td>
                                <td>{{ $article->author->first()->name }}</td>
                                <td><strong>{{ count($article->comments) }} Comments</strong></td>
                                <td><strong>{{ count($article->users) }} Views</strong></td>
                                <td><a href="{{ route('admin.news.upload.sendinBlue', $article->slug) }}" class="label label-info"><i class="fa fa-upload"></i> SendinBlue</a></td>
                                <td><a href="{{ route('admin.news.edit', $article->slug) }}" class="label label-info">Edit</a></td>
                                <td><a href="{{ route('admin.post.comments.index', $article->slug) }}" class="label label-info">({{ count($article->pendingComments()) }}) Pending Aproval</a></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6">There are no news articles at this point</td>
                        </tr>
                    @endif
                    </tbody>
                </table>

                {!! $articles->render() !!}
            </div>
        </div>
    </div>

@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script src="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
              // Initialize datepicker
              $('.is-datepicker').datepicker({
                format: 'yyyy-mm-dd'
            });

            // Initialize select2
            $('.select2').select2();
        });
    </script>
@stop