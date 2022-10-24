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
                        <h4><i class="fa fa-support"></i> Ticket Subject: {{ $thread->title }}</h4>
                        <h4><i class="fa fa-reply-all"></i> Replies: {{ count($thread->tickets) }}</h4>
                        <a href="{{ route('dashboard.support_tickets') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to tickets </a>
                        <a href="javascript:void(0);" class="btn btn-primary" id="reply_button"><i class="fa fa-reply"></i>Reply</a>
                    </div>
                </div>
            </div>
            <br>
            <hr>
            <div class="comments">
                <div class="comment-list" style="line-height: 30px">
                    <div class="row">
                        <div class="col-md-2 col-sm-2 hidden-xs-down">
                            <h4 class="text-center">Initial Ticket <br>  {!! date_format(\Carbon\Carbon::parse($thread->created_at), 'd F Y') !!}</h4>
                            <hr>
                        </div>

                        <div class="col-md-10 col-sm-10">
                            <div class="card card-default arrow left" style="width: 100%">
                                <div class="card-block">
                                    <div class="card-header pull-right">
                                        <a href="javascript:void(0);" id="cta_edit_ticket"><div class="label label-round label-primary"><i class="fa fa-pencil"></i> Edit</div></a>
                                    </div>
                                    <div class="comment-post">
                                        <p>{!! nl2br($thread->description) !!}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @foreach($thread->tickets as $reply)
                    <div class="comment-list" style="line-height: 30px">
                        <div class="row">
                            <div class="col-md-2 col-sm-2 hidden-xs-down">
                                <h4 class="text-center">Ticketing System <br>  {!! date_format(\Carbon\Carbon::parse($reply->created_at), 'd F Y') !!}</h4>
                                <hr>
                            </div>

                            <div class="col-md-10 col-sm-10">
                                <div class="card card-default arrow left" style="width: 100%">
                                    <div class="card-block">
                                        <div class="comment-post">
                                            <p>{!! $reply->description !!}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div>
                    {!! Form::open(['method' => 'post', 'route' => ['support_ticket.reply', $thread->id], 'id' => 'reply_form']) !!}
                        <div class="row">
                            <div class="col-md-2 col-sm-2 hidden-xs-down">
                            </div>

                            <div class="col-md-10 col-sm-10">
                                {!! Form::textarea('description', null, ['class' => 'form-control', 'id' => 'reply_ticket']) !!}
                                <p class="help-block"></p>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-reply"></i>Reply</button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <div id="edit_ticket" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Edit Ticket</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; top: 16px; right: 29px;"> 
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">  
                    {!! Form::open(['method' => 'post', 'route' => 'support_ticket.update', 'onSubmit'=>'return SubmitTicket()']) !!}
                        <input type="hidden" name="thread_id" value="{{ $thread->id }}">
                        <div class="form-group" id="subject_group">
                            {!! Form::label('subject', 'Subject') !!}
                            {!! Form::input('text', 'subject', $thread->title, ['class' => 'form-control']) !!}
                            <p class="help-block"></p>
                        </div>

                        <div class="form-group" id="description_group">
                            {!! Form::label('description', 'Ticket Description') !!}
                            {!! Form::textarea('description', $thread->description, ['class' => 'form-control', 'id' => 'edit_ticket_summernote']) !!}
                            <p class="help-block"></p>
                        </div>
    
                        {!! Form::submit('Update Ticket', ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>

</section>

@endsection

@section('scripts')
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script>

        $(document).ready(function(){

            setTimeout(() => {

                // For exdit ticket textarea
                $('#edit_ticket_summernote').summernote({
                    height: 200,
                    fontNames: ['Arial'],
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph']],
                    ]
                });

                $('#edit_ticket_summernote').on('summernote.change',function(we, contents, $editable){
                    $('#edit_ticket_summernote').val(contents);
                    $('#edit_ticket_summernote').trigger('change');
                });

            }, 2000);

            // For reply to the ticket textarea
            $('#reply_ticket').summernote({
                height: 150,
                fontNames: ['Arial'],
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                ]
            });

            $('#reply_ticket').on('summernote.change',function(we, contents, $editable){
                $('#reply_ticket').val(contents);
                $('#reply_ticket').trigger('change');
            });

            // Scroll to reply form
            $("#reply_button").on('click', function(){
                $([document.documentElement, document.body]).animate({
                    scrollTop: $("#reply_form").offset().top - 100
                }, 1000);
            })

        })

        $('#cta_edit_ticket').on('click', function(){
            $('#edit_ticket').modal('show');
        });

        // Validation of edit form
        function SubmitTicket() {
            $('.form-group.has-error').removeClass('has-error');
            $('.help-block').html('');

            var subject = $('input#subject').val();
            var description = $('textarea#edit_ticket_summernote').val();
            description = description.replace( /(<([^>]+)>)/ig, '');
            var error = false;

            if(subject.trim() ==null || subject.trim()=='') {
                $('#subject_group').addClass('has-error');
                $('#subject_group .help-block').html('Subject is required.');
                error = true;
            }

            if(description.trim() ==null || description.trim()=='') {
                $('#description_group').addClass('has-error');
                $('#description_group .help-block').html('Description is required.');
                error = true;
            }

            if(!error) {
                return true;
            }
            return false;
        }
    </script>
@stop