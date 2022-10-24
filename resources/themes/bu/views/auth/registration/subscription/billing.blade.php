<div class="panel panel-default" v-if="forms.registration.paymentOption">
    <div class="panel-heading">
        <div class="pull-left">
            Billing Information
        </div>

        <!-- If On Single Plan Application -> Show Price On Billing Heading -->
        <div class="pull-right">
			<span v-if="plans.length == 1">
				(@{{ selectedPlanPrice }} / @{{ selectedPlan.interval | capitalize }})
			</span>
        </div>

        <div class="clearfix"></div>
    </div>

    <div class="panel-body">

        <form action="@{{ threeDsInfo.ACSUrl }}" target="threeDFrame" method="POST" id="threeDsForm" v-show="forms.registration.card.enrolled">
            <input type="hidden" v-model="threeDsInfo.PAReqMsg" name="PaReq">
            <input type="hidden" value="{{ route('home') }}/payment/afterCheckThreeDs" name="TermUrl">
            <input type="hidden" v-model="threeDsInfo.TransactionIndex" name="MD">
        </form>

        <app-error-alert :form="forms.registration.card"></app-error-alert>

        <form class="form-horizontal" role="form" v-if="forms.registration.paymentOption == 'cc'">            
            <div class="form-group" :class="{'has-error': forms.registration.errors.has('number')}">
                <label for="number" class="col-md-4 control-label">Card Number</label>

                <div class="col-sm-6">
                    <input type="text" class="form-control" name="number" data-stripe="number" v-model="forms.registration.card.number">

                    <span class="help-block" v-show="forms.registration.errors.has('number')">
                        <strong>@{{ forms.registration.errors.get('number') }}</strong>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label for="number" class="col-md-4 control-label">Security Code</label>

                <div class="col-sm-6">
                    <input type="text" class="form-control" name="cvc" data-stripe="cvc" v-model="forms.registration.card.cvc">
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-4 control-label">Expiration</label>

                <div class="col-md-3">
                    <input type="text" class="form-control" name="month" placeholder="MM" maxlength="2" v-model="forms.registration.card.month">
                </div>

                <div class="col-md-3">
                    <input type="text" class="form-control" name="year" placeholder="YY" maxlength="2" v-model="forms.registration.card.year">
                </div>
            </div>

            <div class="form-group" :class="{'has-error': forms.registration.errors.has('terms')}">
                <div class="col-sm-6 col-sm-offset-4">
                    <span class="help-block" v-show="forms.registration.errors.has('terms')">
                        <strong>@{{ forms.registration.errors.get('terms') }}</strong>
                    </span>
                    <label class="checkbox nomargin">
                        <input type="checkbox" v-model="forms.registration.terms">
                        <i></i>
                        I Accept The <a href="/terms_and_conditions" target="_blank">Terms Of Service</a>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-6 col-sm-offset-4">
                    <button type="submit" class="btn btn-primary" @click.prevent="register" :disabled="forms.registration.busy">
						<span v-if="forms.registration.busy">
							<i class="fa fa-btn fa-spinner fa-spin"></i> Registering
						</span>

						<span v-else>
							<i class="fa fa-btn fa-check-circle"></i>

							<span v-if=" ! selectedPlan.trialDays">
								Register
							</span>

							<span v-else>
								Begin @{{ selectedPlan.trialDays }} Day Trial
							</span>
						</span>
                    </button>
                </div>
            </div>
        </form>

        <form class="form-horizontal" role="form" v-if="forms.registration.paymentOption == 'debit'">

            <div class="form-group" :class="{'has-error': forms.registration.errors.has('bank')}">
                <label for="bank" class="col-md-4 control-label">Bank</label>

                <div class="col-sm-6">
                    <input type="text" class="form-control" name="bank" data-stripe="bank" v-model="forms.registration.debit.bank">

                    <span class="help-block" v-show="forms.registration.errors.has('bank')">
                        <strong>@{{ forms.registration.errors.get('bank') }}</strong>
                    </span>
                </div>
            </div>

            <div class="form-group" :class="{'has-error': forms.registration.errors.has('account_number')}">
                <label for="account_number" class="col-md-4 control-label">Account Number</label>

                <div class="col-sm-6">
                    <input type="text" class="form-control" name="account_number" data-stripe="account_number" v-model="forms.registration.debit.account_number">

                    <span class="help-block" v-show="forms.registration.errors.has('account_number')">
                        <strong>@{{ forms.registration.errors.get('account_number') }}</strong>
                    </span>
                </div>
            </div>

            <div class="form-group" :class="{'has-error': forms.registration.errors.has('account_type')}">
                <label for="account_type" class="col-md-4 control-label">Type of Account</label>

                <div class="col-sm-6">
                    <select name="account_type" class="form-control" name="type" v-model="forms.registration.debit.account_type">
                        <option value="">Please Select...</option>
                        <option value="cheque">Cheque</option>
                        <option value="transmission">Transmission</option>
                        <option value="savings">Savings</option>
                        <option value="other">Other</option>
                    </select>

                    <span class="help-block" v-show="forms.registration.errors.has('account_type')">
                        <strong>@{{ forms.registration.errors.get('account_type') }}</strong>
                    </span>
                </div>
            </div>

            <div class="form-group" :class="{'has-error': forms.registration.errors.has('branch_name')}">
                <label for="branch_name" class="col-md-4 control-label">Branch Name</label>

                <div class="col-sm-6">
                    <input type="text" class="form-control" name="branch_name" data-stripe="branch_name" v-model="forms.registration.debit.branch_name">

                    <span class="help-block" v-show="forms.registration.errors.has('branch_name')">
                        <strong>@{{ forms.registration.errors.get('branch_name') }}</strong>
                    </span>
                </div>
            </div>

            <div class="form-group" :class="{'has-error': forms.registration.errors.has('branch_code')}">
                <label for="branch_code" class="col-md-4 control-label">Branch Code</label>

                <div class="col-sm-6">
                    <input type="text" class="form-control" name="branch_code" data-stripe="branch_code" v-model="forms.registration.debit.branch_code">

                    <span class="help-block" v-show="forms.registration.errors.has('branch_code')">
                        <strong>@{{ forms.registration.errors.get('branch_code') }}</strong>
                    </span>
                </div>
            </div>

            <div class="form-group" :class="{'has-error': forms.registration.errors.has('account_type')}">
                <label for="billable_date" class="col-md-4 control-label">Debit Date</label>

                <div class="col-sm-6">
                    <select name="billable_date" class="form-control" name="type" v-model="forms.registration.debit.billable_date">
                        <option value="">Please Select...</option>
                        <option value="1">1</option>
                        <option value="15">15</option>
                        <option value="20">20</option>
                        <option value="25">25</option>
                        <option value="30">30</option>
                    </select>

                    <span class="help-block" v-show="forms.registration.errors.has('billable_date')">
                        <strong>@{{ forms.registration.errors.get('account_type') }}</strong>
                    </span>
                </div>
            </div>

            <div class="form-group" :class="{'has-error': forms.registration.errors.has('terms')}">
                <div class="col-sm-6 col-sm-offset-4">
                    <span class="help-block" v-show="forms.registration.errors.has('terms')">
                        <strong>@{{ forms.registration.errors.get('terms') }}</strong>
                    </span>
                    <label class="checkbox nomargin">
                        <input type="checkbox" v-model="forms.registration.terms">
                        <i></i>
                        I Accept The <a href="/terms_and_conditions" target="_blank">Terms Of Service</a>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-6 col-sm-offset-4">
                    <button type="submit" class="btn btn-primary" @click.prevent="register" :disabled="forms.registration.busy">
                        <span v-if="forms.registration.busy">
                            <i class="fa fa-btn fa-spinner fa-spin"></i> Registering
                        </span>

                        <span v-else>
                            <i class="fa fa-btn fa-check-circle"></i>

                            <span v-if=" ! selectedPlan.trialDays">
                                Register
                            </span>

                            <span v-else>
                                Begin @{{ selectedPlan.trialDays }} Day Trial
                            </span>
                        </span>
                    </button>
                </div>
            </div>
        </form>

        <form class="form-horizontal" role="form" v-if="forms.registration.paymentOption == 'eft'">
            
            <div class="form-group">
            <div class="col-sm-12">
                <p>
                    You will be able to find our banking details on the invoice you received after completing the signup proccess, once payment has been received you will be able to enjoy the benefits of this plan.
                </p>
            </div>                
            </div>

            <div class="form-group" :class="{'has-error': forms.registration.errors.has('terms')}">
                <div class="col-sm-6 col-sm-offset-4">
                    <span class="help-block" v-show="forms.registration.errors.has('terms')">
                        <strong>@{{ forms.registration.errors.get('terms') }}</strong>
                    </span>
                    <label class="checkbox nomargin">
                        <input type="checkbox" v-model="forms.registration.terms">
                        <i></i>
                        I Accept The <a href="/terms_and_conditions" target="_blank">Terms Of Service</a>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-6 col-sm-offset-4">
                    <button type="submit" class="btn btn-primary" @click.prevent="register" :disabled="forms.registration.busy">
                        <span v-if="forms.registration.busy">
                            <i class="fa fa-btn fa-spinner fa-spin"></i> Registering
                        </span>

                        <span v-else>
                            <i class="fa fa-btn fa-check-circle"></i>

                            <span v-if=" ! selectedPlan.trialDays">
                                Register
                            </span>

                            <span v-else>
                                Begin @{{ selectedPlan.trialDays }} Day Trial
                            </span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
