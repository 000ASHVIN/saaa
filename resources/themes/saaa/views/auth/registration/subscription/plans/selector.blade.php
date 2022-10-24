<!-- Registration Plan Selection -->
<div class="row app-plan-selector-row" v-if="monthlyPlans.length > 0 && yearlyPlans.length > 0">
    <div class="col-md-6 col-md-offset-3 text-center">
		<span class="app-plan-selector-interval">
			Monthly Plans &nbsp;
		</span>

        <input type="checkbox"
               id="plan-type-toggle"
               class="app-toggle app-toggle-round-flat"
               v-model="planTypeState">

        <label for="plan-type-toggle"></label>

		<span class="app-plan-selector-interval">
			&nbsp; Yearly Plans
		</span>
    </div>
</div>

<!-- Default / Monthly Plans -->
<div class="row" v-if="defaultPlans.length > 0 && shouldShowDefaultPlans">
    <div v-for="plan in defaultPlans">
        @include('auth.registration.subscription.plans.plan')
    </div>
</div>

<!-- Yearly Plans, If Applicable -->
<div class="row" v-if="yearlyPlans.length > 0 && shouldShowYearlyPlans">
    <div class="@{{ getPlanColumnWidth(yearlyPlans.length) }}" v-for="plan in yearlyPlans">
        @include('auth.registration.subscription.plans.plan')
    </div>
</div>

<br>
<br>

<div class="row">
  <div class="col-md-12 text-center">
    <a href="/auth/register?free=true" >
      Register for a free non-cpd subscriber account instead ?
    </a>
  </div>
</div>