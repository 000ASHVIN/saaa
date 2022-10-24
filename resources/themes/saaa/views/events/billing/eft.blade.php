<div class="panel panel-default" v-if="forms.eventSignup.paymentOption == 'eft'">
    <div class="panel-body">
        <div class="form-group" :class="{'has-error': forms.eventSignup.errors.has('terms')}">
            <div class="col-sm-6 col-sm-offset-4">
                <span class="help-block" v-show="forms.eventSignup.errors.has('terms')">
                    <strong>@{{ forms.eventSignup.errors.get('terms') }}</strong>
                </span>
                <label class="checkbox nomargin">
                    <input type="checkbox" v-model="forms.eventSignup.terms">
                    <i></i>
                    I Accept The <a href="/terms_and_conditions" target="_blank">Terms Of Service</a> and <a href="/privacy_policy" target="_blank">Privacy Policy</a>
                </label>
            </div>
        </div>
    </div>
</div>

<div class="row"  v-if="forms.eventSignup.paymentOption == 'eft'">
    <div class="col-sm-12">

        <button type="submit"
           target="_blank" 
           class="btn btn-primary btn-block" 
           @click.prevent="popEft" 
           v-if="! forms.eventSignup.busy" 
           :disabled="! forms.eventSignup.terms" 
           :class="{ 'disabled': !forms.eventSignup.terms }">
            <span>
                <i class="fa fa-btn fa-check-circle"></i>
                <span>Continue to Payment</span>
            </span>
        </button>

        <button class="btn btn-danger btn-block" v-if="forms.eventSignup.busy" disabled>
            <i class="fa fa-btn fa-spinner fa-spin"></i> Waiting on Payment
        </button>
    </div>
</div>