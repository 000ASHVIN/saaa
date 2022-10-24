@if(isset($course))
<div class="row">
    <div class="col-md-6">
        <div class="form-group" style="margin-top: 15px !important;">
            <label>Register With Moodle</label>
            @if($course->moodle_course_id == 0 || $course->moodle_course_id == null)
                <a href="{{ route('admin.courses.add_moodle', $course->id) }}" class="label-sm label label-success" style="float: right;">Register</a>
            @else
                <span class="label label-sm label-info" style="float: right;">Already Registered</span>
            @endif
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('title')) has-error @endif">
            {!! Form::label('title', 'Course Title') !!}
            {!! Form::input('text', 'title', null, ['class' => 'form-control']) !!}
            @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group @if ($errors->has('max_students')) has-error @endif">
            {!! Form::label('max_students', 'Max Students') !!}
            {!! Form::input('text', 'max_students', null, ['class' => 'form-control']) !!}
            @if ($errors->has('max_students')) <p class="help-block">{{ $errors->first('max_students') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('start_date')) has-error @endif">
            {!! Form::label('start_date', 'Start Date') !!}
            {!! Form::input('text', 'start_date', null, ['class' => 'is-date form-control']) !!}
            @if ($errors->has('start_date')) <p class="help-block">{{ $errors->first('start_date') }}</p> @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group @if ($errors->has('end_date')) has-error @endif">
            {!! Form::label('end_date', 'End Date') !!}
            {!! Form::input('text', 'end_date', null, ['class' => 'is-date form-control']) !!}
            @if ($errors->has('end_date')) <p class="help-block">{{ $errors->first('end_date') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('monthly_enrollment_fee')) has-error @endif">
            {!! Form::label('monthly_enrollment_fee', 'Monthly Enrollment Fee') !!}
            <span class="pull-right"><small><i>This fee will be charged upon monthly enrollment</i></small></span>
            {!! Form::input('text', 'monthly_enrollment_fee', null, ['class' => 'form-control']) !!}
            @if ($errors->has('monthly_enrollment_fee')) <p
                    class="help-block">{{ $errors->first('monthly_enrollment_fee') }}</p> @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group @if ($errors->has('recurring_monthly_fee')) has-error @endif">
            {!! Form::label('recurring_monthly_fee', 'Monthly Recurring Fee') !!}
            <span class="pull-right"><small><i>This fee will be charged monthly</i></small></span>
            {!! Form::input('text', 'recurring_monthly_fee', null, ['class' => 'form-control']) !!}
            @if ($errors->has('recurring_monthly_fee')) <p
                    class="help-block">{{ $errors->first('recurring_monthly_fee') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('yearly_enrollment_fee')) has-error @endif">
            {!! Form::label('yearly_enrollment_fee', 'Yearly Enrollment Fee') !!}
            <span class="pull-right"><small><i>This fee will be charged upon yearly enrollment</i></small></span>
            {!! Form::input('text', 'yearly_enrollment_fee', null, ['class' => 'form-control']) !!}
            @if ($errors->has('yearly_enrollment_fee')) <p class="help-block">{{ $errors->first('yearly_enrollment_fee') }}</p> @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group @if ($errors->has('recurring_yearly_fee')) has-error @endif">
            {!! Form::label('recurring_yearly_fee', 'Yearly Recurring Fee') !!}
            <span class="pull-right"><small><i>This fee will be charged yearly</i></small></span>
            {!! Form::input('text', 'recurring_yearly_fee', null, ['class' => 'form-control']) !!}
            @if ($errors->has('recurring_yearly_fee')) <p class="help-block">{{ $errors->first('recurring_yearly_fee') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('link')) has-error @endif">
            {!! Form::label('link', 'Link') !!}
            <span class="pull-right"><small><i>Users will only have access to this link once purchased</i></small></span>
            {!! Form::input('text', 'link', null, ['class' => 'form-control']) !!}
            @if ($errors->has('link')) <p class="help-block">{{ $errors->first('link') }}</p> @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group @if ($errors->has('language')) has-error @endif">
            {!! Form::label('language', 'Language') !!}
            <span class="pull-right"><small><i>Course Language</i></small></span>
            {!! Form::input('text', 'language', null, ['class' => 'form-control']) !!}
            @if ($errors->has('language')) <p class="help-block">{{ $errors->first('language') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('presenter[]')) has-error @endif">
            {!! Form::label('presenter[]', 'Please Select Your Instrutor') !!}
            {!! Form::select('presenter[]', $presenters, null, ['class' => 'select2 form-control', 'multiple' => true]) !!}
            @if ($errors->has('presenter[]')) <p class="help-block">{{ $errors->first('presenter[]') }}</p> @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group @if ($errors->has('tags[]')) has-error @endif">
            {!! Form::label('tags[]', 'Categories') !!}
            {!! Form::select('tags[]', $tags, null, ['class' => 'select2 form-control', 'multiple' => true]) !!}
            @if ($errors->has('tags[]')) <p class="help-block">{{ $errors->first('tags[]') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group @if ($errors->has('short_description')) has-error @endif">
            {!! Form::label('short_description', 'Short Description') !!}
            {!! Form::textarea('short_description', null, ['class' => 'form-control']) !!}
            @if ($errors->has('short_description')) <p class="help-block">{{ $errors->first('short_description') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group @if ($errors->has('description')) has-error @endif">
            {!! Form::label('description', 'Description') !!}
            {!! Form::textarea('description', null, ['class' => 'ckeditor form-control ']) !!}
            @if ($errors->has('description')) <p class="help-block">{{ $errors->first('description') }}</p> @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('is_publish')) has-error @endif">
            {!! Form::label('is_publish', 'Status') !!}
            {!! Form::select('is_publish', [
            0 => 'No',
            1 => 'Yes'
            ],null, ['class' => 'form-control']) !!}
            @if ($errors->has('is_publish')) <p class="help-block">{{ $errors->first('is_publish') }}</p> @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('no_of_debit_order')) has-error @endif">
            {!! Form::label('no_of_debit_order', 'No Of Debit Order') !!}
            {!! Form::input('text', 'no_of_debit_order', null, ['class' => 'form-control']) !!}
            @if ($errors->has('no_of_debit_order')) <p class="help-block">{{ $errors->first('no_of_debit_order') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('display_in_front')) has-error @endif">
            {!! Form::label('display_in_front', 'Display in Front') !!}
            {!! Form::select('display_in_front', [
            0 => 'No',
            1 => 'Yes'
            ],null, ['class' => 'form-control']) !!}
            @if ($errors->has('display_in_front')) <p class="help-block">{{ $errors->first('display_in_front') }}</p> @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('exclude_vat')) has-error @endif">
            {!! Form::label('exclude_vat', 'Exclude VAT') !!}
            {!! Form::select('exclude_vat', [
            0 => 'No',
            1 => 'Yes'
            ],null, ['class' => 'form-control']) !!}
            @if ($errors->has('exclude_vat')) <p class="help-block">{{ $errors->first('exclude_vat') }}</p> @endif
        </div>
    </div>
   
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('image')) has-error @endif">
            {!! Form::label('image', 'Image') !!}
            {!! Form::file('image', null, ['class' => 'form-control']) !!}
            <input type="hidden" name="remove_image" id="input_remove_image" value="">
            @if (isset($course) && $course->image)
                <p style="margin-top:5px; text-align:right;" id="course_image_preview">
                    <a href="{{ asset('storage/'.$course->image) }}" target="_blank"><i class="fa fa-link"></i> Course Image</a>
                    <a href="javascript:void(0);" id="remove_course_image" style="color:#AA0000"><i class="fa fa-times"></i></a>
                </p>
            @endif
            @if ($errors->has('image')) <p class="help-block">{{ $errors->first('image') }}</p> @endif
        </div> 
    </div> 

    <div class="col-md-6">
        <div class="form-group @if ($errors->has('brochure')) has-error @endif">
            {!! Form::label('brochure', 'Brochure') !!}
            {!! Form::file('brochure', null, ['class' => 'form-control']) !!}
            <input type="hidden" name="remove_brochure" id="input_remove_brochure" value="">
            @if (isset($course) && $course->brochure)
                <p style="margin-top:5px; text-align:right;" id="course_brochure_preview">
                    <a href="{{ asset('storage/'.$course->brochure) }}" target="_blank"><i class="fa fa-link"></i> Course Brochure</a>
                    <a href="javascript:void(0);" id="remove_course_brochure" style="color:#AA0000"><i class="fa fa-times"></i></a>
                </p>
            @endif
            @if ($errors->has('brochure')) <p class="help-block">{{ $errors->first('brochure') }}</p> @endif
        </div>  
    </div> 
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('intro_video')) has-error @endif">
            {!! Form::label('intro_video', 'Intro video') !!}
            {!! Form::input('text', 'intro_video', null, ['class' => 'form-control']) !!}
            @if ($errors->has('intro_video')) <p class="help-block">{{ $errors->first('intro_video') }}</p> @endif
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('course_type')) has-error @endif">
            {!! Form::label('course_type', 'Front Display Type') !!}
            {!! Form::select('course_type', $course_types,null, ['class' => 'form-control', 'placeholder'=>'Course Type']) !!}
            @if ($errors->has('course_type')) <p class="help-block">{{ $errors->first('course_type') }}</p> @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group @if ($errors->has('type_of_course')) has-error @endif">
            {!! Form::label('type_of_course', 'Type of Course') !!}
            {!! Form::select('type_of_course', ['short' => 'Short Courses', 'semester' => 'Semester'],null, ['class' => 'form-control', 'placeholder'=>'Type of Course', 'id' => 'type_of_course_id',]) !!}
            @if ($errors->has('type_of_course')) <p class="help-block">{{ $errors->first('type_of_course') }}</p> @endif
        </div>
    </div>
    <div class="col-md-6" >
        <div class="no-of-semesters" style="display: none">
            <div class="form-group @if ($errors->has('no_of_semesters')) has-error @endif">
                {!! Form::label('no_of_semesters', 'No of Semesters') !!}
                {!! Form::number('no_of_semesters', null, ['class' => 'form-control', 'id' => 'no_of_semesters_id',]) !!}
                @if ($errors->has('no_of_semesters')) <p class="help-block">{{ $errors->first('no_of_semesters') }}</p> @endif
            </div>
          
          
        </div>
    </div>

</div>
<div class="row">
    <div class="col-md-6 no-of-semesters"   style="display: none">
        <div class="">
            <div class="form-group @if ($errors->has('semester_price')) has-error @endif">
                {!! Form::label('semester_price', 'Semester Price') !!}
                {!! Form::input('text', 'semester_price', null, ['class' => 'form-control']) !!}
                @if ($errors->has('semester_price')) <p class="help-block">{{ $errors->first('semester_price') }}</p> @endif
            </div>
        </div>
       
    </div>
    <div class="col-md-6 no-of-semesters"   style="display: none">
        <div class="">
            <div class="form-group @if ($errors->has('order_price')) has-error @endif">
                {!! Form::label('order_price', 'Purchase Order Price') !!}
                {!! Form::input('text', 'order_price', null, ['class' => 'form-control']) !!}
                @if ($errors->has('order_price')) <p class="help-block">{{ $errors->first('order_price') }}</p> @endif
            </div>
        </div>
    </div>
</div>
<br/>
<button class="btn btn-primary"><i class="fa fa-check"></i> {!! $button !!}</button>