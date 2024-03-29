@extends('admin.layouts.master')
@section('title', $member->first_name . ' ' . $member->last_name)
@section('description', 'User Profile')

@section('css')
    <link href="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.css">
    <style>
        .daterangepicker {
            z-index: 99999 !important;
        }
    </style>

    <style>
        .label-container {
            display: block;
            position: relative;
            padding-left: 23px;
            cursor: pointer;
            font-size: 14px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        /* Hide the browser's default checkbox */
        .label-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        /* Create a custom checkbox */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 15px;
            width: 15px;
            background-color: #eee;
        }

        /* On mouse-over, add a grey background color */
        .label-container:hover input ~ .checkmark {
            background-color: #ccc;
        }

        /* When the checkbox is checked, add a blue background */
        .label-container input:checked ~ .checkmark {
            background-color: #2196F3;
        }

        /* Create the checkmark/indicator (hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the checkmark when checked */
        .label-container input:checked ~ .checkmark:after {
            display: block;
        }

        /* Style the checkmark/indicator */
        .label-container .checkmark:after {
            left: 5px;
            top: 2px;
            width: 5px;
            height: 10px;
            border: solid white;
            border-width: 0 3px 3px 0;
            -webkit-transform: rotate(45deg);
            -ms-transform: rotate(45deg);
            transform: rotate(45deg);
        }
    </style>
@stop

@section('content')
    <div class="container-fluid container-fullw bg-white">
        <div class="row">
            @include('admin.members.includes.nav')
        <app-upgrade-subscription :plans="{{ $subscription_plans->toJson() }}" :monthly_plans="{{ $subscription_plans->where('interval', 'month')->toJson() }}" :yearly_plans="{{ $subscription_plans->where('interval', 'year')->toJson() }}" inline-template>
                <div class="col-md-9 col-sm-9 nopadding">
                    @if($member->pendingupgrade == false)
                        @if(! $member->subscribed('cpd') || auth()->user()->hasRole('super') || $member->subscription('cpd')->plan->price == 0)
                            <div class="alert alert-warning">
                                <b>Note:</b> Note that this will also generate a new invoice for the member with the given subscription.
                                <br>
                                <br>
                                <strong>Plan effective from:</strong> {{ \Carbon\Carbon::now()->startOfMonth()->startOfDay()->toDateString() }}
                            </div>


                            <div class="col-md-12">
                                <div class="form-group"><label class="block">Please select one of the following options..</label>
                                    <div class="clip-radio radio-primary">
                                        <input v-model="selectedOption" type="radio" id="female" name="gender" value="1">
                                        <label for="female">Monthly Plans</label>
                                        <input v-model="selectedOption" type="radio" id="male" name="gender" value="2">
                                        <label for="male">Yearly Plans</label></div>
                                </div>
                            </div>

                            <hr>
                            <br>

                            <div v-show="selectedOption === '1'">
                                <div class="col-md-12">
                                    <form action="/admin/members/subscription/upgrade" method="POST">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="user_id" value="{{ $member->id }}">
                                        <div class="form-group">
                                            <label>Monthly Subscription Plans</label>
                                            <select class="form-control" name="plan_id" v-model="planId" v-on:change="planChange">
                                                <option selected value="">Please Select...</option>
                                                @if(count($subscription_plans))
                                                    <option v-for="plan in monthly_plans" value="@{{ plan.id }}">@{{ plan.interval | capitalize }}@{{"ly - "+plan.name+ " R"+plan.price }}</option>
                                                @endif
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Monthly Payment Method</label>
                                            <select class="form-control" name="payment_method" v-model="payment_method">
                                                <option selected value="">Please Select...</option>
                                                <option value="debit_order">Debit Order</option>
                                                <option value="credit_card">Credit Card</option>
                                            </select>
                                        </div>

                                        <div v-show="payment_method == 'debit_order'">
                                            <hr>
                                            @if($member->debit)
                                                <div class="alert alert-warning">
                                                    Please note that the first debit order will be processed tomorrow for this subscription.
                                                </div>
                                            @else
                                                <div class="alert alert-danger">
                                                    This client does not have any debit order details available and therefore cannot continue the upgrade
                                                    on selected payment method (debit order), please select an alternative payment method or setup the debit order details.
                                                </div>
                                            @endif
                                        </div>

                                        <hr>

                                        @if(env('APP_THEME') == 'taxfaculty')
                                        <div v-show="selectedPlan.is_custom" class="panel panel-default">
                                            @include('admin.members.includes.comprehensive_topics')
                                        </div>
                                        @else
                                        <div v-show="selectedPlan.is_custom" class="panel panel-default">
                                            @include('admin.members.includes.comprehensive_topics')
                                        </div>
                                        @endif
                                    

                                        <div v-if="payment_method == 'debit_order'">
                                            @if($member->debit)
                                                @if($member->debit->otp && $member->debit->peach)
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary">Upgrade Subscription</button>
                                                    </div>
                                                @else

                                                    <div class="alert alert-info">
                                                        In order to proceed with this upgrade, the client needs to verify his bank account details with an OTP Code.
                                                        <br>
                                                        <strong>How does the client verify his details ? (2 options available..)</strong>
                                                        <ul>
                                                            <li>
                                                                From the member profile.
                                                                <ul>
                                                                    <li>Click on my billing.</li>
                                                                    <li>Click on save banking details if there is any, if not please complete the form.</li>
                                                                    <li>Enter the OTP that was sent to the client to his mobile phone.</li>
                                                                </ul>
                                                            </li>
                                                            <li>
                                                                From the Admin Section.
                                                                <ul>
                                                                    <li>Click on Debit Orders.</li>
                                                                    <li>Search for his account.</li>
                                                                    <li>Click on Update</li>
                                                                    <li>select "Migrate To Peach" and choose <strong>YES</strong></li>
                                                                    <li>Click on <strong>Send OTP</strong></li>
                                                                    <li>Ask the client to provide you with the OTP Code, and enter the code in the field "OTP"</li>
                                                                    <li>Click on Update Details</li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary ">Upgrade Subscription</button>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary ">Upgrade Subscription</button>
                                                </div>
                                            @endif
                                        </div>

                                        <div v-else>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Upgrade Subscription</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div v-if="selectedOption === '2'">
                                <div class="col-md-12">
                                    <form action="/admin/members/subscription/upgrade" method="POST">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="user_id" value="{{ $member->id }}"></input>
                                        <div class="form-group">
                                            <label>Yearly Subscription Plans</label>
                                            <select class="form-control" name="plan_id" v-model="planId" v-on:change="planChange">
                                                <option selected value="">Please Select...</option>
                                                @if(count($subscription_plans))
                                                    <option v-for="plan in yearly_plans" value="@{{ plan.id }}">@{{ plan.interval | capitalize }}@{{"ly - "+plan.name+ " R"+plan.price }}</option>
                                                @endif
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Yearly Payment Method</label>
                                            <select class="form-control" name="payment_method" v-model="payment_method">
                                                <option selected value="">Please Select...</option>
                                                <option value="credit_card">Credit Card</option>
                                                <option value="eft">EFT</option>
                                            </select>
                                        </div>

                                        <div v-if="payment_method == 'debit_order'">
                                            <hr>
                                            @if($member->debit)
                                                <div class="alert alert-warning">
                                                    Please note that the first debit order will be processed tomorrow for this subscription.
                                                </div>
                                            @else
                                                <div class="alert alert-danger">
                                                    This client does not have any debit order details available and therefore cannot continue the upgrade
                                                    on selected payment method (debit order), please select an alternative payment method or setup the debit order details.
                                                </div>
                                            @endif
                                        </div>

                                        <hr>
                                        @if(env('APP_THEME') == 'taxfaculty')
                                        <div v-show="selectedPlan.is_custom" class="panel panel-default">
                                            @include('admin.members.includes.comprehensive_topics')
                                        </div>
                                        @else
                                        <div v-show="selectedPlan.is_custom" class="panel panel-default">
                                            @include('admin.members.includes.comprehensive_topics')
                                        </div>
                                        @endif

                                        <div v-if="payment_method == 'debit_order'">
                                            @if($member->debit)
                                                @if($member->debit->otp && $member->debit->peach)
                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary">Upgrade Subscription</button>
                                                    </div>
                                                @else

                                                    <div class="alert alert-info">
                                                        In order to proceed with this upgrade, the client needs to verify his bank account details with an OTP Code.
                                                        <br>
                                                        <strong>How does the client verify his details ? (2 options available..)</strong>
                                                        <ul>
                                                            <li>
                                                                From the member profile.
                                                                <ul>
                                                                    <li>Click on my billing.</li>
                                                                    <li>Click on save banking details if there is any, if not please complete the form.</li>
                                                                    <li>Enter the OTP that was sent to the client to his mobile phone.</li>
                                                                </ul>
                                                            </li>
                                                            <li>
                                                                From the Admin Section.
                                                                <ul>
                                                                    <li>Click on Debit Orders.</li>
                                                                    <li>Search for his account.</li>
                                                                    <li>Click on Update</li>
                                                                    <li>select "Migrate To Peach" and choose <strong>YES</strong></li>
                                                                    <li>Click on <strong>Send OTP</strong></li>
                                                                    <li>Ask the client to provide you with the OTP Code, and enter the code in the field "OTP"</li>
                                                                    <li>Click on Update Details</li>
                                                                </ul>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div class="form-group">
                                                        <button type="submit" class="btn btn-primary ">Upgrade Subscription</button>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary disabled">Upgrade Subscription</button>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <label>Black Friday Discount</label>
                                            <select class="form-control" name="bf_discount">
                                                <option selected value="">Please Select...</option>
                                                <option value="1">Apply Black Friday Discount</option>
                                                <option value="0">Do not Apply Black Friday Discount</option>
                                            </select>
                                        </div>

                                        <div v-else>
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">Upgrade Subscription</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        @else
                            <div class="alert alert-info">
                                <strong>Note </strong> In order for you to upgrade/downgrade this profile to a new plan, Please complete the following form. Once the upgrade/downgrade has been approved the account will be modified.
                            </div>

                            {!! Form::model($member,['method' => 'post', 'route' => 'upgrade_subscription.upgrade']) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group @if ($errors->has('first_name')) has-error @endif">
                                        {!! Form::label('first_name', 'First Name') !!}
                                        {!! Form::input('text', 'first_name', null, ['class' => 'form-control', 'disabled']) !!}
                                        {!! Form::hidden('first_name', null) !!}
                                        @if ($errors->has('first_name')) <p class="help-block">{{ $errors->first('first_name') }}</p> @endif
                                    </div>

                                    <div class="form-group @if ($errors->has('email')) has-error @endif">
                                        {!! Form::label('email', 'Email Address') !!}
                                        {!! Form::input('text', 'email', null, ['class' => 'form-control', 'disabled']) !!}
                                        {!! Form::hidden('email', null) !!}
                                        @if ($errors->has('email')) <p class="help-block">{{ $errors->first('email') }}</p> @endif
                                    </div>

                                    <div class="form-group @if ($errors->has('current_cpd_package')) has-error @endif">
                                        {!! Form::label('current_cpd_package', 'Current CPD Package') !!}
                                        {!! Form::hidden('current_cpd_package', $member->subscription('cpd')->plan->id) !!}
                                        <select name="current_cpd_package" id="current_cpd_package" class="form-control" disabled="disabled">
                                            <option value="{{ $member->subscription('cpd')->plan->id }}">{{ $member->subscription('cpd')->plan->name }}</option>
                                        </select>
                                        @if ($errors->has('current_cpd_package')) <p class="help-block">{{ $errors->first('current_cpd_package') }}</p> @endif
                                    </div>

                                    <div class="form-group">
                                        <label>Payment Method</label>
                                        <select class="form-control" name="payment_method" v-model="payment_method">
                                            <option selected value="">Please Select...</option>
                                            <option value="debit_order">Debit Order</option>
                                            <option value="credit_card">Credit Card</option>
                                            <option value="eft">EFT</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group @if ($errors->has('last_name')) has-error @endif">
                                        {!! Form::label('last_name', 'Last Name') !!}
                                        {!! Form::input('text', 'last_name', null, ['class' => 'form-control', 'disabled']) !!}
                                        {!! Form::hidden('last_name', null) !!}
                                        @if ($errors->has('last_name')) <p class="help-block">{{ $errors->first('last_name') }}</p> @endif
                                    </div>

                                    <div class="form-group @if ($errors->has('id_number')) has-error @endif">
                                        {!! Form::label('id_number', 'ID Number') !!}
                                        {!! Form::input('text', 'id_number', null, ['class' => 'form-control', 'disabled']) !!}
                                        {!! Form::hidden('id_number', null) !!}
                                        @if ($errors->has('id_number')) <p class="help-block">{{ $errors->first('id_number') }}</p> @endif
                                    </div>

                                    <div class="form-group">
                                        <label>New Subscription Plan</label>
                                        <select class="form-control" name="new_subscription_plan" v-model="planId" v-on:change="planChange">
                                            <option selected value="">Please Select...</option>
                                            @if(count($subscription_plans))
                                                <option v-for="plan in plans" value="@{{ plan.id }}">@{{ plan.interval | capitalize }}@{{"ly - "+plan.name+ " R"+plan.price }}</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Black Friday Discount</label>
                                        <select class="form-control" name="bf_discount">
                                            <option selected value="">Please Select...</option>
                                            <option value="1">Apply Black Friday Discount</option>
                                            <option value="0">Do not Apply Black Friday Discount</option>
                                        </select>
                                    </div>
                                </div>

                                <hr>

                                @if(env('APP_THEME') == 'taxfaculty')
                                <div v-show="selectedPlan.is_custom" class="panel panel-default">
                                    @include('admin.members.includes.comprehensive_topics')
                                </div>
                                @else
                                <div v-show="selectedPlan.is_custom" class="panel panel-default">
                                    @include('admin.members.includes.comprehensive_topics')
                                </div>
                                @endif
                            

                                <div class="col-md-12">
                                    <div class="form-group @if ($errors->has('reason')) has-error @endif">
                                        {!! Form::label('reason', 'Please provide your reason') !!}
                                        {!! Form::textarea('reason', null, ['class' => 'form-control ckeditor']) !!}
                                        @if ($errors->has('reason')) <p class="help-block">{{ $errors->first('reason') }}</p> @endif
                                    </div>
                                </div>
                            </div>

                            <div v-if="payment_method == 'debit_order'">
                                <hr>
                                @if($member->debit)
                                    <div class="alert alert-warning">
                                        Please note that the first debit order will be processed on the following day of admin approval.
                                    </div>
                                @else
                                    <div class="alert alert-danger">
                                        This client does not have any debit order details available and therefore cannot continue the upgrade
                                        on selected payment method (debit order), please select an alternative payment method or setup the debit order details.
                                    </div>
                                @endif
                            </div>

                            <div v-if="payment_method == 'debit_order'">
                                @if($member->debit)
                                    @if($member->debit->otp && $member->debit->peach)
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Upgrade Subscription</button>
                                        </div>
                                    @else

                                        <div class="alert alert-info">
                                            In order to proceed with this upgrade, the client needs to verify his bank account details with an OTP Code.
                                            <br>
                                            <strong>How does the client verify his details ? (2 options available..)</strong>
                                            <ul>
                                                <li>
                                                    From the member profile.
                                                    <ul>
                                                        <li>Click on my billing.</li>
                                                        <li>Click on save banking details if there is any, if not please complete the form.</li>
                                                        <li>Enter the OTP that was sent to the client to his mobile phone.</li>
                                                    </ul>
                                                </li>
                                                <li>
                                                    From the Admin Section.
                                                    <ul>
                                                        <li>Click on Debit Orders.</li>
                                                        <li>Search for his account.</li>
                                                        <li>Click on Update</li>
                                                        <li>select "Migrate To Peach" and choose <strong>YES</strong></li>
                                                        <li>Click on <strong>Send OTP</strong></li>
                                                        <li>Ask the client to provide you with the OTP Code, and enter the code in the field "OTP"</li>
                                                        <li>Click on Update Details</li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary ">Upgrade Subscription</button>
                                        </div>
                                    @endif
                                @else
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary disabled">Upgrade Subscription</button>
                                    </div>
                                @endif
                            </div>

                            <div v-else>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Upgrade Subscription</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        @endif
                    @else
                        <div class="alert alert-warning">
                            Unable to select a new upgrade due to previous upgrade / downgrade that are still in process. <br>
                            Please contact management for further assistance.
                        </div>
                    @endif
                </div>
            </app-upgrade-subscription>
        </div>
    </div>
@stop

@section('scripts')
    <script src="/assets/themes/saaa/js/app.js"></script>
    <script src="/assets/admin/assets/js/profile.js"></script>
    <script src="/assets/admin/vendor/bootstrap-fileinput/jasny-bootstrap.js"></script>
    <script src="/assets/admin/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="/assets/admin/assets/js/bootstrap-confirm-delete.js"></script>
    <script>
        jQuery(document).ready(function () {
            Profile.init();
        });
    </script>
    <script src="/assets/admin/assets/js/bootstrap-confirm-delete.js"></script>
    @include('admin.members.includes.spin')
@stop