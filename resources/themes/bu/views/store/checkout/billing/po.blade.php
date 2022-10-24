<div class="panel panel-default" v-if="forms.checkout.paymentOption == 'po'">
    <div class="panel-body">
        <div class="form-group" :class="{'has-error': forms.checkout.errors.has('terms')}">
            <div class="col-sm-6 col-sm-offset-4">
                <span class="help-block" v-show="forms.checkout.errors.has('terms')">
                    <strong>@{{ forms.checkout.errors.get('terms') }}</strong>
                </span>
                <label class="checkbox nomargin">
                    <input type="checkbox" v-model="forms.checkout.terms">
                    <i></i>
                    I Accept The <a href="/terms_and_conditions" target="_blank">Terms Of Service</a>
                </label>
            </div>
        </div>
    </div>
</div>

<div class="row" v-if="forms.checkout.paymentOption == 'po'">
    <div class="col-md-12">
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block" @click.prevent="checkout" :disabled="forms.checkout.busy">
                <span v-if="forms.checkout.busy">
                    <i class="fa fa-btn fa-spinner fa-spin"></i> Registering
                </span>

                <span v-else>
                    <i class="fa fa-btn fa-check-circle"></i>

                    <span>
                        Complete Checkout
                    </span>
                </span>
            </button>
        </div>
    </div>
</div>