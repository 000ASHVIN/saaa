@extends('app')

@section('content')

@section('title')
    <i class="fa fa-support"></i> Ask an Expert
@stop

<section>
    <div class="container">
        <div class="col-md-12">
            <div class="row">
                <div class="text-left col-md-12">
                    <div class="border-box" style="background-color: #f2f2f252; border-radius: 10px">
                        <h4><i class="fa fa-support"></i> Thread: {{ $local_thread->title }}</h4>
                        <h4><i class="fa fa-reply-all"></i> Replies: {{ count($local_thread->tickets) }}</h4>
                        <a href="{{ route('dashboard.support_tickets') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> View All Threads</a>
                    </div>
                </div>
            </div>
            <br>
            <div class="alert alert-info">
                <p>To reply to this ticket, please respond to the email that was sent from our ticketing system.</p>
            </div>

            <hr>

            <div class="comments">
                <div class="comment-list" style="line-height: 30px">
                    <div class="row">
                        <div class="col-md-2 col-sm-2 hidden-xs-down">
                            <div class="profile-pic text-center hidden-sm hidden-xs" style="width: 100%">
                                <img class="repsonsive thumbnail" src="http://imageshack.com/a/img923/8590/LXahIE.png" alt="Local Image"
                                     onError="this.onerror=null;this.src='http://imageshack.com/a/img923/8590/LXahIE.png';" style="margin-bottom: 0px; height: 100%; width: 100%">
                            </div>
                            <h4 class="text-center">Initial Ticket <br>  {!! date_format(\Carbon\Carbon::parse($ticket->created_at), 'd F Y') !!}</h4>
                            <hr>
                        </div>

                        <div class="col-md-10 col-sm-10">
                            <div class="card card-default arrow left" style="width: 100%">
                                <div class="card-block">
                                    <div class="comment-post">
                                        <p>{!! nl2br($ticket->description) !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach($replies as $reply)
                    <div class="comment-list" style="line-height: 30px">
                        <div class="row">
                            <div class="col-md-2 col-sm-2 hidden-xs-down">
                                <div class="profile-pic text-center hidden-sm hidden-xs" style="width: 100%">
                                    <img class="repsonsive thumbnail" src="http://imageshack.com/a/img923/8590/LXahIE.png"
                                         alt="Local Image"
                                         onError="this.onerror=null;this.src='http://imageshack.com/a/img923/8590/LXahIE.png';" style="margin-bottom: 0px; height: 100%; width: 100%">
                                </div>
                                <h4 class="text-center">Ticketing System</h4>
                                <hr>
                            </div>

                            <div class="col-md-10 col-sm-10">
                                <div class="card card-default arrow left" style="width: 100%">
                                    <div class="card-block">
                                        <div class="comment-post">
                                            <p>{!! $reply->body_text !!}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="/assets/frontend/plugins/editor.summernote/summernote.js"></script>
    <script>
        $('#ticket').summernote({
            height: 150,
            fontNames: ['Arial'],
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
            ]
        });
    </script>
@stop