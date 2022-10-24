<div class="row">
    <div class="col-md-4" style="padding-left: 1px; padding-right: 1px">
        <!-- Registration -> Individual Plan Display Block -->
        <div style="margin-bottom: 20px" class="col-sm-12 col-xs-12 app-plan app-plan-1">
            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);">
                <h4 style="font-size: 30px!important;">
                    <span style="font-size: 16px">Free series</span>
                </h4>

                <h5 style="text-transform: uppercase;
                                font-weight: 500;
                                margin: 0;
                                font-size: 13px;
                                color: #547698;
                                letter-spacing: 2px;">PRACTICE MANAGEMENT</h5>
                <hr>
                <p>CPD Subscription Package</p>
                <hr>

                @if(auth()->user() && !auth()->user()->subscribed('cpd'))
                    <a href="/profession/practice-management" class="btn btn-default">Read More</a>
                    <a href="/profession/practice-management" class="btn btn-primary">Subscribe</a>

                @elseif(auth()->user() && auth()->user()->subscription('cpd')->plan->price >= 0)
                    <a href="/profession/practice-management" class="btn btn-primary">Current Plan</a>

                @else
                    <a href="/profession/practice-management" class="btn btn-default">Read More</a>
                    <a href="/auth/register" class="btn btn-primary">Register</a>
                @endif
            </div>
        </div>


    </div>

    <div class="col-md-4" style="padding-left: 1px; padding-right: 1px">
        <!-- Registration -> Individual Plan Display Block -->
        <div style="margin-bottom: 20px" class="col-sm-12 col-xs-12 app-plan app-plan-1">
            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);">
                <h4 style="font-size: 30px!important;">
                    <span style="font-size: 16px">From</span> <sup>R</sup>2700.00<em>/PM</em>
                </h4>

                <h5 style="text-transform: uppercase;
                                font-weight: 500;
                                margin: 0;
                                font-size: 13px;
                                color: #547698;
                                letter-spacing: 2px;">COMPLIANCE AND LEGISLATION UPDATES</h5>
                <hr>
                <p>CPD Subscription Package</p>
                <hr>

                @if(auth()->user() && !auth()->user()->subscribed('cpd'))
                    <a href="/profession/monthly-legislation-update" class="btn btn-default">Read More</a>
                    <a href="/profession/monthly-legislation-update" class="btn btn-primary">Subscribe</a>

                @elseif(auth()->user() && auth()->user()->subscribed('cpd'))
                    <a href="/profession/monthly-legislation-update" class="btn btn-default">Read More</a>

                @else
                    <a href="/profession/monthly-legislation-update" class="btn btn-default">Read More</a>
                    <a href="/auth/register" class="btn btn-primary">Register</a>
                @endif
            </div>
        </div>


    </div>

    <div class="col-md-4" style="padding-left: 1px; padding-right: 1px">
        <!-- Registration -> Individual Plan Display Block -->
        <div style="margin-bottom: 20px" class="col-sm-12 col-xs-12 app-plan app-plan-1">
            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);">
                <h4 style="font-size: 30px!important;">
                    <span style="font-size: 16px">From</span> <sup>R</sup>4806.00<em>/PM</em>
                </h4>

                <h5 style="text-transform: uppercase;
                                font-weight: 500;
                                margin: 0;
                                font-size: 13px;
                                color: #547698;
                                letter-spacing: 2px;">BUILD YOUR OWN</h5>
                <hr>
                <p>CPD Subscription Package</p>
                <hr>

                @if(auth()->user() && !auth()->user()->subscribed('cpd'))
                    <a href="/profession/build-your-own" class="btn btn-default">Read More</a>
                    <a href="/profession/build-your-own" class="btn btn-primary">Subscribe</a>

                @elseif(auth()->user() && auth()->user()->subscribed('cpd'))
                    <a href="/profession/build-your-own" class="btn btn-default">Read More</a>

                @else
                    <a href="/profession/build-your-own" class="btn btn-default">Read More</a>
                    <a href="/auth/register" class="btn btn-primary">Register</a>
                @endif
            </div>
        </div>


    </div>

    <div class="col-md-4" style="padding-left: 1px; padding-right: 1px">
        <div style="margin-bottom: 20px" class="col-sm-12 col-xs-12 app-plan app-plan-6">
            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);">
                <h4 style="font-size: 30px!important;"><span style="font-size: 16px">From</span><sup>R</sup>4050.00<em>/yearly</em></h4>
                <h5 style="text-transform: uppercase;
                                font-weight: 500;
                                margin: 0;
                                font-size: 13px;
                                color: #547698;
                                letter-spacing: 2px;">BUSINESS ACCOUNTANT IN PRACTICE</h5>
                <hr>
                <p>CPD Subscription Package</p>
                <hr>

                @if(auth()->user() && !auth()->user()->subscribed('cpd'))
                    <a href="/profession/business-accountant-in-practice" class="btn btn-default">Read More</a>
                    <a href="/profession/business-accountant-in-practice" class="btn btn-primary">Subscribe</a>

                @elseif(auth()->user() && auth()->user()->subscribed('cpd'))
                    <a href="/profession/business-accountant-in-practice" class="btn btn-default">Read More</a>

                @else
                    <a href="/profession/business-accountant-in-practice" class="btn btn-default">Read More</a>
                    <a href="/auth/register" class="btn btn-primary">Register</a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4" style="padding-left: 1px; padding-right: 1px">
        <div style="margin-bottom: 20px" class="col-sm-12 col-xs-12 app-plan app-plan-11">
            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);">
                <h4 style="font-size: 30px!important;"><span style="font-size: 16px">From</span><sup>R</sup>3240.00<em>/yearly</em></h4>
                <h5 style="text-transform: uppercase;
                                font-weight: 500;
                                margin: 0;
                                font-size: 13px;
                                color: #547698;
                                letter-spacing: 2px;">CERTIFIED BOOKKEEPER</h5>
                <hr>
                <p>CPD Subscription Package</p>
                <hr>

                @if(auth()->user() && !auth()->user()->subscribed('cpd'))
                    <a href="/profession/certified-bookkeeper" class="btn btn-default">Read More</a>
                    <a href="/profession/certified-bookkeeper" class="btn btn-primary">Subscribe</a>

                @elseif(auth()->user() && auth()->user()->subscribed('cpd'))
                    <a href="/profession/certified-bookkeeper" class="btn btn-default">Read More</a>

                @else
                    <a href="/profession/certified-bookkeeper" class="btn btn-default">Read More</a>
                    <a href="/auth/register" class="btn btn-primary">Register</a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4" style="padding-left: 1px; padding-right: 1px">
        <div style="margin-bottom: 20px" class="col-sm-12 col-xs-12 app-plan app-plan-7">
            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);">
                <h4 style="font-size: 30px!important;"><span style="font-size: 16px">From</span><sup>R</sup>4806.00<em>/yearly</em></h4>
                <h5 style="text-transform: uppercase;
                                font-weight: 500;
                                margin: 0;
                                font-size: 13px;
                                color: #547698;
                                letter-spacing: 2px;">CHARTERED ACCOUNTANT</h5>
                <hr>
                <p>CPD Subscription Package</p>
                <hr>

                @if(auth()->user() && !auth()->user()->subscribed('cpd'))
                    <a href="/profession/chartered-accountant" class="btn btn-default">Read More</a>
                    <a href="/profession/chartered-accountant" class="btn btn-primary">Subscribe</a>

                @elseif(auth()->user() && auth()->user()->subscribed('cpd'))
                    <a href="/profession/chartered-accountant" class="btn btn-default">Read More</a>

                @else
                    <a href="/profession/chartered-accountant" class="btn btn-default">Read More</a>
                    <a href="/auth/register" class="btn btn-primary">Register</a>
                @endif
            </div>
        </div>


    </div><div class="col-md-4" style="padding-left: 1px; padding-right: 1px">
        <div style="margin-bottom: 20px" class="col-sm-12 col-xs-12 app-plan app-plan-8">
            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);">
                <h4 style="font-size: 30px!important;"><span style="font-size: 16px">From</span><sup>R</sup>3780.00<em>/yearly</em></h4>
                <h5 style="text-transform: uppercase;
                                font-weight: 500;
                                margin: 0;
                                font-size: 13px;
                                color: #547698;
                                letter-spacing: 2px;">COMPANY SECRETARY</h5>
                <hr>
                <p>CPD Subscription Package</p>
                <hr>

                @if(auth()->user() && !auth()->user()->subscribed('cpd'))
                    <a href="/profession/company-secretary" class="btn btn-default">Read More</a>
                    <a href="/profession/company-secretary" class="btn btn-primary">Subscribe</a>

                @elseif(auth()->user() && auth()->user()->subscribed('cpd'))
                    <a href="/profession/company-secretary" class="btn btn-default">Read More</a>

                @else
                    <a href="/profession/company-secretary" class="btn btn-default">Read More</a>
                    <a href="/auth/register" class="btn btn-primary">Register</a>
                @endif
            </div>
        </div>


    </div><div class="col-md-4" style="padding-left: 1px; padding-right: 1px">
        <div style="margin-bottom: 20px" class="col-sm-12 col-xs-12 app-plan app-plan-10">
            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);">
                <h4 style="font-size: 30px!important;"><span style="font-size: 16px">From</span><sup>R</sup>4806.00<em>/yearly</em></h4>
                <h5 style="text-transform: uppercase;
                    font-weight: 500;
                    margin: 0;
                    font-size: 13px;
                    color: #547698;
                    letter-spacing: 2px;">PROFESSIONAL ACCOUNTANT</h5>
                <hr>
                <p>CPD Subscription Package</p>
                <hr>

                @if(auth()->user() && !auth()->user()->subscribed('cpd'))
                    <a href="/profession/professional-accountant" class="btn btn-default">Read More</a>
                    <a href="/profession/professional-accountant" class="btn btn-primary">Subscribe</a>

                @elseif(auth()->user() && auth()->user()->subscribed('cpd'))
                    <a href="/profession/professional-accountant" class="btn btn-default">Read More</a>

                @else
                    <a href="/profession/professional-accountant" class="btn btn-default">Read More</a>
                    <a href="/auth/register" class="btn btn-primary">Register</a>
                @endif
            </div>
        </div>


    </div>


    <div class="col-md-4" style="padding-left: 1px; padding-right: 1px">
        <!-- Registration -> Individual Plan Display Block -->
        <div style="margin-bottom: 20px" class="col-sm-12 col-xs-12 app-plan app-plan-9">
            <div class="price-clean" style="background-color: rgba(255, 255, 255, 0.82);">
                <h4 style="font-size: 30px!important;"><span style="font-size: 16px">From</span><sup>R</sup>4899.00<em>/yearly</em></h4>
                <h5 style="text-transform: uppercase;
                                font-weight: 500;
                                margin: 0;
                                font-size: 13px;
                                color: #547698;
                                letter-spacing: 2px;">TAX PRACTITIONER</h5>
                <hr>
                <p>CPD Subscription Package</p>
                <hr>

                @if(auth()->user() && !auth()->user()->subscribed('cpd'))
                    <a href="/profession/tax-practitioner" class="btn btn-default">Read More</a>
                    <a href="/profession/tax-practitioner" class="btn btn-primary">Subscribe</a>

                @elseif(auth()->user() && auth()->user()->subscribed('cpd'))
                    <a href="/profession/tax-practitioner" class="btn btn-default">Read More</a>

                @else
                    <a href="/profession/tax-practitioner" class="btn btn-default">Read More</a>
                    <a href="/auth/register" class="btn btn-primary">Register</a>
                @endif
            </div>
        </div>
    </div>
</div>