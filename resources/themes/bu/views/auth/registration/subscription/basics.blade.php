<div class="panel panel-default">
    <div class="panel-heading">The Basics</div>
    <div class="panel-body">
        <app-error-alert :form="forms.registration"></app-error-alert>

        <form class="form-horizontal" role="form" id="subscription-basics-form">
            <app-text :display="'First Name'"
                        :form="forms.registration"
                        :name="'first_name'"
                        :input.sync="forms.registration.first_name">
            </app-text>

            <app-text :display="'Last Name'"
                      :form="forms.registration"
                      :name="'last_name'"
                      :input.sync="forms.registration.last_name">
            </app-text>

            <app-text :display="'ID Number'"
                      :form="forms.registration"
                      :name="'id_number'"
                      :input.sync="forms.registration.id_number">
            </app-text>


            <app-email :display="'E-Mail Address'"
                         :form="forms.registration"
                         :name="'email'"
                         :input.sync="forms.registration.email">
            </app-email>

            <app-text :display="'Cell Number'"
                      :form="forms.registration"
                      :name="'cell'"
                      :input.sync="forms.registration.cell">
            </app-text>

            <app-password :display="'Password'"
                            :form="forms.registration"
                            :name="'password'"
                            :input.sync="forms.registration.password">
            </app-password>

            <app-password :display="'Confirm Password'"
                            :form="forms.registration"
                            :name="'password_confirmation'"
                            :input.sync="forms.registration.password_confirmation">
            </app-password>

            <div v-if="freePlanIsSelected">
                <div class="form-group" :class="{'has-error': forms.registration.errors.has('terms')}">
                    <div class="col-sm-6 col-sm-offset-4">
                        <span class="help-block" v-show="forms.registration.errors.has('terms')">
                            <strong>@{{ forms.registration.errors.get('terms') }}</strong>
                        </span>
                        <label class="checkbox nomargin">
                            <input type="checkbox" v-model="forms.registration.terms">
                            <i></i>
                            I Accept The <a href="/terms_and_conditions" target="_blank">Terms Of Service</a>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-4">
                        <button type="submit" class="btn btn-primary" @click.prevent="register" :disabled="forms.registration.busy">
                            <span v-if="forms.registration.busy">
                                <i class="fa fa-btn fa-spinner fa-spin"></i> Registering
                            </span>

                            <span v-else>
                                <i class="fa fa-btn fa-check-circle"></i> Register
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
