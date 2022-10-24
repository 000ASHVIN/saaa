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

@section('styles')
    <style type="text/css">
        .no-margin-bottom {
            margin-bottom: 0;
        }
        .course_address div.row>div {
            margin-bottom: 0px;
        }
    </style>
@endsection

@section('content')
<?php
$array =  (env('COURSE_PLAN_ID')!=null)?explode(",",env('COURSE_PLAN_ID')):[];;
?>
    <section style="background-image: url('/assets/frontend/images/demo/wall2.jpg'); padding-top: 40px;">
        <enroll :user="{{ auth()->user()->load('cards') }}" :course="{{ $course }}" :donations="{{ env('DONATIONS_AMOUNT') }}" inline-template>
            <div class="container">
                <div id="app">
                    <div class="col-md-10 col-md-offset-1">
                        @include('courses.partials.payment_option')
                        @include('courses.partials.coupon_code')
                        @include('courses.partials.billing_options')
                        @include('courses.partials.credit_card')
                        @include('courses.partials.eft')
                    </div>
                </div>
            </div>
        </enroll>
    </section>
@stop

@section('scripts')
    <script src="/assets/frontend/plugins/form.masked/jquery.maskedinput.js"></script>
@stop