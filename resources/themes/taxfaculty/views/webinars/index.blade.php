@extends('app')

@section('content')

@section('title')
    Webinar: {{ $webinar->title }}
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('dashboard.webinars') !!}
@stop

<section class="alternate" style="background-image: url('/assets/frontend/images/demo/wall2.jpg');">
    <div class="container">
        <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="{!! $webinar->video_url !!}" width="800" height="450" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
        {{--<iframe src="{!! $webinar->video_url !!}" width="640" height="360"--}}
                {{--webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>--}}
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="modal fade" id="instructions_modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                            <h4 class="modal-title">Webinar Instructions</h4>
                        </div>
                        <div class="modal-body">
                            <p>Thank you for joining the webinar, please follow the below instructions to watch and
                                complete the webinar.</p>
                            <ul>
                                <li>To watch the video, click on the play button.</li>
                                <li>You are allowed to pause and unpause the video, as this <strong>will not</strong>
                                    effect your fellow attendees.
                                </li>
                                <li>Please read carefully through the Webinar Q & A to ensure that you are on time to
                                    meet with the presenter.
                                </li>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <a data-dismiss="modal" class="btn btn-primary"><i class="fa fa-close"></i> Close</a>
                        </div>
                    </div>

                </div>
            </div>


            <div class="col-md-8">

                <p>
                    {!! $webinar->description !!}
                </p>
            </div>

            <div class="col-md-4">
                <div class="border-box" style="background-color: #fafafa; border: 1px solid #eee;">
                    <h4 class="text-center"><i class="fa fa-clock-o"></i> Webinar Q & A</h4>
                    <div class="alert alert-warning">
                        <p>The presenter will be online at {{ date_format(\Carbon\Carbon::parse($webinar->date), 'd F Y') }} at {{ $webinar->time }} to answer all of your questions regarding {{ $webinar->title }}.</p>
                        <p>To join the conversation with the presenter, please click on the "Join the conversation"
                            button and use the password stated belowe.</p>
                    </div>
                    <div class="form-group">
                        <input type="text" disabled="disabled" class="form-control text-center"
                               value="Password: {{ $webinar->webinar_password }}">
                    </div>
                    <a href="{{ $webinar->webinar_url }}" target="_blank" class="btn btn-primary btn-block"><i class="fa fa-comment-o"></i> Join the conversation</a>
                </div>
                <hr>

                <div class="border-box" style="background-color: #fafafa; border: 1px solid #eee;">
                    <h4 class="text-center">Need support <i class="fa fa-question"></i></h4>
                    <div class="alert alert-success">
                        <p>If you are experiencing any problems with the video, please contact us directly.</p>
                    </div>

                    <a href="mailto:{{ config('app.email') }}" class="btn btn-success btn-block"><i class="fa fa-envelope-o"></i> Contact Support</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
    <script>
        $(window).load(function () {
            $('#instructions_modal').modal('show');
        });
    </script>
@stop