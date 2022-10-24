<?php

namespace App\Http\Controllers;

use App\CompanyProfessionRegistration;
use App\Http\Requests\CompanyProfessionRegistrationRequest;
use App\Profession\Profession;
use App\Repositories\ThreeDsRepository\ThreeDsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Subscriptions\Models\Plan;

class ProfessionController extends Controller
{
    private $threeDsRepository;

    public function __construct(ThreeDsRepository $threeDsRepository)
    {
        $this->threeDsRepository = $threeDsRepository;
    }

    public function show(Request $request, $slug)
    {
        if (request()->has('threeDs')) {
            $this->threeDsRepository->handleThreeDs(request());
        }

        $profession = Profession::with(['plans'=>function($q) {
            $q->where('inactive', '=', false);
        }, 'plans.features'])->where('slug', $slug)->first();

        $profession->plans->where('inactive',0)->each(function ($plan) {
            $new = $plan->features->reject(function ($feature) {
                if ($feature->selectable == false) {
                    return $feature;
                }
            });
            $plan->pricingGroup = $plan->pricingGroup;
            if($plan->pricingGroup->count() && $plan->is_practice){
                $plan->min_price=$plan->pricingGroup->min('price');
            }
            unset($plan->features);
            $plan->features = $new;
        });

        if (auth()->user() && auth()->user()->body && auth()->user()->membership_verified) {
            if (auth()->user()->body->professions->contains($profession)) {
                if ($profession->is_custom) {
                    unset($profession->plans);
                    $profession->plans = auth()->user()->body->plans()->where('is_custom', '1')->get();
                } else {
                    unset($profession->plans);
                    $profession->plans = auth()->user()->body->plans()->where('is_custom', '0')->get();
                }
            }
        }
        
        $events = [];
        $professionPlans = $profession->plans;

        foreach($professionPlans as $plan) {
            $plan_events = $plan->getPlanEvents($upcoming = true);
            foreach($plan_events as $event) {
                if(!array_key_exists($event->id, $events)) {
                    $events[$event->id] = $event;
                }
            }
        }

        $selectedPlan = null;
        if($request->has('subscription')) {
            $selectedPlan = Plan::find($request->subscription);
            // $selectedPlan = $request->profession;
        }
        return view('professions.show', compact('profession', 'selectedPlan', 'events'));
    }

    public function request_practice_information($slug)
    {
        $profession = Profession::where('slug', $slug)->first();
        return view('professions.more_information', compact('profession'));
    }

    public function send_request_practice_information(CompanyProfessionRegistrationRequest $request)
    {
        $data = $request->except('_token');
        CompanyProfessionRegistration::create($data);

        try {
            Mail::send('emails.company.application', ['user' => $data], function ($m) {
                $m->from(config('app.email'), config('app.name'));
                $m->to(config('app.email'))->subject('New Company Application');
            });
        } catch (\Exception $exception) {
            alert()->error('Could not send email, please contact us', 'Error');
            return back();
        }

        alert()->success('We have received your order', 'Thank you');
        return redirect()->route('home');
    }
    
    public function plans_and_pricing(Request $request)
    {
        if (request()->has('threeDs')) {
            $this->threeDsRepository->handleThreeDs(request());
        }
        
        $business = Plan::whereIn('package_type', ['business','individual','trainee'])->get();
        $individual = Plan::where('package_type', 'individual')->get()->sortBy('interval');
        $trainee = Plan::where('package_type', 'trainee')->get()->sortBy('interval');
        // foreach($trainee as $t){
        //     dd($t->name);
        // } 
        
        $business->each(function ($plan){
            $new = $plan->features->reject(function ($feature){
                if ($feature->selectable == false){
                    return $feature;
                }
            });
            if($plan->professions->count()){
                $profession=$plan->professions->first();
                if($profession){
                    $plan->profession_slug = route('profession.show',@$profession->slug);
                }
            }
            
            $plan->pricingGroup = $plan->pricingGroup;
            if($plan->pricingGroup->count() && $plan->is_practice){
                $plan->min_price=$plan->pricingGroup->min('price');
            }
            unset($plan->features);
            unset($plan->page_description);
            unset($plan->professions);
            $plan->features = $new;
        });
        
        $selectedPlan = null;
        if($request->has('subscription')) {
            $selectedPlan = Plan::find($request->subscription);
        }

        return view('professions.plans_and_pricing',compact('business','individual','trainee', 'selectedPlan'));
    }
}
