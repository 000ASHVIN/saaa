<!-- Registration -> Individual Plan Display Block -->

<div class="col-md-5th col-sm-5th app-plan app-plan-@{{ plan.id }}">
    <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);">
        <h4 style="font-size: 30px!important;">
            <sup>R</sup>@{{ plan.price }}<em>/@{{ plan.interval + 'ly' }}</em>
        </h4>
        <h5 style="text-transform: uppercase;
            font-weight: 500;
            margin: 0;
            font-size: 14px;
            color: #547698;
            letter-spacing: 2px;"
        >@{{ plan.name }}</h5>
        <hr />
        <p>@{{ plan.description }}</p>
        <hr />

        <button class="btn btn-primary app-plan-subscribe-button"  @click.prevent="selectPlan(plan)">
				<span v-if=" ! plan.trialDays && plan.price == 0">
					Register
				</span>

            <span v-if=" ! plan.trialDays && plan.price > 0">
					Subscribe
				</span>

            <span v-if="plan.trialDays">
					Begin @{{ plan.trialDays }} Day Trial
				</span>
        </button>

        {{--<a href="#" class="btn btn-3d btn-teal">Learn More</a>--}}
    </div>
</div>

    {{--<div class="panel panel-default app-plan app-plan-@{{ plan.id }}">--}}
        {{--<div class="panel-heading text-center">@{{ plan.name }}</div>--}}
        {{--<div class="panel-body">--}}
            {{--<div class="text-center">--}}
            {{--<span>@{{ plan.description }}</span>--}}
            {{--</div>--}}
            {{--<hr>--}}

            {{--<!-- Plan Price -->--}}
            {{--<div class="app-plan-price">--}}
                {{--<div v-if="currentCoupon">--}}
                    {{--<div v-if="plan.price > 0 && currentCoupon.lastsForever">--}}
                        {{--<strike>@{{ plan.price | currency 'R' }}</strike>--}}
                    {{--</div>--}}

                    {{--<div v-if="plan.price === 0 || ! currentCoupon.lastsForever">--}}
                        {{--@{{ plan.price | currency 'R' }}--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div v-else>--}}
                    {{--@{{ plan.price | currency 'R' }}--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<!-- Plan Interval -->--}}
            {{--<div class="app-plan-interval">--}}
                {{--@{{ plan.interval + 'ly' }}--}}
            {{--</div>--}}

            {{--<!-- Plan Discount -->--}}
            {{--<div v-if="plan.price > 0 && currentCoupon">--}}
                {{--<hr>--}}

                {{--<div>--}}
                    {{--<div class="app-plan-discount">--}}
                        {{--@{{ getDiscountPlanPrice(plan.price) | currency 'R' }}--}}
                    {{--</div>--}}

                    {{--<div class="app-plan-discount-interval">--}}
                        {{--@{{ getCouponDisplayDuration(plan) }}--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}

            {{--<hr>--}}

            {{--<div class="app-plan-subscribe-button-container">--}}
                {{--<button class="btn btn-primary app-plan-subscribe-button"  @click.prevent="selectPlan(plan)">--}}
				{{--<span v-if=" ! plan.trialDays && plan.price == 0">--}}
					{{--Register--}}
				{{--</span>--}}

                    {{--<span v-if=" ! plan.trialDays && plan.price > 0">--}}
					{{--Subscribe--}}
				{{--</span>--}}

                    {{--<span v-if="plan.trialDays">--}}
					{{--Begin @{{ plan.trialDays }} Day Trial--}}
				{{--</span>--}}
                {{--</button>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}