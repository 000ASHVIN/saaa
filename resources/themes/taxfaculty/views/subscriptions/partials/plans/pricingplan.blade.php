<!-- Registration -> Individual Plan Display Block -->
<div class="col-sm-12 col-xs-12 app-plan app-plan-@{{ plan.id }}" style="margin-bottom: 20px;">

    @if (auth()->user() && auth()->user()->subscribed('cpd'))
        <div class="ribbon" v-if="user.subscriptions[0].plan_id == plan.id" style="right: 16px;">
            <div class="ribbon-inner">Current</div>
        </div>
    @endif

    <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);padding: 10px 10px; min-height: 330px">
        <h4 style="font-size: 40px;">
            <div v-if="forms.subscription.bf">
                <em> <span v-if="plan.is_practice">From </span></em><sup>R</sup>@{{ plan.bf_price }}<em>/<span v-if="plan.is_practice">user /</span>@{{ plan.interval + 'ly' }}</em>
            </div>
            <div v-else>
                <em> <span v-if="plan.is_practice">From </span></em><sup>R</sup><span v-if="plan.min_price">@{{ plan.min_price }}</span><span v-else>@{{ plan.price }}</span><em>/<span v-if="plan.is_practice">user /</span>@{{ plan.interval + 'ly' }}</em>
            </div>
        </h4>
        <div style="min-height: 44px;">
            <h5 style="color: #547698; font-size: 13px">@{{ plan.name }}</h5>
        </div>

        @if (Request::is('subscriptions/2018/saiba_member'))
            <div v-if="plan.alt_text">
                <p><small>@{{{ plan.alt_text }}}</small></p>
            </div>

            <hr />
            <p>@{{{ plan.description }}}</p>
            <hr />

            <div v-if="plan.small_text">
                <p><small>@{{{ plan.small_text }}}</small></p>
                <hr>
            </div>
        @else
            <hr />
            <p style="min-height: 63px">@{{{ plan.description }}}</p>
            <hr />
        @endif
        @if (isset($profession))
            @if(! Request::is('profession/*') && ! Request::is('subscription_plans') && ! Request::is('subscriptions/2018/saiba_member') && ! Request::is('subscriptions/2018/saiba_member'))
                <a href="{{ route('profession.show', @$profession->slug) }}" class="btn btn-3d btn-success {{ (auth()->user() ? : "btn-block") }}"
                   style="font-size: 14px">
                    <center><i class="fa fa-book"></i></center>
                </a>
            @endif
        @endif
		@if(Request::is('subscription_plans'))
			  <a v-if="plan.profession_slug!=null" href="@{{{ plan.profession_slug }}}"  class="btn btn-3d btn-success "
                   style="font-size: 14px;background: #cccccc;
    border: #cccccc;
    color: black;
">
                    <center>Read More</center>
                </a>
                <a v-else :href="{{@selectedRoute}}"   class="btn btn-3d btn-success "
                   style="font-size: 14px;background: #cccccc;
    border: #cccccc;
    color: black;
">
                    <center>Read More</center>
                </a>
		
		@endif
		
        @if(auth()->user())
            @if(auth()->user()->subscribed('cpd') && auth()->user()->subscription('cpd')->plan->price > 0)
                <div v-if="forms.subscription.bf">
                    <a href="#" class="btn btn-3d btn-primary" style="text-transform: uppercase; font-size: 14px" @click.prevent="selectPlan(plan)">
                        <i class="et-trophy"></i>
                        <span v-if=" ! plan.trialDays && plan.price >= 0">
                        Buy Now
                    </span>
                    </a>
                </div>
                <div v-else>
                    <a href="#" @click.prevent="changePlanPopup(plan)" class="btn btn-3d btn-primary btn-block" style="text-transform: uppercase; font-size: 14px">
                        <i class="et-trophy"></i>
                        <span>
                            <span v-if="(parseInt(subscriptions.price) >= parseInt(plan.price))">  Change Plan</span>
                            <span v-else> Buy Now</span>
                    </span>
                    </a>
                </div>
            @else
                <a href="#" class="btn btn-3d btn-primary " style="text-transform: uppercase; font-size: 14px" @click.prevent="selectPlan(plan)">
                    <i class="et-trophy"></i>
                    <span v-if=" ! plan.trialDays && plan.price >= 0">
                        Buy Now
                    </span>
                </a>
            @endif
        @else
            <div class="form-group">
                <a href="#" data-toggle="modal" data-target="#login" class="btn btn-3d btn-primary" style="text-transform: uppercase; font-size: 14px">
                    <i class="et-trophy"></i>
                    <span>
                    Login
                </span>
                </a>
                <a href="/auth/register" class="btn btn-3d btn-primary" style="text-transform: uppercase; font-size: 14px">
                    Register
                </a>
            </div>
        @endif
    </div>
</div>


