<div class="panel panel-default">
    <div class="panel-heading">
        <div class="pull-left">
            Register
        </div>

        <div class="clearfix"></div>
    </div>

    <div class="panel-body">

        <app-error-alert :form="forms.checkout"></app-error-alert>

        <form class="form-horizontal" role="form">            

            <div class="form-group" :class="{'has-error': forms.checkout.errors.has('terms')}">
                <div class="col-sm-6 col-sm-offset-4">
                    <span class="help-block" v-show="forms.checkout.errors.has('terms')">
                        <strong>@{{ forms.checkout.errors.get('terms') }}</strong>
                    </span>
                    <label class="checkbox nomargin">
                        <input type="checkbox" v-model="forms.checkout.terms">
                        <i></i>
                        I Accept The <a href="/terms_and_conditions" target="_blank">Terms Of Service</a> and <a href="/privacy_policy" target="_blank">Privacy Policy</a>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-6 col-sm-offset-4">
                    <button type="submit" class="btn btn-primary" @click.prevent="checkout" :disabled="forms.checkout.busy">
                        <span v-if="forms.checkout.busy">
                            <i class="fa fa-btn fa-spinner fa-spin"></i> Processing payment
                        </span>

                        <span v-else>
                            <i class="fa fa-btn fa-check-circle"></i>

                            <span>
                                Complete order
                            </span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>