@extends('app')

@section('content')

    <section class="page-header hidden-print">
        <div class="container">
            <h1> Search Results for {{ $search }}</h1>
        </div>
    </section>

    <section class="theme-color hidden-print" style="padding: 0px;">
        <div class="container">
            <div class="row">
                <ol class="breadcrumb" style="padding: 0px">
                    <li class="active">
                        <ol class="breadcrumb" style="padding: 0px">
                            <li><a href="{{ route('resource_centre.home') }}">Resource Centre</a></li>
                            <li><a href="{{ route('resource_centre.legislation.index') }}">Legislation List</a></li>
                        </ol>
                    </li>
                </ol>
            </div>
        </div>
    </section>


    @if((auth()->user()->subscribed('cpd') && auth()->user()->subscription('cpd')->plan->price != '0') || (auth()->user()->hasCompany() && auth()->user()->hasCompany()->company && auth()->user()->hasCompany()->company->admin()->subscription('cpd') && auth()->user()->hasCompany()->company->admin()->subscription('cpd')->plan->price != '0'))
        <section>
            <div class="container">
                <div class="row mix-grid">
                    <div class="col-md-3">
                        <div class="border-box" style="background-color: #f5f5f5">
                            <br>
                            {!! Form::open(['method' => 'post', 'route' => 'resource_centre.legislation.search']) !!}
                            <div class="form-group @if ($errors->has('search')) has-error @endif">
                                {!! Form::input('text', 'search', null, ['class' => 'form-control', 'placeholder' => 'Search for content', 'style' => 'text-align:center']) !!}
                                @if ($errors->has('search')) <p class="help-block">{{ $errors->first('search') }}</p> @endif
                            </div>
                            <button onclick="spin(this)" style="color: #800000" class="btn-block btn btn-default"><i class="fa fa-search"></i> Search</button>
                            {!! Form::close() !!}
                        </div>
                        <hr>
                        <a href="{{ route('resource_centre.legislation.index') }}" class="btn-block btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                    <div class="col-md-9">
                        @if(count($acts))
                            @foreach($acts as $act)
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <a href="{{ route('resource_centre.acts.show', $act->slug) }}">{{ ucwords($act->name) }}</a>
                                    </div>
                                </div>
                            @endforeach
                            {!! $acts->render() !!}

                        @else
                            <div class="alert alert-info">There are no acts available.</div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @else
        <section>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h4>You do not have access to the Legislation section.</h4>
                        <a class="btn btn-primary" href="{{ route('resource_centre.home') }}"><i class="fa fa-arrow-left"></i> Back</a>
                    </div>
                </div>
            </div>
        </section>
    @endif


@endsection