@extends('admin.layouts.master')

@section('title', 'Pending Membership Upgrade')
@section('description', 'All Pending Membership')

@section('content')
    <section>
        <br>
        <div class="container-fluid container-fullw padding-bottom-10 bg-white">
            <table class="table table-responsive table-striped">
                <thead>
                    <tr>
                        <td>User</td>
                        <td >Current Plane</td>
                        <td >From Plane</td>
                        <td >To Plane</td>
                        <td >Upgrade By</td>
                        <td >Reason</td>
                        <td >Requested On</td>
                        <td>Action</td>
                        
                    </tr>
                </thead>
                <tbody>
                    @if($upgradeSubscription->count())
                    @foreach($upgradeSubscription as $upgrade)
                        <tr>
                            <td>{{ $upgrade->member->name }}</td>
                            <td>{{ @$upgrade->member->subscription('cpd')->plan->name }}</td>
                            <td>{{ $upgrade->fromSubscription->name }}</td>
                            <td>{{ $upgrade->toSubscription->name }}</td>
                            <td>{{ $upgrade->adminuser->name }}</td>
                            <td>{!! $upgrade->reason !!}</td>
                            <td>{!! $upgrade->created_at->format('d F Y') !!}</td>
                           
                            <td><a href="{{ route('upgrade_subscription.approve', $upgrade->member_id) }}" class="btn btn-xs btn-info">Confirm Membership</a>
                                 <a href="{{ route('upgrade_subscription.decline', $upgrade->member_id) }}" class="btn btn-xs btn-info">Decline Membership</a></td>
                        </tr>
                    @endforeach
                    @else
                        <tr class="text-center">
                            <td colspan="8"> No Data Found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            {!! $upgradeSubscription->render() !!}
        </div>
    </section>
@stop

@section('scripts')
    <script src="/js/app.js"></script>
    <script>
        jQuery(document).ready(function () {
            Main.init();
        });
    </script>
@stop