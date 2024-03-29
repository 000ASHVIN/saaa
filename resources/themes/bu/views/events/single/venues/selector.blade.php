
<div class="row app-plan-selector-row" v-if="venues.length > 0 && online_venues.length > 0">
    <div class="col-md-6 col-md-offset-3 text-center">
		<span class="app-plan-selector-interval">
			Attend in person &nbsp;
		</span>

        <input type="checkbox"
               id="plan-type-toggle"
               class="app-toggle app-toggle-round-flat"
               v-model="AttendTypeState">

        <label for="plan-type-toggle"></label>

		<span class="app-plan-selector-interval">
			&nbsp; View online
		</span>
    </div>
</div>

<div class="row" v-if="shouldShowPhysicalVenues">
    @include('events.single.venues.header')
        <tr style="height: 50px" v-for="venue in venues">
            @include('events.single.venues.venue')
        </tr>
    @include('events.single.venues.footer')
</div>

<div class="row" v-if="shouldShowOnlineVenues">
    @include('events.single.venues.header')
        <tr style="height: 50px" v-for="venue in online_venues">
            @include('events.single.venues.venue')
        </tr>
    @include('events.single.venues.footer')
</div>