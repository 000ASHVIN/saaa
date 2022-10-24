<div class="panel panel-default">
	<div class="panel-heading">
		<h4 class="panel-title">
			4. Insurance Declaration
		</h4>
	</div>
	<div class="panel-body">
		<app-error-alert :form="forms.declaration"></app-error-alert>

		<form class="form-horizontal">

            <h4 class="text-center">Legal Entity:</h4>
            
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="form-group">
                        <label>In the event that you are practicing under the name of a legal entity, kindly provide the name of such entity? </label>
                        <input type="text" class="form-control" v-model="forms.declaration.legal_entity">
                    </div>
                </div>
            </div>

            <hr>

            <h4 class="text-center">Jurisdiction</h4>

            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="form-group">
                        <label>Do you practice outside of South Africa? If yes, please list countries</label>
                        <textarea class="form-control" v-model="forms.declaration.practice_abroad"></textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="form-group">
                        <label>Do you do any work for your clients in the U.S.A , Canada or any other countries/states governed by their laws?</label>
                        <div class="form-group-inline">
                            <label class="radio-inline">
                                <input type="radio" name="work_outside" value="Yes" v-model="forms.declaration.work_abroad">
                                Yes
                            </label>

                            <label class="radio-inline">
                                <input type="radio" name="work_outside" value="No" v-model="forms.declaration.work_abroad">
                                No
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <h4 class="text-center">Claims</h4>

            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="form-group">
                        <label>Have any claims/incidents/circumstances for professional negligence already been made against you during the past 5 years?</label>
                        <div class="form-group-inline">
                            <label class="radio-inline">
                                <input type="radio" name="negligence" value="Yes" v-model="forms.declaration.negligence">
                                Yes
                            </label>

                            <label class="radio-inline">
                                <input type="radio" name="negligence" value="No" v-model="forms.declaration.negligence">
                                No
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="form-group">
                        <label>Are you aware of any claims/incidents/circumstances which may give rise to a claim now or in future against you?</label>
                        <div class="form-group-inline">
                            <label class="radio-inline">
                                <input type="radio" name="aware" value="Yes" v-model="forms.declaration.aware">
                                Yes
                            </label>

                            <label class="radio-inline">
                                <input type="radio" name="aware" value="No" v-model="forms.declaration.aware">
                                No
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" v-if="forms.declaration.aware == 'Yes' || forms.declaration.negligence == 'Yes'">
                <div class="col-md-10 col-md-offset-1">
                    <div class="form-group">
                        <label>If the answer to either question is “YES” please give full</label>
                        <textarea class="form-control" v-model="forms.declaration.aware_description"></textarea>
                    </div>
                </div>
            </div>

            <hr>

            <h4 class="text-center">Insurance history</h4>

            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="form-group">
                        <label>Has any insurer ever declined cover, imposed special terms or cancelled your insurance policy?</label>
                        <div class="form-group-inline">
                            <label class="radio-inline">
                                <input type="radio" name="work_outside" value="Yes" v-model="forms.declaration.declined">
                                Yes
                            </label>

                            <label class="radio-inline">
                                <input type="radio" name="work_outside" value="No" v-model="forms.declaration.declined">
                                No
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" v-if="forms.declaration.declined == 'Yes'">
                <div class="col-md-10 col-md-offset-1">
                    <div class="form-group">
                        <label>If the answer is “YES” please give full details</label>
                        <textarea class="form-control" v-model="forms.declaration.declined_reason"></textarea>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <button type="submit" class="btn btn-primary" @click.prevent="completeApplication()" :disabled="forms.declaration.busy">
                        <span v-if="forms.declaration.busy">
                            <i class="fa fa-btn fa-spinner fa-spin"></i> Saving...
                        </span>

                        <span v-else>
                            <i class="fa fa-btn fa-check-circle"></i> Save & Submit
                        </span>
                    </button>
                </div>
            </div>
		</form>					
	</div>
</div>