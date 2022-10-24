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
                {!! Form::open(['method' => 'post', 'route' => 'courses.search']) !!}

                <div class="search-form">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if ($errors->has('title')) has-error @endif">
                                {!! Form::input('text', 'title', null, ['class' => 'form-control event-title-filter text-center', 'placeholder' => 'What do you want to learn?', 'style' => 'height: 50px;']) !!}
                                @if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
                            </div>
                        </div>
                    </div>
                    <center>
                        <button class="btn btn-primary" onclick="search_spin(this)"><i class="fa fa-search"></i> Seach For Courses</button>
                    </center>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">

                @foreach($courses as $course)
                    @include('courses.partials.course')
                @endforeach

                {!! $courses->render() !!}
            </div>
        </div>
    </div>
</section>
@endsection