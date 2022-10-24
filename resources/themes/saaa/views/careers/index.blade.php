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
            @include('careers.includes.sidebar')
            <div class="col-md-9 col-xs-9 col-sm-9">
                <h3 class="margin-bottom-0">We're hiring</h3>
                <p>Click on anyone of the following postions you would like to apply for.</p>
                <hr>

                <div class="tab-content">
                    @foreach($departments as $department)
                        <div role="tabpanel" class="tab-pane" id="{{$department->id}}">
                            @if(count($department->jobs))
                                @foreach($department->jobs as $job)
                                    <div class="border-box">
                                        <a href="/careers/show/{{$job->slug}}">
                                            <div class="row">
                                                <div class="col-md-2 col-sm-2 col-xs-2 text-center"><i class="fa fa-suitcase" style="font-size: 50px"></i></div>
                                                <div class="col-md-6 col-sm-6 col-xs-6">
                                                    <h4 class="margin-bottom-0">{{$job->title}}</h4>
                                                    <p>{{$department->title}}</p>
                                                </div>
                                                <div class="col-md-4 col-sm-4 col-xs-4 text-center" style="margin-top: 6px">
                                                    @if($job->available === 1)
                                                    <span class="label label-danger" style="padding: 0.4em 3.6em;
                                                        font-size: 12px;
                                                        font-weight: bold;">{{$job->period}}</span>
                                                    @else
                                                        <span class="label label-success" style="padding: 0.4em 3.6em;
                                                        font-size: 12px;
                                                        font-weight: bold;">Position Filled</span>
                                                    @endif
                                                    <p><small>{{$job->location}}</small></p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <br>
                                    @endforeach
                                @else
                                    <p>Please note that there is currently no positions available.</p>
                                @endif
                        </div>
                    @endforeach
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