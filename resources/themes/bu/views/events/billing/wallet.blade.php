<div class="panel panel-default" v-if="forms.eventSignup.paymentOption == 'wallet' && user.availableWallet != 0">

    <div class="panel-heading">
        <div class="pull-left">
            <span v-if="user.availableWallet >= total">Full Payment</span>
            <span v-else="user.availableWallet <= total">Partial Payment</span>
        </div>

        <div class="pull-right">
            Available Funds: <strong>@{{ user.availableWallet | currency 'R' }}</strong>
        </div>

        <div class="clearfix"></div>
    </div>

    <div class="panel-body">
        <app-error-alert :form="forms.eventSignup"></app-error-alert>
        <form class="form-horizontal" role="form" style="margin-bottom: 0px">
            <div class="form-group" style="margin-bottom: 0px">
                <div class="col-sm-12">
                    <p>
                        <div class="alert alert-success" v-if="user.availableWallet >= total">
                            Note that if you select this payment option the funds will be deducted from your wallet. All transactions for your wallet can be found under the My Wallet tab from your profile.
                        </div>
                        <div class="alert alert-warning" v-else="user.availableWallet <= total">
                            You only have a partial of the amount required to continue this purchase, You can use this option to allocate part of the funds to this invoice but you would need to make another payment from your profile for this invoice..
                        </div>
                    </p>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="panel panel-default" v-if="forms.eventSignup.paymentOption == 'wallet'">
    <div class="panel-body">
        <div class="form-group" :class="{'has-error': forms.eventSignup.errors.has('terms')}">
            <div class="col-sm-6 col-sm-offset-4">
                <span class="help-block" v-show="forms.eventSignup.errors.has('terms')">
                    <strong>@{{ forms.eventSignup.errors.get('terms') }}</strong>
                </span>
                <label class="checkbox nomargin">
                    <input type="checkbox" v-model="forms.eventSignup.terms">
                    <i></i>
                    I Accept The <a href="/terms_and_conditions" target="_blank">Terms Of Service</a>
                </label>
            </div>
        </div>
    </div>
</div>

<div class="row" v-if="forms.eventSignup.paymentOption == 'wallet'">
    <div class="col-md-12">
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block" @click.prevent="register"
                    :disabled="forms.eventSignup.busy">
                <span v-if="forms.eventSignup.busy">
                    <i class="fa fa-btn fa-spinner fa-spin"></i> Registering
                </span>

                <span v-else>
                    <i class="fa fa-btn fa-check-circle"></i>

                    <span>
                        Register for event
                    </span>
                </span>
            </button>
        </div>
    </div>
</div>