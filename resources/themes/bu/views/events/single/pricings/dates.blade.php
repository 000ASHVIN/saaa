<div class="panel panel-default">
    <div class="panel-heading">Available Dates</div>
    <div class="panel-body">

        <div v-if="forms.eventSignup.venue.dates.length >= 1">
            
            <label class="checkbox" v-for="date in forms.eventSignup.venue.dates">
                <input v-on:change="dateChanged" v-bind:disabled="forms.eventSignup.pricingOption && forms.eventSignup.venue.dates.length === forms.eventSignup.pricingOption.day_count" type="checkbox" value="@{{ date }}" v-model="forms.eventSignup.dates">
                <i></i> @{{ date.date }}
            </label>
        </div>

        <div class="clearfix"></div>
    </div>
</div>