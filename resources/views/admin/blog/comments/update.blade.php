@extends('admin.layouts.master')

@section('title', 'Moderate Comments')
@section('description', 'Moderate Comment')

@section('content')
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <div class="col-md-12">
                @if(count($comments))
                    @foreach($comments as $comment)
                        <div class="panel panel-white" id="panel1">
                            <div class="panel-heading" style="background-color: #fafafa">
                                <h4 class="panel-title"><a href="{{ route('news.show', $comment->post->slug) }}" target="_blank"><strong>Article:</strong> {{ $comment->post->title }}</a></h4>
                            </div>

                            <div class="panel-body">
                                <strong>Comment:</strong> {!! nl2br(e($comment->description)) !!}
                            </div>

                            <div class="panel-footer">
                                <i class="fa fa-user"></i> {{ $comment->name }} | <i class="fa fa-clock-o"></i> {{ date_format($comment->created_at, 'F d Y') }}
                                <div class="pull-right">
                                  <div class="form-group">
                                      <a onclick="spin(this)" href="{{ route('admin.post.comments.approve', $comment->id) }}" class="btn btn-sm btn-success">Approve</a>
                                      <a onclick="spin(this)" href="{{ route('admin.post.comments.decline', $comment->id) }}" class="btn btn-sm btn-danger">Decline</a>
                                  </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                <div class="alert alert-info">
                    No Comments that need approval!
                </div>
                @endif
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });

        $('.select2').select2({
            placeholder: "Please select",
        });

        function spin(this1)
        {
            this1.disabled=true;
            this1.innerHTML=`<i class="fa fa-spinner fa-spin"></i> Working..`;
            this1.intent.url
        }
    </script>
@stop