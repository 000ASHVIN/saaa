@extends('admin.layouts.master')

@section('title', 'Webinar Attendees')
@section('description', 'Mark all the attendees as attended at the end of a webinar by uploading the list.')

@section('content')
    <section class="container-fluid container-fullw bg-white ng-scope">
        <br>
        <div class="container">
            <div class="panel-group" id="accordion">
                <div class="col-md-6 col-md-offset-3">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            {!! Form::open(['method' => 'Post', 'route' => 'admin.cpd.post_assign_webinars', 'files' => true]) !!}
                            <div class="form-group @if ($errors->has('event')) has-error @endif">
                                {!! Form::label('event', 'Please select your event that you would like to mark the webinar attendees for') !!}
                                {!! Form::select('event', $events, null, ['class' => 'select2 form-control']) !!}
                                @if ($errors->has('event')) <p class="help-block">{{ $errors->first('event') }}</p> @endif
                            </div>
                            <hr>
                            <div class="form-group">
                                <div class="clip-radio radio-primary">
                                    <input type="radio" id="attended" name="attendance" value="attended">
                                    <label for="attended">Attended the webinar.</label>
                                    <input type="radio" id="not_attended" name="attendance" value="not_attended">
                                    <label for="not_attended">Did not attended the webinar.</label>
                                </div>
                            </div>
                            <hr>

                            <div class="form-group @if ($errors->has('file')) has-error @endif">
                                {!! Form::label('file', 'Please upload your excel email address file') !!}
                                {!! Form::file('file',['class' => 'form-control']) !!}
                                @if ($errors->has('file')) <p class="help-block">{{ $errors->first('file') }}</p> @endif
                            </div>

                            <br>

                            <button type="submit" class="btn btn-o btn-primary" onclick="spin(this)" ;>
                                <i class="fa fa-check"></i> Submit File
                            </button>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>

    <script type="text/javascript">
        $('.select2').select2();
    </script>

    <script>
        function spin(this1) {
            this1.closest("form").submit();
            this1.disabled = true;
            this1.innerHTML = `<i class="fa fa-spinner fa-spin"></i> Working..`;
        }
    </script>
@stop