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

<?php //$profession=New \App\Profession\Profession(); 
$staff = collect();
$company = collect();
if($member){ 
    if($member->company_admin()){
       $company[] =  $member->company;
       $staff =  $member->company->staff;
    }
}
?>
    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            @include('admin.members.includes.nav')
            <div class="col-md-9 col-sm-9 nopadding">
                <div class="row">
                    <div class="col-md-12">

                        <plan-order-template :plans="{{ $plan->toJson() }}"  :staff="{{ $staff }}" :companys="{{ $company }}"  inline-template>
                             {!! Form::open(['method' => 'post', 'route' => ['order_practice_plan_for_user', $member->id]]) !!}

                             <fieldset>
                                <legend>Select Pricing</legend>
                                <label for="plan_type" style="width: 100%!important;">
                                    <select v-on:change="setPlanType" v-model="planType" name="plan_type" id="plan_type" style="width: 100%!important;">
                                        <option value="month" >Monthly Price</option>
                                        <option value="year" >Yearly Price</option>
                                    </select>
                                </label>
                            </fieldset>

                            <fieldset>
                                <legend>
                                    Select Plan
                                </legend>
                                <label for="plan_id" style="width: 100%">
                                    <select v-on:change="changeplanselection" v-model="selectedCourseId" name="plan_id"
                                            id="plan_id" style="width: 100%">
                                            <option v-for="plan in filterPlan" value="@{{ plan.id }}">@{{ plan.name }}</option>
                                          
                                    </select>
                                </label>
                            </fieldset>

                            <div v-if="selectedPlan.is_practice>2">
                                <div class="panel panel-default" v-if=" companys.length == 0">
                                        <div class="panel-heading">Company</div>
                                        <div class="panel-body">
                                            @if($member)
                                            @include('subscriptions.partials.plans.company')
                                            @endif
                                        </div>
                                    </div>
                                    
                                    
                                <div class="panel panel-default" v-if="!is_practice_plan && companys.length > 0">
                                    <div class="panel-heading">Company Staff  <a href="#" v-on:click.prevent="toggleStaff()" class="btn btn-primary">Add Staff</a>  @{{{selectedStaff.length}}} &nbsp;&nbsp;Plan Price = @{{{selectedPlanPrice}}}</div>
                                    <div class="panel-body">
                                
                                        <div class="col-md-12 " v-for="staffs in staff">
                                            <div> <label class="checkbox pt-20" for="check@{{{ staffs.id }}}">
                                                <input type="checkbox" value="@{{{ staffs.id }}}" name="staff[]" id="check@{{{ staffs.id }}}"  @change.prevent="setSelectedStaff($event)">
                                                <i></i> @{{{ staffs.first_name }}} @{{{ staffs.last_name }}}</div>
                                                </label>
                                            
                                        </div>
                                        <div v-if="staff.length == 0">
                                            No staff Member found. Please add New staff Members
                                        </div>
                                        <div class="clearfix"></div>
                                        
                                        <div class="col-lg-12 col-md-12 col-sm-12" v-if="inviteStaff" >
                                                <hr>
                                                @include('dashboard.company.forms.invite_form', ['button' => 'Send Invite'])
                                                </div>
                                    </div> 
                                </div>
                                
                                
                                <div class="panel panel-default" v-if="selectedPlan.pricing_group.length>0">
                                    <div class="panel-heading">Pricing Groups</div>
                                    <div class="panel-body">
                                
                                        <div class="col-md-12 ">
                                            <table class="table table-responsive table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Practice Size</th>
                                                        <th>Maximun Users</th>
                                                        <th>Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr  v-for="pricing in selectedPlan.pricing_group">
                                                        <td> @{{{ pricing.name }}}</td>
                                                        <td>@{{{ pricing.max_user }}}</td>
                                                        <td>@{{{ pricing.price }}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                          
                                            
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                </div>

                            
                                <fieldset v-if="selectedPlan.is_practice">
                                    <legend>
                                       Select Staff
                                    </legend>
                                    <label for="plan_id" style="width: 100%">
                                        <input name="staff" class="form-control" type="text" v-model="staff_count">
                                            
                                    </label>
                                </fieldset>
                                <fieldset>
                                    <legend>
                                        Payment Method
                                    </legend>
                                    <label for="plan_id" style="width: 100%">
                                        <select class="form-control" name="payment_method" v-model="payment_method">
                                            <option selected value="">Please Select...</option>
                                            <option value="debit_order">Debit Order</option>
                                            <option value="credit_card">Credit Card</option>
                                            <option value="eft">EFT</option>
                                        </select>
                                    </label>
                                </fieldset>
                               
                                <div class="col-md-12">

                                    <div class="row">
                                <div class="panel panel-default" v-if="selectedPlan.pricing_group.length>0">
                                    <div class="panel-heading">Pricing Groups</div> 
                                    <div class="panel-body">
                                        <div class="pull-left" style="line-height: 36px;">
                                            You have selected the <strong>@{{ selectedPlan.name }}</strong>
                                            (@{{ selectedPlanPrice }} /<span v-if="selectedPlan.is_practice">user /</span> @{{ selectedPlan.interval | capitalize}}) plan.
                                        </div>
                                        <div class="col-md-12 ">
                                            <table class="table table-responsive table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Practice Size</th>
                                                        <th>Maximun Users</th>
                                                        <th>Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr  v-for="pricing in selectedPlan.pricing_group">
                                                        <td> @{{{ pricing.name }}}</td>
                                                        <td>@{{{ pricing.max_user }}}</td>
                                                        <td>@{{{ pricing.price }}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                          
                                            
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                                </div>
                                </div>
                            <div >{!! Form::submit('Generate Order', ['class' => 'btn btn-primary']) !!}</div>
                            {!! Form::close() !!}
                            

                            <div class="col-md-12">

                                <div class="row">
                                    <hr>
                                    <div v-if="selectedOption == 'event'">

                                    </div>
                                </div>
                            </div>
                        </plan-order-template>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="/assets/themes/saaa/js/app.js"></script>

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