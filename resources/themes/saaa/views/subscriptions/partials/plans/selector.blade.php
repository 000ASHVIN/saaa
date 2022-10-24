@if (@$profession->slug == 'business-accountant-in-practice-saiba')
    <div class="row brandlogo">
        <img alt="" src="{{ Theme::asset('img/saiba.png') }}" height="250" >
    </div>
@endif

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

@if (Route::getFacadeRoot()->current()->uri() == 'subscriptions/2020/BlackFriday' || Route::getFacadeRoot()->current()->uri() == 'BlackFriday'  || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2020/one-day-only' || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2020/One-Day-Only' || Route::getFacadeRoot()->current()->uri() == 'subscriptions/2022/One-Day-Only' )
    <!-- Default / Disabled Monthly Plans -->
    <div class="row" v-if="defaultPlans.length > 0 && shouldShowDefaultPlans">
        <div class="@{{ getPlanColumnWidth(defaultPlans.length) }}" v-for="plan in defaultPlans">
            @include('subscriptions.partials.plans.disabled_plans')
            @if(isset($selectedPlan) && auth()->user())
                <div v-if="plan.id == {{$selectedPlan->id}} && beforeSelected">
                    @{{ selectPlan(plan) }}
                </div>
            @endif
        </div>
    </div>

    <!-- Yearly Plans, Disabled Plans -->
    <div class="row" v-if="yearlyPlans.length > 0 && shouldShowYearlyPlans">
        <div class="@{{ getPlanColumnWidth(yearlyPlans.length) }}" v-for="plan in yearlyPlans">
            @include('subscriptions.partials.plans.disabled_plans')
            @if(isset($selectedPlan) && auth()->user())
                <div v-if="plan.id == {{$selectedPlan->id}} && beforeSelected">
                    @{{ selectPlan(plan) }}
                </div>
            @endif
        </div>
    </div>
@else
    <!-- Default / Monthly Plans -->
    <div class="row" v-if="defaultPlans.length > 0 && shouldShowDefaultPlans">
        <div class="@{{ getPlanColumnWidth(defaultPlans.length) }}" v-for="plan in defaultPlans">
            @include('subscriptions.partials.plans.plan')
            @if(isset($selectedPlan) && auth()->user())
                <div v-if="plan.id == {{$selectedPlan->id}} && beforeSelected">
                    @{{ selectPlan(plan) }}
                </div>
            @endif
        </div>
    </div>

    <!-- Yearly Plans, If Applicable -->
    <div class="row" v-if="yearlyPlans.length > 0 && shouldShowYearlyPlans">
        <div class="@{{ getPlanColumnWidth(yearlyPlans.length) }}" v-for="plan in yearlyPlans">
            @include('subscriptions.partials.plans.plan')
            @if(isset($selectedPlan) && auth()->user())
                <div v-if="plan.id == {{$selectedPlan->id}} && beforeSelected">
                    @{{ selectPlan(plan) }}
                </div>
            @endif
        </div>
    </div>
@endif