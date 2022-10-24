@extends('app')

@section('content')

@section('title')
    Online Courses
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('courses') !!}
@stop

<section class="alternate" style="background-image: url('/assets/frontend/images/demo/wall2.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @foreach($courses as $course)
                    @include('courses.partials.course')
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection