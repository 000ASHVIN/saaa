@extends('app')

@section('content')

@section('title')
    <i class="fa fa-support"></i> Ask an Expert
@stop
<section>
    <div class="container">
        <div class="col-md-10">
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">
                                    {{ $ticket->subject }}
                                </div>
                                <div class="pull-right">
                                    <i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($ticket->created_at)->format('d F Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <p>{!! $ticket->description !!}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach($replies as $reply)
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-left">
                                        {{ $reply->from_email }}
                                    </div>
                                    <div class="pull-right">
                                        <i class="fa fa-calendar"></i> {{ \Carbon\Carbon::parse($reply->created_at)->format('d F Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            {!! $reply->body !!}
                        </div>
                    </div>
                @endforeach

                <a class="btn btn-primary" href="{{ route('resource_centre.legislation.opinion') }}"><i class="fa fa-arrow-left"></i> Back</a>
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