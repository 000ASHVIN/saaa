<div class="panel panel-default" v-if="forms.subscription.paymentOption == 'debit' && !waiting_on_otp">
    <div class="panel-heading">
        Debit Order details
    </div>
    <div class="panel-body">

        <div class="alert alert-info">
            <p>
                <strong>Please note</strong> that if you have existing debit order details listed on your account the following information will overwrite them.
            </p>
        </div>

        <form class="form-horizontal" role="form">
            <div class="form-group" :class="{'has-error': forms.subscription.errors.has('bank')}">
                <label for="bank" class="col-md-4 control-label">Bank</label>

                <div class="col-sm-6">
                    <select name="bank_temp" class="form-control" v-model="forms.subscription.debit.bank_temp" v-on:change='bankNameChanged()'>
                        @foreach (getBankBranches() as $code=>$name)
                            <option value="{{ $code }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" class="form-control" name="bank" id="bank" v-model="forms.subscription.debit.bank" value="">

                    <span class="help-block" v-show="forms.subscription.errors.has('bank')">
                        <strong>@{{ forms.subscription.errors.get('bank') }}</strong>
                    </span>
                </div>
            </div>
            <div class="form-group" :class="{'has-error': forms.subscription.errors.has('account_holder')}">
                <label for="account_holder" class="col-md-4 control-label">Account Holder</label>

                <div class="col-sm-6">
                    <input type="text" class="form-control" name="account_number" data-stripe="account_holder" v-model="forms.subscription.debit.account_holder">

                    <span class="help-block" v-show="forms.subscription.errors.has('account_holder')">
                        <strong>@{{ forms.subscription.errors.get('account_holder') }}</strong>
                    </span>
                </div>
            </div>

            <div class="form-group" :class="{'has-error': forms.subscription.errors.has('type_of_account')}">
                <label for="type_of_account" class="col-md-4 control-label">Account Type</label>

                <div class="col-sm-6">
                    <select name="type_of_account" class="form-control" name="type" v-model="forms.subscription.debit.type_of_account">
                        <option value="">Please Select...</option>
                        <option value="company">Company</option>
                        <option value="personal">Personal</option>
                    </select>

                    <span class="help-block" v-show="forms.subscription.errors.has('type_of_account')">
                        <strong>@{{ forms.subscription.errors.get('type_of_account') }}</strong>
                    </span>
                </div>
            </div>

            <div v-if="forms.subscription.debit.type_of_account == 'company'" class="form-group" :class="{'has-error': forms.subscription.errors.has('registration_number')}">
                <label for="registration_number" class="col-md-4 control-label">Company Registration Number</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="registration_number" data-stripe="registration_number" v-model="forms.subscription.debit.registration_number">
                    <span class="help-block" v-show="forms.subscription.errors.has('registration_number')">
                        <strong>@{{ forms.subscription.errors.get('registration_number') }}</strong>
                    </span>
                </div>
            </div>

            <div v-if="forms.subscription.debit.type_of_account == 'personal'" class="form-group" :class="{'has-error': forms.subscription.errors.has('id_number')}">
                <label for="id_number" class="col-md-4 control-label">ID Number</label>

                <div class="col-sm-6">
                    <input type="text" class="form-control" name="id_number" data-stripe="id_number" v-model="forms.subscription.debit.id_number">

                    <span class="help-block" v-show="forms.subscription.errors.has('id_number')">
                        <strong>@{{ forms.subscription.errors.get('id_number') }}</strong>
                    </span>
                </div>
            </div>

            <div class="form-group" :class="{'has-error': forms.subscription.errors.has('account_number')}">
                <label for="account_number" class="col-md-4 control-label">Account Number</label>

                <div class="col-sm-6">
                    <input type="text" class="form-control" name="account_number" data-stripe="account_number" v-model="forms.subscription.debit.account_number">

                    <span class="help-block" v-show="forms.subscription.errors.has('account_number')">
                        <strong>@{{ forms.subscription.errors.get('account_number') }}</strong>
                    </span>
                </div>
            </div>

            <div class="form-group" :class="{'has-error': forms.subscription.errors.has('account_type')}">
                <label for="account_type" class="col-md-4 control-label">Type of Account</label>

                <div class="col-sm-6">
                    <select name="account_type" class="form-control" name="type" v-model="forms.subscription.debit.account_type">
                        <option value="">Please Select...</option>
                        <option value="cheque">Cheque</option>
                        <option value="transmission">Transmission</option>
                        <option value="savings">Savings</option>
                        <option value="other">Other</option>
                    </select>

                    <span class="help-block" v-show="forms.subscription.errors.has('account_type')">
                        <strong>@{{ forms.subscription.errors.get('account_type') }}</strong>
                    </span>
                </div>
            </div>

            <div class="form-group" :class="{'has-error': forms.subscription.errors.has('branch_name')}" style="display:none;">
                <label for="branch_name" class="col-md-4 control-label">Branch Name</label>

                <div class="col-sm-6">
                    <input type="text" class="form-control" name="branch_name" data-stripe="branch_name" v-model="forms.subscription.debit.branch_name">

                    <span class="help-block" v-show="forms.subscription.errors.has('branch_name')">
                        <strong>@{{ forms.subscription.errors.get('branch_name') }}</strong>
                    </span>
                </div>
            </div>

            <div class="form-group" :class="{'has-error': forms.subscription.errors.has('branch_code')}">
                <label for="branch_code" class="col-md-4 control-label">Branch Code</label>

                <div class="col-sm-6">
                    <input type="text" name="branch_code" class="form-control" v-model="forms.subscription.debit.branch_code" value="" readonly="true">

                    <span class="help-block" v-show="forms.subscription.errors.has('branch_code')">
                        <strong>@{{ forms.subscription.errors.get('branch_code') }}</strong>
                    </span>
                </div>
            </div>

            <div class="form-group" :class="{'has-error': forms.subscription.errors.has('account_type')}">
                <label for="billable_date" class="col-md-4 control-label">Debit Date</label>

                <div class="col-sm-6">
                    <select name="billable_date" class="form-control" name="type" v-model="forms.subscription.debit.billable_date">
                        <option value="">Please Select...</option>
                        <option value="2">2</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="25">25</option>
                        <option value="28">28</option>
                    </select>

                    <span class="help-block" v-show="forms.subscription.errors.has('billable_date')">
                        <strong>@{{ forms.subscription.errors.get('account_type') }}</strong>
                    </span>
                </div>
            </div>

            <div class="alert alert-warning">
                <strong>NB*</strong>
                <p>Please note that your first debit order will be processed on {{ date_format(\Carbon\Carbon::now()->addDay(1), 'd F Y') }}.</p>
            </div>

            <div class="alert alert-info" v-if="forms.subscription.debit.billable_date">
                <p>Your next debit order will be processed on
                    <strong>
                        <span>@{{ forms.subscription.debit.billable_date }} </span>
                        {{ date_format(\Carbon\Carbon::now()->addMonth(1), 'F y') }}
                    </strong>
                </p>
            </div>
        </form>
    </div>
</div>

<div class="panel panel-default" v-if="forms.subscription.paymentOption == 'debit' && waiting_on_otp">
    <div class="panel-heading">
        One-Time-Pin (OTP)
    </div>
    <div class="panel-body">

        <div class="alert alert-info">
            <p>
                <strong>Please note</strong> We've just sent an SMS to your cellphone, please enter the OTP number below in order to complete your registration.
            </p>
        </div>

        <form class="form-horizontal" role="form">
            <div class="form-group" :class="{'has-error': forms.subscription.errors.has('otp')}">
                <label for="otp" class="col-md-4 control-label">OTP</label>

                <div class="col-sm-6">
                    <input type="text" class="form-control" name="otp" data-stripe="otp" v-model="forms.subscription.debit.otp">

                    <button @click.prevent="resendOtp" class="btn btn-primary btn-sm pull-right">Resend OTP</button>

                    <span class="help-block" v-show="forms.subscription.errors.has('otp')">
                        <strong>@{{ forms.subscription.errors.get('otp') }}</strong>
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>