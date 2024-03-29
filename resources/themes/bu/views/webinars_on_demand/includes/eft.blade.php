<div class="panel panel-default" v-if="forms.webinarCheckout.paymentOption == 'eft'">
    <div class="panel-body">
        <div class="form-group" :class="{'has-error': forms.webinarCheckout.errors.has('terms')}">
            <div class="col-sm-12">
                <span class="help-block" v-show="forms.webinarCheckout.errors.has('terms')">
                    <strong>@{{ forms.webinarCheckout.errors.get('terms') }}</strong>
                </span>
                <label class="checkbox nomargin">
                    <input type="checkbox" v-model="forms.webinarCheckout.terms">
                    <i></i>
                    I Accept The <a href="/terms_and_conditions" target="_blank">Terms Of Service</a>
                </label>
            </div>
        </div>
    </div>
</div>

<div class="row"  v-if="forms.webinarCheckout.paymentOption == 'eft'">
    <div class="col-sm-12">

        <button type="submit"
           target="_blank" 
           class="btn btn-primary btn-block" 
           @click.prevent="popEft" 
           v-if="! forms.webinarCheckout.busy"
           :disabled="! forms.webinarCheckout.terms" 
           :class="{ 'disabled': !forms.webinarCheckout.terms }">
            <span>
                <i class="fa fa-btn fa-check-circle"></i>
                <span>Continue to Payment</span>
            </span>
        </button>

        <button class="btn btn-danger btn-block" v-if="forms.webinarCheckout.busy" disabled>
            <i class="fa fa-btn fa-spinner fa-spin"></i> Waiting on Payment
        </button>
    </div>
</div>