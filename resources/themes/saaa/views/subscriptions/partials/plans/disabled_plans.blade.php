<!-- Registration -> Individual Plan Display Block -->
<div class="col-sm-12 col-xs-12 app-plan app-plan-@{{ plan.id }}" style="margin-bottom: 20px;">
    <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);padding: 10px 10px; min-height: 330px">
        <h4 style="font-size: 40px;">
            <div v-if="forms.subscription.bf">
                <small style="color: red; font-weight: bold; text-decoration: line-through; font-size: 18px!important;"><sup style="    font-size: 14px;
    line-height: 60px;">R</sup>@{{ plan.price }}</small>
                <br>
                <sup>R</sup>@{{ plan.bf_price }}<em>/@{{ plan.interval + 'ly' }}</em>
            </div>
            <div v-else>
                <sup>R</sup>@{{ plan.price }}<em>/@{{ plan.interval + 'ly' }}</em>
            </div>
        </h4>
        <div style="min-height: 44px;">
            <h5 style="color: #547698; font-size: 13px">@{{ plan.name }}</h5>
            <br>
            <small style="color: red; font-weight: bold">Save <sup>R</sup>  @{{ parseFloat(plan.price - plan.bf_price).toFixed(2) }}</small>
        </div>

        <hr>
        <p>@{{{ plan.description }}}</p>
        <hr>

        @if (isset($profession))
            @if(! Request::is('profession/*') && ! Request::is('subscriptions/2017/saiba_member') && ! Request::is('subscriptions/2017/saiba_member'))
                <a href="{{ route('profession.show', $profession->slug) }}" class="btn btn-3d btn-success {{ (auth()->user() ? : "btn-block") }}"
                   style="font-size: 14px">
                    <center><i class="fa fa-book"></i></center>
                </a>
            @endif
        @endif
        @if(Request::is('subscriptions/2020/BlackFriday') || Request::is('subscriptions/2020/one-day-only'))
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
                {{--<div v-if="forms.subscription.bf">--}}
                    {{--<a href="#" class="btn btn-3d btn-primary" style="text-transform: uppercase; font-size: 14px" @click.prevent="selectPlan(plan)">--}}
                        {{--<i class="et-trophy"></i>--}}
                        {{--<span v-if=" ! plan.trialDays && plan.price >= 0">--}}
                        {{--Pay Now--}}
                    {{--</span>--}}
                    {{--</a>--}}
                {{--</div>--}}
                <div>
                    <a href="#" v-if="(parseInt(subscriptions.price) >= parseInt(plan.price))" data-target="#need_help_subscription_one" data-toggle="modal" class="btn btn-3d btn-primary btn-block" style="text-transform: uppercase; font-size: 14px">
                        <i class="et-trophy"></i>
                        <span>
                        Change Plan
                    </span>
                    </a>
                    <a href="#" v-else  @click.prevent="changePlanPopup(plan)"  class="btn btn-3d btn-primary btn-block" style="text-transform: uppercase; font-size: 14px">
                        <i class="et-trophy"></i>
                        <span>
                        Buy Now
                    </span>
                    </a>
                </div>
            @else
                <a href="#" class="btn btn-3d btn-primary " style="text-transform: uppercase; font-size: 14px" @click.prevent="selectPlan(plan)">
                    <i class="et-trophy"></i>
                    <span v-if=" ! plan.trialDays && plan.price >= 0">
                        Pay Now
                    </span>
                </a>
            @endif
        @else
            <div class="form-group">
                {{-- <a href="#" data-toggle="modal" data-target="#login" class="btn btn-3d btn-primary" style="text-transform: uppercase; font-size: 14px">
                    <i class="et-trophy"></i>
                    <span>
                    Login
                </span>
                </a>
                <a href="/auth/register" class="btn btn-3d btn-primary" style="text-transform: uppercase; font-size: 14px">
                    Register
                </a> --}}
                <a href="/auth/register?subscription=@{{ plan.id }}" class="btn btn-3d btn-primary" style="text-transform: uppercase; font-size: 14px">
                    Sign Up
                </a>
            </div>
        @endif
    </div>
</div>