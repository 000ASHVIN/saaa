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
.radio, .checkbox {
    display: inline-block;
    margin: 0 15px 3px 0;
    padding-left: 27px;
    font-size: 15px;
    line-height: 27px;
    color: #404040;
    cursor: pointer;
}
.radio input, .checkbox input {
    position: absolute;
    left: -9999px;
}
.radio i, .checkbox i {
    position: absolute;
    top: 5px;
    left: 0;
    display: block;
    width: 19px;
    height: 19px;
    outline: none;
    border-width: 2px;
    border-style: solid;
    border-color: rgba(0,0,0,0.3);
    background: rgba(255,255,255,0.3);
}
.checkbox input + i:after {
    content: '\f00c';
    top: 0;
    left: 0px;
    width: 15px;
    height: 15px;
    font: normal 12px/16px FontAwesome;
    text-align: center;
    color: rgba(0,0,0,8);
    position: absolute;
    opacity: 0;
}
.checkbox input:checked + i:after {
    opacity: 1;
}
.title {
    margin-top: 20px;
}
    </style>
@stop

@section('content')

<?php //$profession=New \App\Profession\Profession(); 
$staff = collect();
$company = collect();
$staffs = collect();
if($member){ 
    if($member->company_admin()){
       $company[] = $member->company;
       $staffs = $member->company->staff;
    }
}
?>
    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            @include('admin.members.includes.nav')
            <div class="col-md-9 col-sm-9 nopadding">
                <div class="row">
                    <div class="col-md-12">

                        <renew-company-subscription :plan="{{ $member->subscription('cpd') ? $member->subscription('cpd')->plan : null }}" :staffs="{{ $staffs }}" :companys="{{ $company }}" :last_staff_count="{{ $last_staff_count }}" inline-template>
                             {!! Form::open(['method' => 'post', 'id' => 'renew_subscription', 'route' => ['renew_company_subscription', $member->id]]) !!}

                             <fieldset>
                                <legend>
                                    Plan
                                </legend>
                                <input name="plan_id" class="form-control" type="hidden" value="@{{ plan ? plan.id : 0 }}">
                                <input name="plan" class="form-control" type="text" value="@{{ plan ? plan.name : 'N/A' }}" disabled>
                            </fieldset>

                             <fieldset>
                                <legend>No. of staff members</legend>
                                <input name="staff" class="form-control" type="text" v-model="staff_count" >

                                <div class="row" v-if="staff_count != '' && staff_count < staffs.length">
                                    <div class="col-md-12">
                                        <p class="title">Select Staff that you want to renew:</p>
                                        <p class="text-danger" v-if="staff_error">Please select only @{{ staff_count }} staff.</p>
                                    </div>
                                    <div class="col-md-6" v-for="staff in staffs" :key="staff.id">
                                        <div class="form-group">
                                            <label class="checkbox">
                                                <input type="checkbox" name="selected_staffs[]" value="@{{ staff.id }}" v-model="selectedStaff" style="text-transform: capitalize"> @{{ staff.first_name + " " + staff.last_name }} <i></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <div >{!! Form::submit('Renew', ['class' => 'btn btn-primary', '@click.prevent' => 'submit']) !!}</div>
                            {!! Form::close() !!}
                            

                            <div class="col-md-12">

                                <div class="row">
                                    <hr>
                                    <div v-if="selectedOption == 'event'">

                                    </div>
                                </div>
                            </div>
                        </renew-company-subscription>
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