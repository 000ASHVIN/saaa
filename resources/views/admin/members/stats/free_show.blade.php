@extends('admin.layouts.master')

@section('title', $plan->name.' '.'Members')
@section('description', 'Total'.' '.\App\Users\User::whereDoesntHave('subscriptions')->count())

@section('content')
    <br>
    <div class="row">
        <div class=" panel-white col-sm-12">
            <br>
            <br>
            <div class="pull-right">
                {!! Form::open(['method' => 'Post', 'route' => ['admin.stats.members.export', 0]]) !!}
                <div class="form-inline">
                    <div class="form-group">
                        <button class="btn btn-default" type="submit"><i class="fa fa-download"></i> Export</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>

            <br>
            <br>
            <br>

            <table class="table table-striped" id="sample-table-2">
                <thead>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th class="hidden-xs">Email</th>
                    <th>Cell</th>
                    <th>Account Status</th>
                    <th>Subscription</th>
                    <th>View Profile</th>
                </thead>
                <tbody>
                @foreach($subscriptions as $subscription)
                    <tr>
                        <td>{{ $subscription->first_name }}</td>
                        <td>{{ $subscription->last_name }}</td>
                        <td>{{ $subscription->email }}</td>
                        <td>{{ ($subscription->cell? : $subscription->alternative_cell) }}</td>
                        <td>
                            <span class=" {{ ($subscription->ageAnalysis()? "label-sm label label-warning" : "label-sm label label-success") }}">
                                @if($subscription->ageAnalysis())
                                    {{ ($subscription->ageAnalysis() <= 1? : $subscription->ageAnalysis().' '.'days in arrears') }}
                                @else
                                    Account in good standing
                                @endif
                            </span>
                        </td>
                        <td> Free Plan </td>
                        <td><a href="{{ route('admin.members.show', $subscription->id) }}" class="btn btn-default">View Profile</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="text-center">
                {!! $subscriptions->render() !!}
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script src="/admin/assets/js/index.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop