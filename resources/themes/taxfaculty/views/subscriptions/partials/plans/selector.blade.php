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

@if (Route::getFacadeRoot()->current()->uri() == 'subscriptions/2020/BlackFriday' || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2021/BlackFriday' || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2020/One-Day-Only' || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2022/One-Day-Only' || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2020/one-day-only' || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2019/One-Day-Only'  || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2021/One-Day-Only'  || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2022/One-Day-Only'  || Route::getFacadeRoot()->current()->uri() == 'BlackFriday' || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2021/cpd-catch-up' )
    <!-- Default / Disabled Monthly Plans -->
    <div class="row" v-if="defaultPlans.length > 0 && shouldShowDefaultPlans">
        <div class="@{{ getPlanColumnWidth(defaultPlans.length) }}" v-for="plan in defaultPlans">
            @include('subscriptions.partials.plans.disabled_plans')
        </div>
    </div>

    <!-- Yearly Plans, Disabled Plans -->
    <div class="row" v-if="yearlyPlans.length > 0 && shouldShowYearlyPlans">
        <div class="@{{ getPlanColumnWidth(yearlyPlans.length) }}" v-for="plan in yearlyPlans">
            @include('subscriptions.partials.plans.disabled_plans')
        </div>
    </div>
@else
    <!-- Default / Monthly Plans -->
    <div class="row" v-if="defaultPlans.length > 0 && shouldShowDefaultPlans">
        <div class="@{{ getPlanColumnWidth(defaultPlans.length) }}" v-for="plan in defaultPlans">
            @include('subscriptions.partials.plans.plan')
        </div>
    </div>

    <!-- Yearly Plans, If Applicable -->
    <div class="row" v-if="yearlyPlans.length > 0 && shouldShowYearlyPlans">
        <div class="@{{ getPlanColumnWidth(yearlyPlans.length) }}" v-for="plan in yearlyPlans">
            @include('subscriptions.partials.plans.plan')
        </div>
    </div>
@endif