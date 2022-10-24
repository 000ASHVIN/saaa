@extends('app')

@section('title')
    My Company
@stop

<?php
$total = $total_practice_plan;
$staff = ($user->company)?($user->company->staff->count()+$user->company->invites()->where('completed',false)->count())+1:1;
$is_practice = 0;
if(auth()->user()->subscribed('cpd')){
    $is_practice= auth()->user()->subscription('cpd')->plan->is_practice;
}

?>
@section('content')
    <section>
        @include('dashboard.company.includes.bulk_invite')
        @include('dashboard.company.includes.add_users')
        <div class="container"> 
            @include('dashboard.includes.sidebar')
            <div class="col-lg-9 col-md-9 col-sm-8">
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('dashboard.company.staff.certificates.download', $user->id) }}" class="btn btn-info pull-right"><i class="fa fa-lock"></i>Download Certificates of Staff</a>
                    </div>
                </div>
               <div class="row">
                   <div class="col-md-12">
                   
                       @if($user->company_admin())
                       @if($staff >= $total)
                       <a href="#addUser" data-toggle="modal" data-title="addUser" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add Users</a>
                       @endif
                       @if($staff < $total)
                                <a href="#bulkInvite" data-toggle="modal" data-title="bulkInvite" class="btn btn-warning pull-right"><i class="fa fa-users"></i> Bulk Invitation</a>
                           
                                <a href="{{ route('dashboard.company.invite') }}" class="btn btn-primary pull-right"><i class="fa fa-envelope"></i> Send Invitation</a>
                           @endif
                           <a href="{{ route('dashboard.company.pending') }}" class="btn btn-success pull-right"><i class="fa fa-lock"></i>Pending Invitations</a>
                       @endif
                        @if(isset($user->company))
                        <span class="btn btn-lg" style="border: 1px solid;">{{ $staff }} of {{ $total_practice_plan }} users</span>
						@endif
                        <!--<div class="label label-default">{{ count(@$user->company->staff) }} of {{ $total_practice_plan }} users</div>-->
                   </div>
               </div>

                <br>
                <div class="heading-title heading-dotted text-center" style="margin-bottom: 10px">
                    <h4>My <span>Company</span></h4>
                </div>

                @if(isset($user->company))
                    <table class="table table-bordered table-striped">
                        <thead>
                            <th>Full Name</th>
                            <th>Email Address</th>
                            <th class="text-center">CPD Hours</th>
                            <th class="text-center">Remove</th>
                            @if($is_practice)
                            {{--  <th class="text-center">Allocate</th>  --}}
                            @endif
                            
                        </thead>

                        <tbody>
                        @if(count($user->company->staff))
                            @foreach($user->company->staff as $staff)
                                <tr>
                                    <td>{{ $staff->first_name.' '.$staff->last_name }}</td>
                                    <td>{{ $staff->email }}</td>
                                    <td class="text-center"><div class="label label-info">{{ $staff->cpds->sum('hours') }}</div></td>
                                    <td class="text-center"><a data-toggle="tooltip" data-placement="top" title="Remove" onclick="spin(this)" href="{{ route('dashboard.company.cancel_membership', [ $user->company->id, $staff->id ]) }}"><div class="label label-danger"><i class="fa fa-close"></i></div></a></td>
                                    
                                    {{--  @if($is_practice)
                                    @if(!in_array($staff->id,$subscription_group))
                                     <td class="text-center"><a class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Allocate" onclick="spin(this)" href="{{ route('dashboard.company.allocate_membership', [ $user->company->id, $staff->id ]) }}"><div class="label label-success"><i class="fa fa-check"></i></div></a></td>
                                    @else
                                    <div class="disabled"><td class="text-center"><a class="btn btn-success btn-xs disabled" data-toggle="tooltip" data-placement="top" title="Allocate" href="#"><div class="label label-success"><i class="fa fa-check"></i></div></a></td> </div>
                                    @endif 
                                    @endif  --}}
                                </tr> 
                                

                            @endforeach
                        @else
                        <tr>
                            <td colspan="4">You have no staff members at the moment</td>
                        </tr>
                        @endif
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-bordered-dotted margin-bottom-30">
                        <h4>Company setup is required</h4>
                        <p>Please follow the steps below in order to get your comapny setup.</p>
                        <ul class="list-unstyled list-icons">
                            <li><i class="fa fa-check text-success"></i> Click on Setup My Company</li>
                            <li><i class="fa fa-check text-success"></i> Enter your Company Name</li>
                            <li><i class="fa fa-check text-success"></i> Enter your Company Vat Number</li>
                            <li><i class="fa fa-check text-success"></i> Enter the amount of employees, (for indication purposes only)</li>
                            <li><i class="fa fa-check text-success"></i> Enter your company address.</li>
                            <li><i class="fa fa-check text-success"></i> Complete the setup</li>
                        </ul>
                        <br>
                        <p>Once the above setup has been completed, you will be able to add to your staff members to join your CPD subscription package. However, as per our terms and conditions for practice licenses your staff would only be able to attend webinars and <strong>NOT</strong> Seminars.</p>
                    </div>
                    <a href="{{ route('dashboard.company.create') }}" class="btn btn-success"><i class="fa fa-building"></i> Setup My Company</a>
                @endif
            </div>
        </div>
    </section>
@stop 