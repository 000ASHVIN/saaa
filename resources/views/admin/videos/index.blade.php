@extends('admin.layouts.master')

@section('title', 'Videos')
@section('description', 'All videos')
@section('styles')
<link rel="stylesheet" href="/assets/frontend/plugins/magnific-popup/magnific-popup.css">
<style>
    .mfp-wrap {
        z-index: 9999999 !important;
    }
</style>
@endsection
@section('content')
    <br>


      <div class="container-fluid container-fullw padding-bottom-10 bg-white">
      <div class="row">
        <div class="col-md-4">
            {!! Form::open(['method' => 'get', 'route' => 'admin.video.search']) !!}
            <div class="form-group @if ($errors->has('event_name')) has-error @endif">
                {!! Form::label('title', 'Search Videos') !!}
                {!! Form::input('text', 'title', \Input::has('title')?\Input::get('title'):"", ['class' => 'form-control', 'placeholder' => 'Searching']) !!}
                @if ($errors->has('title')) <p class="help-block">{{ $errors->first('event_name') }}</p> @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group @if ($errors->has('tag')) has-error @endif">
            {!! Form::label('tag', 'Tag') !!}
            {!! Form::select('tag', [
                '' => 'Select Tag',
                'studio' => 'Studio',
                'webinar' => 'Webinar Recording'
            ],\Input::has('tag')?\Input::get('tag'):"", ['class' => 'form-control']) !!}
            @if ($errors->has('tag')) <p class="help-block">{{ $errors->first('tag') }}</p> @endif
            </div>
        </div>

        <div class="col-md-4">
            <button class="btn btn-primary" onclick="spin(this)"  style="top: 22px;"><i class="fa fa-search" ></i> Search</button>
            {!! Form::close() !!}
        </div>
    </div>
            <hr>

    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('admin.videos.create') }}" class="btn btn-primary pull-right">Add a video</a>
        </div>
    </div>
    <hr>

    <div class="row">
        <div class="col-md-12">
            @if(count($videos))
                <table class="table table-striped table-responsive">
                    <thead>
                        <tr>
                            <th>Thumbnail</th>
                            <th>Title</th>
                            <th>Tag</th>
                            <th>Watch Video</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($videos as $video)
                        <tr>
                            <td style="width: 125px"><img src="{{ $video->cover }}" alt="Thumbnail" class="thumbnail" style="max-width: 100px; margin-bottom: 0px"></td>
                            <td>{{ $video->title }}</td>
                            <td>{{ $video->tag }}</td>
                            <!-- <td><a href="{{$video->download_link}}" target="_blank" class="label label-success">Watch Video</a></td> -->
                            <td>@if($video->view_link)<a href="{!! $video->view_link !!}" data-plugin-options="{&quot;type&quot;:&quot;iframe&quot;}" class="label label-success my-webinars-btn-container lightbox"> <i class="fa fa-play"></i> Watch Video</a> @endif</td>
                            <td>
                                <a class="btn btn-xs btn-info" href="{{ route('admin.videos.edit',$video->id) }}">
                                    <i class="fa fa-pencil"></i> Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
                <hr>
                {!! $videos->appends(Input::except('page'))->render() !!}
            @else
                <p>There are no videos</p>
            @endif
        </div>
    </div>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script src="/assets/frontend/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            jQuery(".lightbox").magnificPopup({"type":"iframe"});
        });
    </script>
@stop