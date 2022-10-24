@extends('app')

@section('content')

@section('title')
    Join our Team
@stop

@section('breadcrumbs')
    {!! Breadcrumbs::render('careers') !!}
@stop

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-3">
                <img width="100%" src="/assets/frontend/images/logo-a.jpg" alt="Careers" style="border-top-right-radius: 10px; border-top-left-radius: 10px">
                <ul class="list-group col_half" id="tabs">
                    @foreach($departments as $department)
                        <li class="list-group-item default-colors" style="border-radius: 0px">
                            <span class="badge label-default-color">{{count($department->jobs)}}</span>
                            <a href="/careers">{{$department->title}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="col-md-9 col-xs-9 col-sm-9">
                <div class="tab-content">
                    <h4>{{$job->title}}</h4>
                    <h5>Department: {{$job->department->title}} <br> Location: {{$job->location}} <br> Job Duration: {{$job->period}} </h5>
                    <hr>

                    <h4 class="margin-bottom-0">Job Description: </h4>
                    <p>{{$job->description}}</p>

                    <h4 class="margin-bottom-0">Personality</h4>
                    <p>{{$job->personality}}</p>

                    <h4 class="margin-bottom-0">Skills</h4>
                    <p>{{$job->skills}}</p>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
    <script>
        $('#tabs li').first().addClass("active");
        $('#1').addClass('active');
    </script>
@endsection