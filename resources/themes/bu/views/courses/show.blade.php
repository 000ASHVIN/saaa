@extends('app')

@section('meta_tags')
    <meta name="description" content="{{ $course->description }}">
    <meta name="Author" content="{{ $course->name }}"/>
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('course.show', $course) !!}
@stop

@section('title', $course->title)
@section('intro', str_limit($course->short_description, '80'))

@section('content')
    <section style="background-image: url('/assets/frontend/images/course.jpg'); background-size: cover">
        <div class="overlay dark-5"><!-- dark overlay [1 to 9 opacity] --></div>
        <div class="container g-z-index-1 g-py-100 event-inner-banner" style="background-color: rgba(255, 255, 255, 0.8784313725490196)!important;">
            <h2 class="g-font-weight-300 g-letter-spacing-1 g-mb-15 text-center">{{ $course->title }}</h2>
            <hr>
            <div class="row">
                <div class="col-md-3 col-md-offset-2">
                    <p style="font-size: 16px"><i class="fa fa-calendar"></i> <strong>Date:</strong> {{ $course->start_date->toFormattedDateString() }} - {{ $course->end_date->toFormattedDateString() }}</p>
                    @if($course->monthly_enrollment_fee > 0)
                    <p style="font-size: 16px"><i class="fa fa-tag"></i> <strong>Monthly: </strong> R{{ number_format($course->monthly_enrollment_fee, 2) }}</p>
                    @endif
                    <p style="font-size: 16px"><i class="fa fa-tag"></i> <strong>Once-off: </strong> R{{ number_format($course->yearly_enrollment_fee, 2) }}</p>
                </div>

                <div class="hidden-lg hidden-md">
                    <hr>
                </div>

                <div class="col-md-6">
                    @if($course->presenters->count())
                        @if(count($course->presenters) > 2)
                            @foreach($course->presenters as $presenter)
                                <h5>Lecturer: <a href="{{ route('presenters.show', $presenter->id) }}" target="_blank">{{ $presenter->name }}</a> <small>{{ $presenter->title }}</small></h5>
                            @endforeach
                        @else
                            @foreach($course->presenters as $presenter)
                                <h5>Lecturer: <a href="{{ route('presenters.show', $presenter->id) }}" target="_blank">{{ $presenter->name }}</a> <br> <small>{{ $presenter->title }}</small></h5>
                            @endforeach
                            <p>{!! strip_tags(str_limit($course->short_description, 400)) !!}</p>
                        @endif
                    @else
                        <h5>Lecturer: TBA</h5>
                        <p>{!! strip_tags(str_limit($course->short_description, 400)) !!}</p>
                    @endif
                </div>
            </div>

            <div class="row">
                <hr>

                <div class="col-md-12 text-center">
                    <div class="form-group">
                        <a class="btn btn-default" href="{{ route('courses.index') }}">
                            <i class="fa fa-arrow-left"></i> Browse More Courses
                        </a>

                            @if (auth()->guest())
                                <a href="#" data-toggle="modal" data-target="#login" class="btn btn-default" style="text-transform: uppercase; font-size: 14px">
                                    <i class="fa fa-lock"></i> Login
                                </a>
                            @else

                            @if($course->end_date > \Carbon\Carbon::now())
                                <a href="{{ route('courses.enroll', $course->reference) }}">
                                    <button type="button" class="btn btn-primary">
                                        <i class="fa fa-graduation-cap"></i> Apply Now
                                    </button>
                                </a>
                            @else
                                <button class="btn btn-primary disabled">
                                    <i class="fa fa-ticket"></i> Registration closed
                                </button>
                            @endif

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    {!! $course->description !!}

                    <div class="hidden-lg hidden-md">
                        <div style="height: 100px"></div>
                    </div>

                    <hr>
                    @if($course->end_date > \Carbon\Carbon::now())
                        <a href="{{ route('courses.enroll', $course->reference) }}">
                            <button type="button" class="btn btn-primary">
                                <i class="fa fa-graduation-cap"></i> Apply Now
                            </button>
                        </a>
                    @else
                        <button class="btn btn-primary disabled">
                            <i class="fa fa-ticket"></i> Registration closed
                        </button>
                    @endif
                </div>

                <div class="col-md-4 ">
                    <div class="custom-md-4 text-center" style="padding: 15px">
                        <p class="font-lato size-17">Save this course to your <strong>Google</strong> or <strong>Outlook</strong> calendar.</p>
                        <a target="_blank" href="{{ $link->google() }}" class="social-icon social-icon-border social-gplus" data-toggle="tooltip" data-placement="top" title="" data-original-title="Google Calendar">
                            <i class="icon-gplus"></i>
                            <i class="icon-gplus"></i>
                        </a>

                        <a target="_blank" href="{{ $link->ics() }}" class="social-icon social-icon-border social-email3" data-toggle="tooltip" data-placement="top" title="" data-original-title="Outlook Calendar">
                            <i class="icon-email3"></i>
                            <i class="icon-email3"></i>
                        </a>
                    </div>

                    <div class="margin-bottom-20"></div>

                    <div class="custom-md-4 text-center" style="padding: 15px">
                        @foreach($course->tags as $tag)
                            <button class="btn btn-primary btn-xs">{{ \App\Blog\Category::find($tag)->title }}</button>
{{--                            <div class="label label-default" style="margin: 5px">--}}

{{--                            </div>--}}
                        @endforeach
                    </div>

                    <div class="margin-bottom-20"></div>

                    <div class="custom-md-4" style="padding: 15px">

                        @if($course->end_date > \Carbon\Carbon::now())
                            <a href="{{ route('courses.enroll', $course->reference) }}">
                                <button class="btn btn-3d btn-xlg btn-primary btn-block">
                                    <i class="fa fa-graduation-cap"></i> Apply Now
                                </button>
                            </a>
                        @else
                            <button class="btn btn-3d btn-xlg btn-primary btn-block disabled">
                                <i class="fa fa-ticket"></i> Registration closed
                            </button>
                        @endif
                    </div>

                    <div class="margin-bottom-20"></div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

