<div class="div" v-if="selectedPlan.is_custom">
    <div class="alert alert-info">
        <p>
            The ESSENTIAL PLUS programme for Build your own is a webinar only package. Please choose @{{ getMaxFeatures!=0? 'only' :'' }} @{{ getMaxFeatures!=0? getMaxFeatures :'any' }} of the
            topics listed below. Webinars can be watched live at a pre-scheduled time and the recording will be
            available to re-watch via your online profile. The presenter will be available for Q & A during these
            webinars.
        </p>
    </div>

    <div class="callout callout-theme-color">
        <div class="row">
            <div class="col-md-9">
                <h3>Select Plan Topics</h3>
                <p>Please select your topics, you have selected <strong>(@{{ countSelectedFeatures }}@{{ getMaxFeatures!=0?'/':'' }}@{{ getMaxFeatures!=0?getMaxFeatures:'' }})</strong></p>
            </div>

            <div class="col-md-3 text-right">
                <button class="btn btn-primary btn-lg" @click.prevent="clearSelectedFeatures()">Clear Selected</button>
            </div>
        </div>
    </div>
    <br>

    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group" v-for="feature in selectedPlan.features">
                <label class="checkbox">
                    <input type="checkbox" value="@{{ feature.slug }}" v-model="selectedFeatures"
                           :disabled="! canSelectMoreFeatures" style="text-transform: capitalize"> @{{ feature.name | capitalize }}<i></i>
                </label>
            </div>
        </div>
    </div>
</div>