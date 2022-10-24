@extends('admin.layouts.master')

@section('title', $plan->name.' '.'Members')
@section('description', 'Total'.' '.$plan->subscriptions()->active()->count())

@section('content')
    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            <div class="pull-left">
                @if(isset($active) && isset($suspended))
                    <strong>Suspended:</strong> <div class="label label-danger">{{ count($suspended) }}</div>
                    <strong>Active:</strong> <div class="label label-success">{{ count($active) }}</div>
                @endif
            </div>
            <div class="pull-right">
                {!! Form::open(['method' => 'Post', 'route' => ['admin.stats.members.export', $plan->id]]) !!}
                <div class="form-inline">
                    <div class="form-group">
                        <button class="btn btn-default" type="submit"><i class="fa fa-download"></i> Export</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        <br>
        <div class="row">
            <table class="table table-striped" id="sample-table-2">
                <thead>
                <th>First Name</th>
                <th>Last Name</th>
                <th class="hidden-xs">Email</th>
                <th>Cell</th>
                <th>Status</th>
                <th>Arrears</th>
                <th>Subscription</th>
                <th>View Profile</th>
                </thead>
                <tbody>
                @foreach($subscriptions as $subscription)
                    <tr>
                        <td>{{ $subscription->user->first_name }}</td>
                        <td>{{ $subscription->user->last_name }}</td>
                        <td>{{ $subscription->user->email }}</td>
                        <td>{{ $subscription->user->cell }}</td>
                        <td>{{ ucwords($subscription->user->status) }}</td>
                        <td>
                                <span class=" {{ ($subscription->user->ageAnalysis()? "label-sm label label-warning" : "label-sm label label-success") }}">
                                @if($subscription->user->ageAnalysis())
                                        {{ ($subscription->user->ageAnalysis() <= 1? : $subscription->user->ageAnalysis().' '.'days in arrears') }}
                                    @else
                                        Account in good standing
                                    @endif
                            </span>
                        </td>
                        <td>{{ $subscription->plan->name }}</td>
                        <td><a href="{{ route('admin.members.show', $subscription->user->id) }}" class="btn btn-default">View Profile</a></td>
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