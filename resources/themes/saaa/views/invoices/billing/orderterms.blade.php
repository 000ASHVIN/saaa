<div class="panel">
    <div class="panel-body">
        <div class="form-group" :class="{'has-error': forms.pay.errors.has('terms')}">
            <div class="col-sm-12">
                <span class="help-block" v-show="forms.pay.errors.has('terms')">
                    <strong>@{{ forms.pay.errors.get('terms') }}</strong>
                </span>
                <label class="checkbox nomargin">
                    <input type="checkbox" value="1" v-model="forms.pay.terms">
                    <i></i>
                    I Accept The <a href="/terms_and_conditions" target="_blank">Terms Of Service</a> and <a href="/privacy_policy" target="_blank">Privacy Policy</a>
                </label>
            </div>
        </div>
    </div>
</div>

<div class="row" v-if="forms.pay.paymentOption == 'cc'">
    <div class="col-sm-12">
        <button type="submit" class="btn btn-primary btn-block" @click.prevent="pay()" :disabled="forms.pay.busy">
            <span v-if="forms.pay.busy">
                <i class="fa fa-btn fa-spinner fa-spin"></i> Processing...
            </span>

            <span v-else>
                <i class="fa fa-btn fa-check-circle"></i>
                <span>
                    Settle Order
                </span>
            </span>
        </button>
    </div>
</div>

<div class="row"  v-if="forms.pay.paymentOption == 'eft'">
    <div class="col-sm-12">

        <button type="submit"
           target="_blank"
           class="btn btn-primary btn-block"
           @click.prevent="popEft"
           v-if="! forms.pay.busy"
           :disabled="! forms.pay.terms"
           :class="{ 'disabled': !forms.pay.terms }">
            <span>
                <i class="fa fa-btn fa-check-circle"></i>
                <span>Continue to Payment</span>
            </span>
        </button>

        <button class="btn btn-danger btn-block" v-if="forms.pay.busy" disabled>
            <i class="fa fa-btn fa-spinner fa-spin"></i> Waiting on Payment
        </button>
    </div>
</div>