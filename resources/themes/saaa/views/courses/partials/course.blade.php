<div class="col-sm-6 col-md-4">
    <div class="border-box coursebox"
         style="background-color: white; margin-bottom: 15px; padding: 0px; min-height: 240px; border-color: #ffffff;">
         {{-- -webkit-box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); -moz-box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); box-shadow: 0px 6px 16px -5px rgba(0,0,0,0.75); --}}
      
        <img style="width: 100%!important;" @if($course->image!="") src="{{ (asset('storage/'.$course->image)) }}" @else
        src="{{ url('assets/themes/taxfaculty/img/courseImg.jpg') }}" @endif eight="180px" width="80px" alt="...">
        <div class="padding" style="padding: 15px; text-align: center">
            <div class="w_title" style="font-weight: bold">
                <h3 class="course-title">{!! $course->title !!}</h3>
            </div>
        </div>
        <div class="hr-divider"><span></span></div>
        <div class="padding" style="padding: 15px; text-align: center">
            <div class="course-info">
                {{-- <p><strong>Date:</strong> {{ date_format($course->start_date, 'd F Y') }}</p>
                <p><strong>Duration:</strong> {{ $course->DurationMonths?$course->DurationMonths.' Months':'' }}</p> --}}
                <p><strong>Cost:</strong> {{ 'R'.number_format($course->yearly_enrollment_fee,0 ,"."," ") }}</p>
            </div>

            {{-- <p class="flexible-payment">Flexible payment options available</p> --}}

            <div style="{{ $course->brochure?'':'visibility:hidden;' }}">
                @include('courses.partials.course_brochure_popup')
                <div class="form-group form-inline">
                    <button type="button"  data-toggle="modal" data-target="#course_brochure_popup{{ $course->id }}" class="btn btn-primary download_brochure form-control">DOWNLOAD COURSE BROCHURE</button>
                </div>
            </div>

            <div class="form-group form-inline row">
                <div class="col-sm-6">
                    <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-primary form-control change-color btn-apply-now">READ MORE</a>
                </div>
                <div class="col-sm-6">
                    <a href="javascript:void(0);" class="btn btn-primary btn-block form-control change-color btn-read-more open-tth-popup" data-course="{{ $course->id }}">Please call me</a>    
                </div>
            </div>

            <div class="form-group form-inline" style="{{ $course->intro_video?'':'visibility:hidden;' }}">
                <a type="button" class="btn btn-primary course_intro_video form-control lightbox"  data-plugin-options="{&quot;type&quot;:&quot;iframe&quot;,&quot;items&quot;:{&quot;src&quot;: &quot;{{ $course->intro_video }}&quot;}}" href="{{ $course->intro_video }}">WATCH INTRO VIDEO</a>
            </div>

        </div>
    </div>
</div> 