<?php

namespace App\Http\Controllers\Subscriptions;

use App\Card;
use App\Note;
use App\Peach;
use App\Rep;
use App\Repositories\ThreeDsRepository\ThreeDsRepository;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Jobs\SubscribeUserToPlan;
use App\Subscriptions\Models\Plan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Subscriptions\Models\Period;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Subscriptions\Models\CustomPlanFeature;

class SubscriptionUpgradeController extends Controller
{
    /**
     * @var ThreeDsRepository
     */
    private $threeDsRepository;

    public function __construct(ThreeDsRepository $threeDsRepository)
    {
        $this->middleware('auth', ['except' => ['bf_deals', 'last_minute','last_minute_saiba','oneDayOnly', 'store', 'need_help_with_subscription','corona_deals','package','one_day_offer']]);
        $this->middleware('subscribed', ['except' => ['bf_deals', 'last_minute','last_minute_saiba','oneDayOnly', 'store', 'need_help_with_subscription','corona_deals','package','one_day_offer']]);
        $this->middleware('CheckSuspendedStatus', ['except' => ['bf_deals', 'last_minute','last_minute_saiba','oneDayOnly', 'store', 'need_help_with_subscription','corona_deals','package','one_day_offer']]);
        $this->threeDsRepository = $threeDsRepository;
    }
    public function package(Request $request,$package)
    {
        try{
            if(request()->has('threeDs'))
            $this->threeDsRepository->handleThreeDs(request());

        $plans = Plan::where('price', '!=', 0)
            ->where('slug',$package);
        
        if(isset($_REQUEST['renew']) && !empty($_REQUEST['renew'])) {
            $plans = $plans->where('interval', 'year');
        }

        $plans = $plans->limit(1)
            ->get();

        $plans->each(function ($plan){
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
            
            $plan->pricingGroup = $plan->pricingGroup;
            if($plan->pricingGroup->count() && $plan->is_practice){
                $plan->min_price=$plan->pricingGroup->min('price');
            }

            if(isset($_REQUEST['renew']) && !empty($_REQUEST['renew'])) {
                if($plan->id != null) {
                    setcookie('plan', (string)$plan->id);
                }
            }

        });

        return view('subscriptions.2017.package', compact('plans'));
        }catch(\Exception $e){
            dd($e);
        }
    }

    public function bf_deals(Request $request)
    {
        if(request()->has('threeDs'))
            $this->threeDsRepository->handleThreeDs(request());

        $plans = Plan::where('inactive', '==', false)
            ->where('interval', 'year')
            ->where('price', '!=', 0)
            ->where('enable_bf', true)
            ->get();

        $plans->each(function ($plan){
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
            unset($plan->features);
            $plan->features = $new;
        });

        $selectedPlan = null;
        if($request->has('subscription')) {
            $selectedPlan = Plan::find($request->subscription);
        }

        return view('subscriptions.2017.index', compact('plans', 'selectedPlan'));
    }
    public function corona_deals(Request $request)
    {
        if(request()->has('threeDs'))
            $this->threeDsRepository->handleThreeDs(request());

        $plans = Plan::where('inactive', '==', false)
            ->where('interval', 'year')
            ->where('price', '!=', 0)
            ->where('enable_bf', true)
            ->get();

        $plans->each(function ($plan){
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
            unset($plan->features);
            $plan->features = $new;
        });

        $selectedPlan = null;
        if($request->has('subscription')) {
            $selectedPlan = Plan::find($request->subscription);
        }

        return view('subscriptions.2017.corona_deals', compact('plans', 'selectedPlan'));
    }

    public function one_day_offer(Request $request)
    {
        if(request()->has('threeDs'))
            $this->threeDsRepository->handleThreeDs(request());

        $plans = Plan::where('inactive', '==', false)
            ->where('interval', 'year')
            ->where('price', '!=', 0)
            ->where('enable_bf', true)
            ->get();

        $plans->each(function ($plan){
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
            unset($plan->features);
            $plan->features = $new;
        });
        if(env('APP_THEME') == 'taxfaculty'){
            return view('subscriptions.2017.one_day_offer', compact('plans'));
        }else{
            return view('subscriptions.2017.corona_deals', compact('plans'));
        }
    }

    public function oneDayOnly(Request $request)
    {
        if(request()->has('threeDs'))
            $this->threeDsRepository->handleThreeDs(request());

        $plans = Plan::where('inactive', '==', false)
            ->where('interval', 'year')
            ->where('price', '!=', 0)
            ->get();

        $plans->each(function ($plan){
            $new = $plan->features->reject(function ($feature){
                if ($feature->selectable == false){
                    return $feature;
                }
            });

            unset($plan->features);
            $plan->features = $new;
        });

        $selectedPlan = null;
        if($request->has('subscription')) {
            $selectedPlan = Plan::find($request->subscription);
        }
        return view('subscriptions.2017.one_day_only', compact('plans', 'selectedPlan'));
    }

    public function last_minute(Request $request)
    {
        if(request()->has('threeDs'))
            $this->threeDsRepository->handleThreeDs(request());

        $plans = Plan::where('inactive', '==', true)
            ->where('last_minute', true)
            ->where('interval', 'year')
            ->get();

        $plans->each(function ($plan){
            $new = $plan->features->reject(function ($feature){
                if ($feature->selectable == false){
                    return $feature;
                }
            });

            unset($plan->features);
            $plan->features = $new;
        });

        return view('subscriptions.once.index', compact('plans'));
    }

    public function last_minute_saiba(Request $request)
    {
        if(request()->has('threeDs'))
            $this->threeDsRepository->handleThreeDs(request());

        $plans = Plan::where('inactive', '==', true)
            ->where('last_minute', true)
            ->where('interval', 'year')
            ->where('id','64')
            ->get();

        $plans->each(function ($plan){
            $new = $plan->features->reject(function ($feature){
                if ($feature->selectable == false){
                    return $feature;
                }
            });

            unset($plan->features);
            $plan->features = $new;
        });

        return view('subscriptions.once.saiba', compact('plans'));
    }

    
    public function need_help_with_subscription(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email_address' => 'required|email',
            'cell' => 'required|numeric'
        ]);

        $user = $request->only([
            'first_name',
            'last_name',
            'email_address',
            'cell'
        ]);

        $rep = Rep::nextAvailable();

        try {
            Mail::send('emails.leads.event_registration', ['rep' => $rep, 'user' => $user, 'topic' => 'CPD Subscription Discount Deals'], function ($m) use ($user, $rep) {
                $m->from(config('app.email'), config('app.name'));
                $m->to($rep->email)->subject('New lead for CPD Subscription');
            });

            $rep->update(['emailedLast' => Carbon::now()]);
        } catch (\Exception $e) {
            alert()->error('Something went wrong, please contact us on 010 593 0466', 'Warning')->persistent('close');
            return back();
        }

        alert()->success('One of our agents will be in touch with you shortly', 'Success')->persistent('close');
        return back();
    }

    public function index()
    {
        $plans = Plan::active()->get();
        return view('subscriptions.upgrade', compact('plans'));
    }

    public function store(Request $request)
    {
        $user  = auth()->user();
        $plan = Plan::find($request->plan);

        DB::transaction(function() use($request, $user, $plan) {
            // 1 Change Subscription Plan
            auth()->user()->subscription('cpd')->changePlan($request->plan)->save();

            // 1.5 Setup Custom Features
            if($plan->is_custom && ! empty($request->features)) {
                CustomPlanFeature::create([
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'features' => $request->features
                ]);
            }
        });

        $note = new Note([
            'type' => 'new_subscription',
            'description' => 'New CPD Subscription started for '.$plan->name.' (' . ucfirst($plan->interval) . 'ly)',
            'logged_by' => 'System',
        ]);
        auth()->user()->notes()->save($note);

        // 3 Respond
        return response()->json(['message' => 'Success']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'terms' => ['required', 'accepted'],
            'plan' => ['required_unless:free,true']
        ]);
    }

    public function help(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email_address' => 'required|email',
            'cell' => 'required|numeric'
        ]);

        $user = $request->only([
            'first_name',
            'last_name',
            'email_address',
            'cell'
        ]);

        $rep = Rep::nextAvailable();

        try {
            
    
            Mail::send('emails.leads.event_registration', ['rep' => $rep, 'user' => $user, 'topic' => 'CPD Subscription Discount Deals'], function ($m) use ($user, $rep) {
                $m->from(config('app.email'), config('app.name'));
                $m->to($rep->email)->subject('Need Help');
            });

            $rep->update(['emailedLast' => Carbon::now()]);
        } catch (\Exception $e) {
            alert()->error('Something went wrong, please contact us on 010 593 0466', 'Warning')->persistent('close');
            return back();
        }

        alert()->success('One of our agents will be in touch with you shortly', 'Success')->persistent('close');
        return back();
    }
}
