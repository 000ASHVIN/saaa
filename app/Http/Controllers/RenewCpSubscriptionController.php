<?php

namespace App\Http\Controllers;

use App\Subscriptions\Models\Period;
use App\Subscriptions\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RenewCpSubscriptionController extends Controller
{
    protected $plans;

    public function renew_subscription()
    {

        return redirect()->route('home');
        $user = auth()->user();

        Plan::all()->each(function($plan) {
            if(! $plan->inactive)
                $this->plans[$plan->id] = $plan->name . ' (' . money_format('%2n', $plan->price) . ')';
        });

        $plans = $this->plans;
        return view('pages.about.cpd.confirm', compact('user', 'plans'));
    }

    public function renew_subscription_post(Request $request)
    {
        $user = auth()->user();
        $user->subscription('cpd')->changePlan($request->new_plan)->save();

        $period = new Period('month', 1, Carbon::now()->startOfMonth());
        $user->subscription('cpd')->starts_at = $period->getStartDate();
        $user->subscription('cpd')->ends_at = $period->getEndDate();
        $user->subscription('cpd')->canceled_at = NULL;
        $user->subscription('cpd')->save();

        if (!$request->has('pi_cover')){
            alert()->success('Thank you for renewing your subscription', 'success');
            return redirect()->route('dashboard');
        };
        return redirect()->route('insurance.index');

    }
}
