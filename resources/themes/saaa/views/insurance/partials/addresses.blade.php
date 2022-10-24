<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			2. Address Information
		</h4>
	</div>
	<div class="panel-body">
		<app-error-alert :form="forms.address"></app-error-alert>

		<form class="form-horizontal">
            
            <div class="row">
                <div class="col-md-6 text-center">
                    <h4 class="title">Business Address</h4>
                    <app-text :display="'Line One'"
                        :form="forms.address"
                        :name="'business.line_one'"
                        :input.sync="forms.address.business.line_one">
                    </app-text>
                    <app-text :display="'Line Two'"
                        :form="forms.address"
                        :name="'business.line_two'"
                        :input.sync="forms.address.business.line_two">
                    </app-text>
                    <app-text :display="'City'"
                        :form="forms.address"
                        :name="'business.city'"
                        :input.sync="forms.address.business.city">
                    </app-text>
                    <app-text :display="'Area Code'"
                        :form="forms.address"
                        :name="'business.code'"
                        :input.sync="forms.address.business.code">
                    </app-text>
                    <app-text :display="'Province'"
                        :form="forms.address"
                        :name="'business.province'"
                        :input.sync="forms.address.business.province">
                    </app-text>
                </div>
                <div class="col-md-6 text-center">
                    <h4 class="title">Postal Address</h4>
                    <app-text :display="'Line One'"
                        :form="forms.address"
                        :name="'postal.line_one'"
                        :input.sync="forms.address.postal.line_one">
                    </app-text>
                    <app-text :display="'Line Two'"
                        :form="forms.address"
                        :name="'postal.line_two'"
                        :input.sync="forms.address.postal.line_two">
                    </app-text>
                    <app-text :display="'City'"
                        :form="forms.address"
                        :name="'postal.city'"
                        :input.sync="forms.address.postal.city">
                    </app-text>
                    <app-text :display="'Area Code'"
                        :form="forms.address"
                        :name="'postal.code'"
                        :input.sync="forms.address.postal.code">
                    </app-text>
                    <app-text :display="'Province'"
                        :form="forms.address"
                        :name="'postal.province'"
                        :input.sync="forms.address.postal.province">
                    </app-text>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <button type="submit" class="btn btn-primary" @click.prevent="submitAddress" :disabled="forms.address.busy">
                        <span v-if="forms.address.busy">
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