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
        .actions .btn {
            margin: 5px 0;
        }
    </style>
@stop

@section('content')
    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            @if($member->isPracticePlan())
            <div class="col-md-12">
                <div class="pull-right">
                    <a target="_blank" href="{{ route('member.activity_log.export', $member->id) }}" class="btn btn-sm btn-primary">Export Activity Log</a>
                </div>
            </div>
            @endif
            @include('admin.members.includes.nav')
            <div class="col-md-9 col-sm-9 nopadding">
                <div class="panel panel-white no-radius">
                    <div class="panel-heading border-light">
                        <h4 class="panel-title"><i class="fa fa-lock"></i> <strong>Pending Invites</strong></h4>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <th width="25%">Full Name</th>
                            <th width="50%">Email Address</th>
                            <th class="text-center">Invite Date</th>
                            </thead>
                            <tbody>
                            @if($member->company)
                                @if(count($member->company->invites->where('completed', '==' ,false)))
                                    @foreach($member->company->invites->where('completed', '==' ,false) as $invite)
                                        <tr>
                                            <td>{{ $invite->first_name.' '.$invite->last_name }}</td>
                                            <td>{{ $invite->email }}</td>
                                            <td class="text-center">{{ date_format($invite->created_at, 'd F') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3">You have no new pending invites.</td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td colspan="3">Company has not been setup yet</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                    </div>
                </div>


                <div class="panel panel-white no-radius">
                    <div class="panel-heading border-light">
                        <h4 class="panel-title"><i class="fa fa-unlock"></i> <strong>Staff Members</strong></h4>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <thead>
                            <th width="25%">Full Name</th>
                            <th width="25%">Email Address</th>
                            <th width="25%">Contact Number</th>
                            <th width="25%">Subscription</th>
                            <th class="text-center">CPD Hours</th>
                            <th class="text-center"></th>
                            </thead>
                            <tbody>
                            @if($member->company)
                                @if(count($member->company->staff))
                                    @foreach($member->company->staff as $staff)
                                        <tr>
                                            <td>{{ $staff->first_name.' '.$staff->last_name }}</td>
                                            <td><a target="_blank" href="{{ route('admin.members.show', $staff->id) }}">{{ $staff->email }}</a></td>
                                            <td>{{ ($staff->cell) ? : "None" }}</td>
                                            <td>{{ ($staff->subscription('cpd')) ? $staff->subscription('cpd')->plan->name : "None" }}</td>
                                            <td class="text-center"><div class="label label-info">{{ $staff->cpds->sum('hours') }} Hours</div></td>
                                            {{-- <td><a target="_blank" href="{{ route('admin.members.show', $staff->id) }}" class="btn btn-sm btn-primary">View Profile</a></td> --}}
                                            <td class="text-center actions">
                                                <a target="_blank" href="{{ route('admin.members.show', $staff->id) }}" class="btn btn-sm btn-primary view-profile">View Profile</a>
                                                @if ( !$staff->subscription('cpd') || $member->subscription('cpd')->plan_id != $staff->subscription('cpd')->plan_id)
                                                    {!! Form::open(['method' => 'GET', 'route' => ['member.subscription.allocate', $staff->id]]) !!}
                                                    
                                                        <input type="hidden" name="plan_id" value="{{ $member->subscription('cpd')->plan_id }}">
                                                        {!! Form::submit('Allocate', ['class' => 'btn btn-sm btn-info']) !!}
                                                        
                                                    {!! Form::close() !!}
                                                    
                                                @endif

                                                @if ($staff->subscription('cpd'))
                                                    <a href="/admin/{{ $staff->id }}/subscription/period/update" class="btn btn-sm btn-primary">Update Duration</a>
                                                @endif

                                                @if ($staff->subscription('cpd') && $member->subscription('cpd')->plan_id == $staff->subscription('cpd')->plan_id)
                                                    <a href="/admin/{{ $staff->id }}/subscription/remove" class="btn btn-sm btn-info">Remove Subscription</a>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6">Member have no staff members at the moment</td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td colspan="6">Company has not been setup yet</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel panel-white no-radius">
                    <div class="panel-heading border-light">
                        <h4 class="panel-title"><i class="fa fa-unlock"></i> <strong>Staff CPD Logbook</strong></h4>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-vertical-middle">
                                <thead>
                                    <tr>
                                        @if($member->isPracticePlan())
                                        <th>Employee</th>
                                        @endif
                                        <th>Date</th>
                                        <th>Service Provider</th>
                                        <th class="text-center text-nowrap">Category</th>
                                        <th>CPD Source</th>
                                        <th>CPD Hours</th>
                                        <th>File</th>
                                        <th class="text-center" colspan="3">Verifiable</th>
                                    </tr>
                                </thead>
                                <tbody>
    
                                @if(count($cpds))
                                    @foreach($cpds as $cpd)
                                        <tr>
                                            @if($member->isPracticePlan())
                                            <td>{{ $cpd->user->name }}</td>
                                            @endif
                                            <td>{{ $cpd->date->toFormattedDateString() }}</td>
                                            <td>{{ $cpd->service_provider ? $cpd->service_provider : "Tax Faculty" }}</td>
    
                                            <td class="text-center text-nowrap">{{ ($cpd->category) ? ucfirst(str_replace('_', ' ', $cpd->category)): "-" }}</td>
    
                                            <td>{{ str_limit($cpd->source, 25) }}</td>
    
                                            <td>{{ $cpd->hours }} {{ ($cpd->hours > 1) ? 'Hours' : 'Hour' }}</td>
                                                @if($cpd->attachment)
                                                    <td><a class="label label-primary" href="{{ $cpd->attachment }}"  data-toggle="tooltip" title="Download" data-placement="top" target="_blank">Download</a></td>
                                                @elseif($cpd->hasCertificate())
                                                    <td>
                                                        <a class="label label-primary" data-toggle="tooltip" title="Certificate" data-placement="top" href="{{ route('dashboard.cpd.certificate',[$cpd->id]) }}">Certificate</a>
                                                    </td>
                                                @else
                                                    <td class="text-center">
                                                        &nbsp;-&nbsp;
                                                    </td>
                                                @endif
    
    
                                            <td class="text-center">
                                                @if($cpd->certificate || ($cpd->attachment && $cpd->verifiable))
                                                Yes
                                                @else
                                                    No
                                                @endif
                                            </td>
    
                                            {{-- @if(! $cpd->certificate)
                                                <td class="text-center"><a style="color: white!important;" href="#" data-toggle="modal" data-target="#update_cpd_entry_{{$cpd->id}}" class="btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>
                                                {!! Form::open(['method' => 'Post', 'route' => ['dashboard.cpd.destroy', $cpd->id]]) !!}
                                                    <td>
                                                        <button type="button" class="btn-xs btn-primary" onclick="spin(this)"><i class="fa fa-close"></i></button>
                                                    </td>
                                                {!! Form::close() !!}
                                            @else
                                                <td class="text-center"><a href="#" style="text-decoration: none; background-color: #777777; cursor: default" class="btn-xs btn-primary"><i class="fa fa-pencil"></i></a></td>
                                                <td class="text-center"><a href="#" style="text-decoration: none; background-color: #777777; cursor: default"  class="btn-xs btn-primary"><i class="fa fa-close"></i></a></td>
                                            @endif
                                            @include('dashboard.includes.cpd.update_cpd_entry') --}}
                                        </tr>
                                    @endforeach
                                        {{-- <tr>
                                            <td colspan="9" class="text-center">Total: {{ $cpds_total_hours }} Hours</td>
                                        <tr> --}}
                                @else
                                    <tr>
                                        <td colspan="8">
                                            <p>You currently have no CPD entries, would you like to add some now ?</p>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
    
                            {!! $cpds->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="/admin/assets/js/index.js"></script>
    <script src="/js/app.js"></script>
    <script src="/assets/admin/assets/js/profile.js"></script>
    <script src="/assets/admin/assets/js/bootstrap-confirm-delete.js"></script>
    @include('admin.members.includes.spin')
@stop