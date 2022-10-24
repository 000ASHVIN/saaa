@extends('admin.layouts.master')

@section('title', 'Members')
@section('description','We have '.count($subscriptions).' Members')

@section('styles')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
@endsection

@section('content')
    <div class="container-fluid container-fullw">
        <div class="row">
            <table id="plans" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <th>Plan</th>
                    <th>Year</th>
                    <th>Interval</th>
                    <th>Subscribers</th>
                    <th>Active</th>
                    <th>Cancelled</th>
                    <th>Renewable</th>
                    <th>View</th>
                </thead>
                <tbody>
                    @foreach($plans as $plan)
                        <tr>
                            <td>{{ $plan->name }}</td>
                            <td>{{ $plan->year }}</td>
                            <td>
                                {{ ucwords($plan->interval) }}ly
                            </td>
                            <td width="5%">{{ $plan->subscriptions()->count() }}</td>
                            <td width="5%">{{ $plan->subscriptions()->active()->count() }}</td>
                            <td width="5%">{{ count($plan->subscriptions()->cancelled()->get()) }}</td>
                            <td width="10%">{{ count($plan->subscriptions()->active()->renewable()->get()) }}</td>

                            <td>
                                {!! Form::open(['method' => 'Post', 'route' => ['admin.stats.members.export', ($plan->id ? : 0)]]) !!}
                                <div class="form-inline">
                                    <div class="form-group">
                                        <a href="{{ route('admin.stats.members', [($plan->id ? : 0)]) }}" class="btn btn-sm btn-info btn-squared btn-o"><i class="fa fa-eye"></i> View</a>
                                    </div>
                                    <div class="form-group">
                                        <button  class="formButton btn btn-sm btn-default btn-squared btn-o" type="submit">Export</button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
@section('scripts')
    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

    <script src="/admin/assets/js/index.js"></script>
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });

        $(document).ready(function() {
            $('#plans').DataTable();
        } );
    </script>
@stop