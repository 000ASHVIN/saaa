<div v-if="forms.eventSignup.venue && forms.eventSignup.venue.type != 'online'" class="panel panel-default">
    <div class="panel-heading">
        <div class="pull-left">
            Extras (Optional)
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="panel-body">

        {{-- <app-error-alert :form="forms.eventSignup"></app-error-alert> --}}

        <div>
            <label class="col-md-4 control-label">Select any of the following:</label>
            <div class="col-md-6">
                <label class="checkbox" v-for="extra in extras">
                    <input type="checkbox" value="@{{ extra }}" v-model="forms.eventSignup.extras">
                    <i></i> @{{ extra.name }} <span v-if="extra.price > 0"> - @{{ extra.price | currency 'R' }}</span>
                </label>

            </div>
        </div>
    </div>
</div>
