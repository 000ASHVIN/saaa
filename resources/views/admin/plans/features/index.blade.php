@extends('admin.layouts.master')

@section('title', 'Membership Plan Features')
@section('description', 'All Features Plans')

@section('content')
    <section>
        <br>
        <div class="tabbable">
            <ul class="nav nav-tabs" id="navigation-tabs">
                <li class="active"><a data-toggle="tab" href="#features">Features</a></li>
                <li class=""><a data-toggle="tab" href="#practice_plan">Practice Plan</a></li>
            </ul>

            <div class="tab-content">
                <div id="features" class="tab-pane fade in active">
                    <div class="container-fluid container-fullw padding-bottom-10 bg-white">

                        {!! Form::open(['method' => 'get', 'route' => 'admin.plans.features.index']) !!}
                        <div class="form-group @if ($errors->has('search')) has-error @endif">
                            {!! Form::label('search', 'Search Features') !!}
                            {!! Form::input('text', 'search', $feature_search, ['class' => 'form-control', 'placeholder' => 'Ethics Independence and NOCLAR']) !!}
                            @if ($errors->has('search')) <p class="help-block">{{ $errors->first('search') }}</p> @endif
                        </div>

                        <button class="btn btn-primary" onclick="spin(this)"><i class="fa fa-search"></i> Search</button>
                        {!! Form::close() !!}
                        <hr>
                        <table class="table table-responsive table-hover table-striped">
                            <thead>
                                <th>Feature Title</th>
                                <th colspan="3">Feature Description</th>
                            </thead>
                            <tbody>
                                @if(count($features))
                                    @foreach($features as $feature)
                                        <tr>
                                            <td style="width: 40%">{{ $feature->name }}</td>
                                            <td style="width: 50%">{{ ($feature->description ? : "No Description") }}</td>
                                            <td style="width: 5%" class="text-center">
                                                <a class="btn btn-info" href="{{ route('admin.plans.features.edit', $feature->slug) }}">
                                                    <i class="ti-pencil"></i>
                                                </a>
                                            </td>
                                            <td style="width: 5%" class="text-center">
                                                <a class="btn btn-danger" href="{{ route('admin.plans.features.destroy', $feature->slug) }}">
                                                    <i class="ti-close"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3">No Features available</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <a href="{{ route('admin.plans.features.create') }}" class="btn btn-sm btn-primary">Add New</a>
                        <div class="text-right">
                            <div class="">{!! $features->render() !!}</div>
                        </div>

                    </div>
                </div>
                <div id="practice_plan" class="tab-pane">
                    @include('admin.plans.practice_plan.index')
                </div>
            </div>
    </section>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });

        $(document).ready(function(){

            $("#navigation-tabs > li > a").on("shown.bs.tab", function(e) {
                var id = $(e.target).attr("href").substr(1);
                if(window.history.pushState) {
                    window.history.pushState(null, null, '#'+id);
                }
                else {
                    window.location.hash = id;
                }
            });

            if (window.location.hash) {
                $("a[href='" + location.hash + "']").tab("show");
            }

        });

    </script>
@stop