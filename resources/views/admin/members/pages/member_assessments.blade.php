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
    </style>
@stop

@section('content')
    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            @include('admin.members.includes.nav')
            <div class="col-md-9 col-sm-9 nopadding">
                @if(count($member->completed_Assessments()))
                    <div class="panel-group" id="assessments">
                        @foreach($member->completed_Assessments()->groupBy('assessment.id') as $assessment)
                            <div class="panel panel-white no-radius">
                                <div class="panel-heading border-light">
                                    <h4 class="panel-title" style="font-size: 14px;">
                                        <a data-toggle="collapse" data-parent="#assessments" href="#{{ $assessment->first()->assessment->id }}">Assessment: {{ $assessment->first()->assessment->title }}
                                            <span class="pull-right">Member Attempts: {{ count($assessment) }} / Max Attempts: {{ $assessment->first()->assessment->maximum_attempts }}</span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="{{ $assessment->first()->assessment->id }}" class="collapse">

                                    <div class="panel-body">
                                        <table class="table striped">
                                            <thead>
                                            <th>Attempt</th>
                                            <th>Date</th>
                                            <th>Passed</th>
                                            <th>Percentage</th>
                                            <th style="width: 2%;"></th>
                                            <th style="width: 2%;"></th>
                                            </thead>
                                            <tbody>
                                            @foreach($assessment as $key => $attempt)
                                                <tr>
                                                    <td>Attempt: #  {{ $key+1 }}</td>
                                                    <td>{{ date_format($attempt->created_at, 'd F Y') }}</td>
                                                    <td>{{ ($attempt->passed ? "Yes" : "No") }}</td>
                                                    <td>{{ $attempt->percentage }}%</td>
                                                    <td><a href="#" data-target="#modify_attempt_{{$attempt->id}}" data-toggle="modal"><span class="label label-info"><i class="fa fa-pencil"></i></span></a></td>
                                                    <td><a href="{{ route('admin.event.attempt.destroy', $attempt->id) }}" data-confirm-content="Are you sure you want to delete selected attempt"><span class="label label-danger"><i class="fa fa-close"></i></span></a></td>
                                                </tr>

                                                @include('admin.members.includes.modify_attempt', ['int' => $key+1 ])
                                            @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-white no-radius">
                                <div class="panel-heading border-light">
                                    <h4 class="panel-title">{{ $member->first_name }} has <span class="text-bold">no assessments</span></h4>
                                </div>
                                <div class="panel-body">
                                    <p>If you beleive this is a technical error, Please inform the development team.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
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