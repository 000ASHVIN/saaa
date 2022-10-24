@extends('admin.layouts.master')
@section('title', $member->first_name . ' ' . $member->last_name)
@section('description', 'User Profile')

@section('css')
    <link href="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <style>
        .daterangepicker{
            z-index:99999!important;
        }
        .panel-title-right{
            position:absolute;
            right:15px;
            color: #5b5b60;
            font-size: 13px ;
            font-weight: bold;
        }
    </style>
@stop

@section('content')

    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            @include('admin.members.includes.nav')
            <div class="col-md-9 col-sm-9 nopadding">
                <div id="user_activity" class="tab-pane fade in">
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="panel panel-white" id="activities">
                                <div class="panel-heading border-light">
                                    <h4 class="panel-title text-primary">{{ $member->first_name }}'s Recent Activity <span class="panel-title-right">Last Login : {{ $last_login }}</span> </h4>
                                    <paneltool class="panel-tools" tool-collapse="tool-collapse"
                                               tool-refresh="load1" tool-dismiss="tool-dismiss"></paneltool>
                                </div>
                                <div collapse="activities" ng-init="activities=false" class="panel-wrapper">
                                    <div class="panel-body">
                                        @if(count($member->latestActivities()))
                                            <ul class="timeline-xs">
                                                @include('includes.activities.list')
                                            </ul>
                                        @else
                                            <ul class="timeline-xs">
                                                <li>{{ $member->first_name }} has no activities yet.</li>
                                            </ul>
                                        @endif
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
    <script src="/js/app.js"></script>
    <script src="/assets/admin/assets/js/profile.js"></script>
    <script src="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.js"></script>
    <script src="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script>
        jQuery(document).ready(function () {
            Profile.init();
        });
    </script>
    <script src="/assets/admin/assets/js/bootstrap-confirm-delete.js"></script>
    @include('admin.members.includes.spin')
@stop