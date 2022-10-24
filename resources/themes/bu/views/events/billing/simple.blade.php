<div class="panel panel-default">
    <div class="panel-heading">
        <div class="pull-left">
            Register
        </div>

        <div class="clearfix"></div>
    </div>

    <div class="panel-body">

        <app-error-alert :form="forms.eventSignup"></app-error-alert>

        <form class="form-horizontal" role="form">            

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

            <div class="form-group">
                <div class="col-sm-6 col-sm-offset-4">
                    <button type="submit" class="btn btn-primary" @click.prevent="register" :disabled="forms.eventSignup.busy">
                        <span v-if="forms.eventSignup.busy">
                            <i class="fa fa-btn fa-spinner fa-spin"></i> Registering
                        </span>

                        <span v-else>
                            <i class="fa fa-btn fa-check-circle"></i>

                            <span>
                                Register
                            </span>
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>