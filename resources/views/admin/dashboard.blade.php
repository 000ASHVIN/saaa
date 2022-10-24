@extends('admin.layouts.master')

@section('title', 'Dashboard')
@section('description', 'Administration Dashboard')

@section('content')
    <!-- start: FEATURED BOX LINKS -->
    <div class="container-fluid container-fullw ">
        <div class="row">
            <div class="col-sm-4">
                <div class="panel panel-white  text-center">
                    <div class="panel-body">
                        <span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i
                                    class="fa fa-smile-o fa-stack-1x fa-inverse"></i> </span>
                        <h2 class="StepTitle">Manage Members</h2>
                        <p class="text-small">
                            Manage all the users on our system
                        </p>
                        <p class="links cl-effect-1">
                            <a href="{{ route('admin.members.index') }}">
                                Manage
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-white  text-center">
                    <div class="panel-body">
                        <span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i
                                    class="fa fa-shopping-cart fa-stack-1x fa-inverse"></i> </span>
                        <h2 class="StepTitle">Manage Store Orders</h2>
                        <p class="text-small">
                            Manage existing store orders
                        </p>
                        <p class="cl-effect-1">
                            <a href="{{ route('admin.orders.index') }}">
                                view more
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="panel panel-white  text-center">
                    <div class="panel-body">
                        <span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i> <i
                                    class="fa fa-terminal fa-stack-1x fa-inverse"></i> </span>
                        <h2 class="StepTitle">Manage Events</h2>
                        <p class="text-small">
                            Manage Upcoming and past events
                        </p>
                        <p class="links cl-effect-1">
                            <a href="{{route('admin.events.index')}}">
                                view more
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end: FEATURED BOX LINKS -->
    <div class="container-fluid container-fullw padding-bottom-10 bg-white">
        <div class="row">
            <div class="col-sm-12">

                <div class="row">
                    <div class="col-md-6 col-lg-6">
                        <div class="panel panel-white ">
                            <div class="panel-heading border-light">
                                <h4 class="panel-title">
								<span class="fa-stack ">
									<i class="fa fa-square fa-stack-2x text-primary"></i>
									<i class="fa fa-users fa-stack-1x fa-inverse"></i>
								</span> {{$users->count()}} New Members
                                    in {{ date_format(\Carbon\Carbon::today(), 'F Y') }}
                                </h4>
                            </div>
                            <div class="panel-body" style="max-height: 250px; height: 250px; overflow-y: auto">
                                <div class="panel-scroll height-200 ps-container ps-active-y">
                                    <table class="table table-striped" style="text-transform: capitalize">
                                        <thead>
                                        <th>Full Name</th>
                                        <th>Membership</th>
                                        <th>Join Date</th>
                                        </thead>
                                        <tbody>
                                        @foreach($users as $user)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.members.show', $user->id) }}" target="_blank">
                                                        {{ str_limit(ucfirst($user->first_name). ' ' . ucfirst($user->last_name), 20) }}
                                                    </a>
                                                </td>

                                                @if($user->subscription('cpd'))
                                                    <td>{{ str_limit($user->subscription('cpd')->plan->name, 20) }}</td>
                                                @else
                                                    <td>No Membership</td>
                                                @endif
                                                <td>{{ $user->created_at->diffForHumans() }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: -48.8182px;">
                                        <div class="ps-scrollbar-x" style="left: 0px; width: 0px;"></div>
                                    </div>
                                    <div class="ps-scrollbar-y-rail" style="top: 51.8182px; height: 200px; right: 3px;">
                                        <div class="ps-scrollbar-y" style="top: 41px; height: 158px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6">
                        <div class="panel panel-white ">
                            <div class="panel-heading border-light">
                                <h4 class="panel-title">
								<span class="fa-stack ">
									<i class="fa fa-square fa-stack-2x text-primary"></i>
									<i class="fa fa-users fa-stack-1x fa-inverse"></i>
								</span> {{ count($active) }} {{ (count($active) >= 2 ? "Members" : "Member") }} Online
                                </h4>
                            </div>
                            <div class="panel-body" style="max-height: 250px; height: 250px; overflow-y: auto">
                                <div class="panel-scroll height-200 ps-container ps-active-y">
                                    <table class="table no-margin" style="text-transform: capitalize">
                                        <tbody>
                                            @if(count($active))
                                                @foreach($active as $user)
                                                    <tr>
                                                        <td>
                                                            @if($user->subscribed('cpd'))
                                                                <span class="text-small block"><i class="fa fa-star"></i> {{ str_limit($user->subscription('cpd')->plan->name, 20) }}</span>
                                                            @else
                                                                <span class="text-small block"><i class="fa fa-star"></i> Free Member</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="glyphicon glyphicon-one-fine-dot"></span>  {{ ucwords(strtolower(str_limit(ucfirst($user->first_name).' '.ucfirst($user->last_name), 20))) }}
                                                        </td>
                                                        <td class="center">
                                                            <div class="cl-effect-13">
                                                                <a class="btn btn-xs btn-success" style="color: #ffffff!important;" href="{{ route('admin.members.show', $user->id) }}">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <div class="ps-scrollbar-x-rail" style="left: 0px; bottom: -48.8182px;">
                                        <div class="ps-scrollbar-x" style="left: 0px; width: 0px;"></div>
                                    </div>
                                    <div class="ps-scrollbar-y-rail" style="top: 51.8182px; height: 200px; right: 3px;">
                                        <div class="ps-scrollbar-y" style="top: 41px; height: 158px;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid container-fullw padding-bottom-10">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="panel panel-white ">
                            <div class="panel-heading border-light">
                                <h4 class="panel-title">
								<span class="fa-stack ">
									<i class="fa fa-square fa-stack-2x text-primary"></i>
									<i class="fa fa-file fa-stack-1x fa-inverse"></i>
								</span>Important files</h4>
                            </div>
                            <div class="panel-body" style="max-height: 250px; height: 250px; overflow-y: auto">
                                <div class="panel-scroll height-200 ps-container ps-active-y">
                                    @if(config('app.theme') == 'saaa')
                                        <table class="table" style="text-transform: uppercase">
                                            <tbody>
                                            <tr>
                                                <td><a>Download Code of Conduct</a></td>
                                                <td><a href="https://www.dropbox.com/s/oe4e4zbo2g4g1d0/SAAA%20Code%20of%20Conduct_2017-07-11.docx?dl=1"><i class="fa fa-download"></a></i></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a>Download Company Policy</a></td>
                                                <td><a href="https://www.dropbox.com/s/95qt3el1ah4ztj8/SAAA%20Company%20Policy%20%281%29.docx?dl=1"><i class="fa fa-download"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a>CPD Discount Calculator</a></td>
                                                <td><a href="https://www.dropbox.com/s/v6odt3ynxh4i0ql/Subscription%20Discount%20Calculator%20-%20updated.xlsx?dl=0"><i class="fa fa-download"></i></a></td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <a>Annual Leave Form Template</a></td>
                                                <td><a href="https://www.dropbox.com/s/8nfzhi7ohn6110w/Leave%20application%20form%20-%20Adjusted.pdf?dl=1"><i class="fa fa-download"></i></a></td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <a>CPD Monthly Statistics</a></td>
                                                <td><a href="{{ route('admin.reports.payments.cpd_stats') }}"><i class="fa fa-download"></i></a></td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <a>Download Complete Subscription Export</a></td>
                                                <td><a href="{{ route('admin.reports.payments.cpd_stats_all') }}"><i class="fa fa-download"></i></a></td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <a>Download Complete Course Export</a></td>
                                                <td><a href="{{ route('admin.reports.payments.course_stats_all') }}"><i class="fa fa-download"></i></a></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    @else
                                        <table class="table" style="text-transform: uppercase">
                                            <tbody>
                                            <tr>
                                                <td><a>Download Code of Conduct</a></td>
                                                <td><a href="/assets/themes/taxfaculty/files/code_of_conduct.pdf"><i class="fa fa-download"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a>Download Company Policy</a></td>
                                                <td><a href="/assets/themes/taxfaculty/files/policy.pdf"><i class="fa fa-download"></i></a></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <a>CPD Monthly Statistics</a></td>
                                                <td><a href="{{ route('admin.reports.payments.cpd_stats') }}"><i class="fa fa-download"></i></a></td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <a>Download Complete Subscription Export</a></td>
                                                <td><a href="{{ route('admin.reports.payments.cpd_stats_all') }}"><i class="fa fa-download"></i></a></td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <a>Download Complete Course Export</a></td>
                                                <td><a href="{{ route('admin.reports.payments.course_stats_all') }}"><i class="fa fa-download"></i></a></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- start: FIRST SECTION -->


    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="panel panel-white " id="visits">
                    <div class="panel-heading border-light">
                        <h4 class="panel-title"> Member Growth </h4>
                    </div>
                    <div collapse="visits" class="panel-wrapper">
                        <div class="panel-body">
                            <div class="height-350">
                                <canvas id="chart1" class="full-width"></canvas>
                                <div class="margin-top-20">
                                    <div class="inline pull-left">
                                        <div id="chart1Legend" class="chart-legend"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="/assets/admin/assets/js/index.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
            Index.init();
        });
    </script>
    <script>
        var months = [];
        var users = [];
        var old_users = [];
    </script>

    @foreach($months as $month => $user)
        <script>
            months.push("{{ $month }}");
            users.push("{{ $user->count() }}");
        </script>
    @endforeach

    @foreach($old_users_months as $old_month)
        <script>
            old_users.push("{{ $old_month->count() }}");
        </script>
    @endforeach
    <script>
        var chart1Handler = function () {
            var data = {
                labels: months,
                datasets: [{
                    label: '2019',
                    fillColor: 'rgba(128, 195, 65, 0.4)',
                    strokeColor: 'rgba(128, 195, 65, 1)',
                    pointColor: '#80c341',
                    pointStrokeColor: '#80c341',
                    pointHighlightFill: '#80c341',
                    pointHighlightStroke: '#80c341',
                    data: users
                },
                    {
                        label: '2018',
                        fillColor: "rgba(23, 49, 117, 0.4)",
                        strokeColor: "rgba(23, 49, 117, 1)",
                        pointColor: "rgba(23, 49, 117, 1)",
                        pointStrokeColor: "#007aff",
                        pointHighlightFill: "#007aff",
                        pointHighlightStroke: "rgba(0, 122, 255, 1)",
                        data: old_users
                    }
                ]
            };

            var options = {


                maintainAspectRatio: false,

                // Sets the chart to be responsive
                responsive: true,

                ///Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: true,

                //String - Colour of the grid lines
                scaleGridLineColor: 'rgba(0,0,0,.05)',

                //Number - Width of the grid lines
                scaleGridLineWidth: 2,

                //Boolean - Whether the line is curved between points
                bezierCurve: true,

                //Number - Tension of the bezier curve between points
                bezierCurveTension: 0.4,

                //Boolean - Whether to show a dot for each point
                pointDot: true,

                //Number - Radius of each point dot in pixels
                pointDotRadius: 4,

                //Number - Pixel width of point dot stroke
                pointDotStrokeWidth: 1,

                //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                pointHitDetectionRadius: 20,

                //Boolean - Whether to show a stroke for datasets
                datasetStroke: true,

                //Number - Pixel width of dataset stroke
                datasetStrokeWidth: 2,

                //Boolean - Whether to fill the dataset with a colour
                datasetFill: true,

                // Function - on animation progress
                onAnimationProgress: function () {
                },

                // Function - on animation complete
                onAnimationComplete: function () {
                },

                //String - A legend template
                legendTemplate: '<ul class="tc-chart-js-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].strokeColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>'
		};
		// Get context with jQuery - using jQuery's .get() method.
		var ctx = $("#chart1").get(0).getContext("2d");
		// This will get the first returned node in the jQuery collection.
		var chart1 = new Chart(ctx).Line(data, options);
		//generate the legend
		var legend = chart1.generateLegend();
		//and append it to your page somewhere
		$('#chart1Legend').append(legend);
	};
	chart1Handler();
</script>
@stop