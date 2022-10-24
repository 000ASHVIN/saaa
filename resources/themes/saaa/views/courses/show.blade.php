@extends('app')

@section('meta_tags')
    <title>{{ $course->checkMetaTitle() }}</title> 
    <meta name="description" content="{{ $course->meta_description }}">
    
@endsection

@section('breadcrumbs')
    {!! Breadcrumbs::render('course.show', $course) !!}
@stop

@section('title', $course->title)
@section('intro', str_limit($course->short_description, '80'))

@section('content')
<style>
        .content_desriptions div {
            width: 100% !important;
        }
        .g-recaptcha iframe {
            min-height: 0px !important;
        }
        @media (max-width:768px){
            .custom-md-4{
                position: unset !Important;
        }
        }
        .different-options {
            display: flex;
            justify-content: center;
        }
        .open-tth-popup {
            width: auto;
        }

        /* talk to human css */
        .brochure-popup-background {
            border-radius: 25px;
            background:  #c9c9c8;
            border: none;
        }
        .brochure-popup-border{
            border-bottom: none;
        }
        .brochure-popup-title{
            text-align: center;
            color:  #009cae;
            font-size: 40px;
        } 
        .course_brochure_popup .close {
            margin: 11px;
            color: white;
            font-size: 20px;
        }
        .brochure-popup-required-title{
            font-weight: normal;
            font-style: italic;
        }
        .course_brochure_popup input.form-control.brochure-popup-text {
            border: none !important;
            border-radius: 7px;
        }
        .brochure-popup-text{
            font-weight: bold;
            color: black !important;
        }
        .brochure-popup-footer{
            padding: 15px;
            text-align: center;
            border-top: none;
            margin-top: -30px;
        }
        .modal-dialog {
            border-radius: 30px;
        }
        .download_brochure{
            background: #7d1128;
            /* font-weight: bold; */
        }
        button.btn.btn-primary.download_brochure.form-control {
            border: none !important;
            border-radius: 5px;
            width: 100%;
        }

        button.btn.btn-primary.download_brochure.form-control:focus {
            background-color: #7d1128;
        }
        button.btn.btn-primary.download_brochure.form-control:hover {
            background-color: #7d1128;
        }
        #cell {
            padding-left: 100px !important;
        }
        #course_brochure_popup_login .container_models{
            width: 90%;
            margin: 0 6% 6px 6%;
            font-size: 17px;
        }
        
        #course_brochure_popup_login .modal-footer.brochure-popup-footer a {
            width: 21%;
            border-radius: 5px;
            font-size: 16px;
        }
        .course_brochure_popup .close {
            margin: 11px;
            color: white;
            font-size: 20px;
        }
</style>
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
                                    <a href="{{ route('courses.enroll', $course->slug) }}">
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

                            <a href="javascript:void(0);" class="btn btn-primary change-color btn-read-more open-tth-popup" data-course="{{ $course->id }}">Please Call Me</a> 
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-md-8 content_desriptions">
                    {!! $course->description !!}

                    <div class="hidden-lg hidden-md">
                        <div style="height: 100px"></div>
                    </div>

                    <hr>
                    @if($course->end_date > \Carbon\Carbon::now())
                        <a href="{{ route('courses.enroll', $course->slug) }}">
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
                    <div class="custom-md-4 text-center action_box_course" style="padding: 15px">
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
                            <a href="{{ route('courses.enroll', $course->slug) }}">
                                <button class="btn btn-3d btn-xlg btn-primary btn-block">
                                    <i class="fa fa-graduation-cap"></i> Apply Now
                                </button>
                            </a>
                        @else
                            <button class="btn btn-3d btn-xlg btn-primary btn-block disabled">
                                <i class="fa fa-ticket"></i> Registration closed
                            </button>
                        @endif

                        @include('courses.partials.course_brochure_popup')
                        @if ($course->brochure)
                            <button type="button"  data-toggle="modal" data-target="#course_brochure_popup{{ $course->id }}" class="btn btn-primary download_brochure form-control" style="margin: 15px 0px;">Download Course Brochure</button>
                        @endif

                        <a href="javascript:void(0);" class="btn btn-primary btn-block form-control change-color btn-read-more open-tth-popup" data-course="{{ $course->id }}">Please Call Me</a> 
                    </div>

                    <div class="margin-bottom-20"></div>
                </div>
            </div>
        </div>
        </div>
    </section>

    @include('courses.partials.talk_to_human')
    @include('courses.partials.login_popup')
@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        // Open popup for talk to human
     $('.open-tth-popup').on('click', function(){
        var course = $(this).data('course');
        $('#talk_to_human_popup input[name=course_id]').val(course);
        $('#talk_to_human_popup').modal('show');
    });

    var input = document.querySelectorAll(".cell");
    input.forEach(myFunction)
    
    
    function myFunction(item, index) {
        window.intlTelInput(item, {
            // allowDropdown: false,
            autoHideDialCode: false,
            autoPlaceholder: "off",
            // dropdownContainer: document.body,
            // excludeCountries: ["us"],
            formatOnDisplay: true,
            // geoIpLookup: function(callback) {
            //   $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
            //     var countryCode = (resp && resp.country) ? resp.country : "";
            //     callback(countryCode);
            //   });
            // },
            hiddenInput: "full_number",
                initialCountry: "za",
            // localizedCountries: { 'de': 'Deutschland' },
            nationalMode: false,
            // onlyCountries: ['us', 'gb', 'ch', 'ca', 'do'],
            // placeholderNumberType: "MOBILE",
            // preferredCountries: ['cn', 'jp'],
            separateDialCode: true,
            utilsScript: "/assets/frontend/js/utils.js",
            });
    }
    })
     
</script>

    @if (Session::has('course'))
        
        <script>
            $(document).ready(function(){
                setTimeout(function(){
                    $('#course_brochure_popup_login').modal('show');
                }, 1000);
                setTimeout(function() {
                    window.location = "{{ route('courses.download_brochure', [Session::get('course')]) }}";
                }, 2000);
            });
        </script>
        <?php 
            session()->forget('course')
        ?>
    @endif
@stop

