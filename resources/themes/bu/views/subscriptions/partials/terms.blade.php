<div class="panel panel-default">
    <div class="panel-body">
        <div class="form-group" :class="{'has-error': forms.subscription.errors.has('terms')}">
            <div class="col-sm-12">
                <span class="help-block" v-show="forms.subscription.errors.has('terms')">
                    <strong>@{{ forms.subscription.errors.get('terms') }}</strong>
                </span>
                <label class="checkbox nomargin">
                    <input type="checkbox" value="1" v-model="forms.subscription.terms">
                    <i></i>
                    I Accept The <a href="/terms_and_conditions" target="_blank">Terms Of Service</a>
                </label>
            </div>
        </div>
    </div>
</div>

<div class="row" v-if="forms.subscription.paymentOption != 'eft'">
    <div class="col-sm-12">
        <button type="submit" class="btn btn-primary btn-block" @click.prevent="register" :disabled="forms.subscription.busy">
            <span v-if="forms.subscription.busy">
                <i class="fa fa-btn fa-spinner fa-spin"></i> Registering
            </span>

            <span v-else>
                <i class="fa fa-btn fa-check-circle"></i>

                <span v-if=" ! selectedPlan.trialDays">
                    Complete Registration
                </span>

                <span v-else>
                    Begin @{{ selectedPlan.trialDays }} Day Trial
                </span>
            </span>
        </button>
    </div>
</div>

<div class="row" v-if="forms.subscription.paymentOption == 'eft' && instantlink">
    <div class="col-sm-12">

        <button
           class="btn btn-primary btn-block" 
           @click="popEft" 
           v-if="! forms.subscription.busy" 
           :disabled="! forms.subscription.terms" 
           :class="{ 'disabled': !forms.subscription.terms }">
            <span>
                <i class="fa fa-btn fa-check-circle"></i>
                <span>Continue to Payment</span>
            </span>
        </button>

        <button class="btn btn-danger btn-block" v-if="forms.subscription.busy" disabled>
            <i class="fa fa-btn fa-spinner fa-spin"></i> Waiting on Payment (Cancel Payment)
        </button>
    </div>
</div>
<br>