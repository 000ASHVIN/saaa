@extends('admin.layouts.master')

@section('title', 'Import New Members')
@section('description', 'Members')

@section('content')
    <br>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>
                    {{ $import->title }}
                    @if($import->description != '')
                        <br>
                        <small>
                            {{ $import->description }}
                        </small>
                    @endif
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                @if($import->completed_successfully)
                    <div class="alert alert-success">
                        <h4>
                            Successfully processed {{ count($import->total_count) }} records.
                        </h4>
                    </div>
                @else
                    <div class="alert alert-danger">
                        <h4>
                            The import process encountered an error.
                        </h4>
                        <pre>
                            {{ $import->error }}
                        </pre>
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <div class="panel panel-white no-radius">
                    <div class="panel-heading border-bottom">
                        <h4 class="panel-title">Results</h4>
                    </div>
                    <div class="panel-body">
                        <div class="text-center">

                            <canvas id="import-results" class="full-width" width="100%" height="100%"
                                    style="width: 100%; height: 100%;"></canvas>

                        </div>
                        <div class="margin-top-20 text-center legend-xs inline">
                            <div id="chart3Legend" class="chart-legend">
                                <ul class="tc-chart-js-legend">
                                    <li>
                                        <span style="background-color:#00d449"></span>
                                        Imported
                                    </li>
                                    <li>
                                        <span style="background-color:#FDB45C"></span>
                                        Existing
                                    </li>
                                    <li>
                                        <span style="background-color:#4A90E2"></span>
                                        Duplicates
                                    </li>
                                    <li>
                                        <span style="background-color:#F7464A"></span>
                                        Invalid
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="clearfix padding-5 space5">
                            <div class="col-xs-3 text-center no-padding">
                                <div class="border-right border-dark">
                                    <span class="text-bold block text-extra-large">
                                        {{ round(($import->imported_count / $import->total_count) * 100) }}%
                                    </span>
                                    <span class="text-light">Imported</span>
                                </div>
                            </div>
                            <div class="col-xs-3 text-center no-padding">
                                <div class="border-right border-dark">
                                    <span class="text-bold block text-extra-large">
                                        {{ round(($import->existing_count / $import->total_count) * 100) }}%
                                    </span>
                                    <span class="text-light">Existing</span>
                                </div>
                            </div>
                            <div class="col-xs-3 text-center no-padding">
                                <div class="border-right border-dark">
                                <span class="text-bold block text-extra-large">
                                    {{ round(($import->duplicates_count / $import->total_count) * 100) }}%
                                </span>
                                    <span class="text-light">Duplicates</span>
                                </div>
                            </div>
                            <div class="col-xs-3 text-center no-padding">
                                <span class="text-bold block text-extra-large">
                                    {{ round(($import->invalid_count / $import->total_count) * 100) }}%
                                </span>
                                <span class="text-light">Invalid</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="panel panel-white no-radius">
                    <div class="panel-heading border-bottom">
                        <h4 class="panel-title">Actions</h4>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            @foreach($actions as $actionKey => $action)
                                <li class="list-group-item">
                                    <i class="fa fa-ticket"></i> <a href="{{ route('admin.import.provider.import.action',[$provider->id,$import->id,$actionKey]) }}">{{ $action['text'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="panel panel-white no-radius">
                    <div class="panel-heading border-bottom">
                        <h4 class="panel-title">Existing Members</h4>
                    </div>
                    <div class="panel-body" style="max-height: 752px; overflow-y: auto">
                        <ul class="list-group">
                            <table class="table">
                                <tbody>
                                @if(count($import->existing))
                                    @foreach($import->existing as $user)
                                        <tr>
                                            <td>{{ $user['first_name'].' '.$user['last_name'] }}</td>
                                            <td>{{ $user['email'] }}</td>
                                            <td><label class="label label-success">{{ \App\Users\User::where('email', $user['email'])->first()->subscription('cpd')->plan->name }} </label></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <li class="list-group-item list-group-item-warning">
                                        <i class="fa fa-warning"></i>
                                        Please check your .csv file, The members you are trying to import already exists.
                                    </li>
                                @endif
                                </tbody>
                            </table>

                        </ul>
                    </div>
                </div>



                <div class="panel panel-white no-radius">
                    <div class="panel-heading border-bottom">
                        <h4 class="panel-title">Imported Members</h4>
                    </div>
                    <div class="panel-body" style="max-height: 752px; overflow-y: auto">
                        <ul class="list-group">
                            <table class="table">
                                <tbody>
                                @if(count($users))
                                    @foreach($users as $user)
                                        @if($user->importable)
                                            <tr>
                                                <td><a target="_blank" href="{{ route('admin.members.show', $user->importable->id) }}">{{ $user->importable['first_name'].' '.$user->importable['last_name'] }}</a></td>
                                                <td>{{ $user->importable['email'] }}</td>
                                                <td><label class="label label-success">{{ ($user->importable->subscribed('cpd')? $user->importable->subscription('cpd')->plan->name : "Free Plan") }}</label></td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <li class="list-group-item list-group-item-warning">
                                        <i class="fa fa-warning"></i>
                                        Please check your .csv file, The members you are trying to import already exists.
                                    </li>
                                @endif
                                </tbody>
                            </table>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script type="text/javascript">
        jQuery(document).ready(function () {
            Main.init();
        });
        // Get context with jQuery - using jQuery's .get() method.
        var context = $("#import-results").get(0).getContext("2d");
        // This will get the first returned node in the jQuery collection.
        var data = [
            {
                value: {{ $import->imported_count }},
                color: "#00d449",
                highlight: "#33ff7a",
                label: "Imported"
            },
            {
                value: {{ $import->existing_count }},
                color: "#FDB45C",
                highlight: "#fed19a",
                label: "Existing"
            },
            {
                value: {{ $import->duplicates_count }},
                color: "#4A90E2",
                highlight: "#7bafea",
                label: "Duplicates"
            },
            {
                value: {{ $import->invalid_count }},
                color: "#F7464A",
                highlight: "#fb9d9f",
                label: "Invalid"
            }

        ];
        var options = {};
        var resultsChart = new Chart(context).Pie(data, options);
    </script>
@endsection