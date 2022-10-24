<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			1. Basic Information
		</h4>
	</div>
	<div class="panel-body">
		<app-error-alert :form="forms.basic"></app-error-alert>

		<form class="form-horizontal">
			<app-text :display="'First Name'"
                :form="forms.basic"
                :name="'first_name'"
                :input.sync="forms.basic.first_name">
            </app-text>
            <app-text :display="'Last Name'"
                      :form="forms.basic"
                      :name="'last_name'"
                      :input.sync="forms.basic.last_name">
            </app-text>
            <app-email :display="'E-Mail Address'"
                         :form="forms.basic"
                         :name="'email'"
                         :input.sync="forms.basic.email">
            </app-email>

            <div class="form-group" :class="{'has-error': forms.basic.errors.has('terms')}">
                <div class="col-sm-6 col-sm-offset-4">
                    <span class="help-block" v-show="forms.basic.errors.has('terms')">
                        <strong>@{{ forms.basic.errors.get('terms') }}</strong>
                    </span>
                    <label class="checkbox nomargin">
                        <input type="checkbox" v-model="forms.basic.terms">
                        <i></i>
                        I Accept The <a href="/terms_and_conditions" target="_blank">Terms Of Service</a>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <button type="submit" class="btn btn-primary" @click.prevent="signup" :disabled="forms.basic.busy">
                        <span v-if="forms.basic.busy">
                            <i class="fa fa-btn fa-spinner fa-spin"></i> Saving...
                        </span>

                        <span v-else>
                            <i class="fa fa-btn fa-check-circle"></i> Save & Continue
                        </span>
                    </button>
                </div>
            </div>
		</form>					
	</div>
</div>