<?php

namespace App\Http\Controllers\Subscriptions;

use App\Billing\Invoice;
use App\Card;
use App\Jobs\assignInvoiceIdToSubscription;
use App\Jobs\SendEventTicketInvoiceJob;
use App\Peach;
use App\Profession\Profession;
use App\Repositories\Invoice\SendInvoiceRepository;
use App\Subscriptions\Models\Subscription;
use App\Users\User;
use Carbon\Carbon;
use App\DebitOrder;
use App\Billing\Payment;
use Illuminate\Http\Request;
use App\Jobs\SubscribeUserToPlan;
use App\Billing\InvoiceRepository;
use App\Subscriptions\Models\Plan;
use App\Http\Controllers\Controller;
use App\Repositories\DebitOrder\DebitOrderRepository;
use App\Subscriptions\Models\SubscriptionGroup;
use App\SubscriptionUpgrade;
use App\CompanyUser;
use App\Video;

class SubscriptionController extends Controller
{
    /**
     * Peach Payment Gateway
     */
    private $peach;

    private $debitOrderRepository;
    private $pricingGroup;

    private $invoiceRepository;

    private $sendInvoiceRepository;

    /**
     * Override redirectPath
     */
    protected $redirectPath = 'dashboard/general';

    /**
     * Setup Controller constructor
     * @param Peach $peach
     * @param DebitOrderRepository $debitOrderRepository
     * @param InvoiceRepository $invoiceRepository
     * @param SendInvoiceRepository $sendInvoiceRepository
     */
    public function __construct(Peach $peach, DebitOrderRepository $debitOrderRepository, InvoiceRepository $invoiceRepository, SendInvoiceRepository $sendInvoiceRepository)
    {
        $this->middleware('auth', ['except' => ['getPlans']]);
        $this->middleware('CheckSuspendedStatus', ['except' => ['getPlans', 'index', 'handleThreeDs']]);
        $this->middleware('redirect.subscribed', ['except' => ['getPlans', 'index', 'handleThreeDs']]);

        $this->peach = $peach;
        $this->debitOrderRepository = $debitOrderRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->sendInvoiceRepository = $sendInvoiceRepository;
    }

    /**
     * Display view with Plans
     */
    public function index()
    {
        if(request()->has('threeDs'))
            $this->handleThreeDs(request());
        return redirect()->back();
    }

    /**
     * Subscribe User to Plan
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'terms' => 'required|accepted'
        ], [
            'terms.required' => 'You must accept the Terms & Conditions to proceed.',
            'terms.accepted' => 'You must accept the Terms & Conditions to proceed.'
        ]);
        switch ($request->paymentOption) {
            case 'cc':
                return $this->handleCCRegistration($request);
                break;
            case 'debit':
                return $this->handleDebitRegistration($request);
                break;
            case 'eft':
                return $this->handleEftRegistration($request);
                break;
        }

        return response()->json(['message' => 'success'], 200);
    }

    public function handleCCRegistration($request)
    {
        $card = Card::find($request->card);
        $plan = Plan::find($request->plan);

        // Do Black Friday Deal
        if ($request['bf']){
            $plan->price = $plan->bf_price;
        }

        $staff = count($request->staff)+1;
        
        if ($plan->pricingGroup->count()) {
            $plan->price = $plan->price * ($staff);
            foreach ($plan->pricingGroup as $pricing) {
                if ((int) $pricing->min_user <= $staff && (int) $pricing->max_user >= $staff) {
                    $plan->price = $pricing->price * ($staff);
                    $this->pricingGroup = $pricing->id;
                }
            }
            $max = $plan->pricingGroup->max('max_user');
            if ($staff > $max) {
                $priceGroup = $plan->pricingGroup->where('max_user', $max)->first();

                if ($priceGroup) {
                    $plan->price = $priceGroup->price * ($staff);
                    $this->pricingGroup = $pricing->id;
                }
            }
            foreach ($plan->pricingGroup as $pricing) {
                if ((int) $pricing->min_user < $staff && (int) $pricing->max_user == 0) {
                    $plan->price = $pricing->price * ($staff);
                    $this->pricingGroup = $pricing->id;
                }
            }   
        }

        /*
        * Add donations to charge amount
        */
        $charge = $plan->price;
        $donations = $request->donations;
        if($donations) {
            $charge = $charge + $donations;
        }

        $payment = $this->peach->charge(
            $card->token,
            $charge,
            $plan->name . ' ' . ucfirst($plan->interval) . "ly Subscription -" . time(),
            $plan->invoice_description
        );

        if(preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $payment['result']['code']) === 1) {

            if (auth()->user() && auth()->user()->subscription('cpd')){
                $agent = User::find(auth()->user()->subscription('cpd')->agent_id);
            }

            auth()->user()->subscriptions->where('name','cpd')->each(function ($subscription){
                $subscription->delete();
            });

            $invoice = $this->invoiceRepository->createSubscriptionInvoice($request->user(), $plan);
            $this->subscribeUser($request);
            $invoice->settle();
            $this->assignPlanFeatureVideosToUser();
            $this->allocatePayment($invoice, $invoice->total - $invoice->discount, "Signup Credit Card Payment", 'cc');
            $this->dispatch((new SendEventTicketInvoiceJob(auth()->user(), $invoice->fresh())));

            if (@$agent && @auth()->user()->fresh()->subscription('cpd')->agent_id == null){
                auth()->user()->fresh()->subscription('cpd')->setAgent($agent);
            }

            // Set user payment Method
            auth()->user()->update(['payment_method' =>'credit_card']);

            $invoice = $invoice->fresh();
            $items = $invoice->items->first();
            $items->quantity= $staff;
            $items->save();
            // $items = $invoice->items;
            return response()->json(['message' => 'success', 'invoice' => $invoice], 200);
        } else {
            return response()->json([
                'error' => $payment['result']['description']
            ], 422);
        }
    }

    public function handleDebitRegistration($request)
    {
      
        $plan = Plan::find($request->plan);

        // Do Black Friday Deal
        if ($request['bf']){
            $plan->price = $plan->bf_price;
        }
        $staff = count($request->staff)+1;
        
        if ($plan->pricingGroup->count()) {
            $plan->price = $plan->price * ($staff);
            foreach ($plan->pricingGroup as $pricing) {
                if ((int) $pricing->min_user <= $staff && (int) $pricing->max_user >= $staff) {
                    $plan->price = $pricing->price * ($staff);
                    $this->pricingGroup = $pricing->id;
                }
            }
            $max = $plan->pricingGroup->max('max_user');
            if ($staff > $max) {
                $priceGroup = $plan->pricingGroup->where('max_user', $max)->first();

                if ($priceGroup) {
                    $plan->price = $priceGroup->price * ($staff);
                    $this->pricingGroup = $pricing->id;
                }
            }
            foreach ($plan->pricingGroup as $pricing) {
                if ((int) $pricing->min_user < $staff && (int) $pricing->max_user == 0) {
                    $plan->price = $pricing->price * ($staff);
                    $this->pricingGroup = $pricing->id;
                }
            }   
            
        }
        $this->createDebitOrder($request);

        if($request->immediately){
            SubscriptionUpgrade::create([
                'plan_id'=>$plan->id,
                'new_plan_id'=>$request->plan,
                'user_id'=>auth()->user()->id,
                'data'=>json_encode($request->all())
                ]);
                return response()->json(['message' => 'success'], 200);
        }

        $invoice = $this->invoiceRepository->createSubscriptionInvoice($request->user(), $plan);

      

        if ($invoice->fresh()->user && $invoice->fresh()->user->subscription('cpd')){
            $agent = User::find($invoice->fresh()->user->subscription('cpd')->agent_id);
        }

        $invoice->fresh()->user->subscriptions->where('name','cpd')->each(function ($subscription){
            $subscription->delete();
        });

        // Set user payment Method
        $invoice->fresh()->user->update(['payment_method' => 'debit_order']);

        $this->subscribeUser($request);
        $this->assignPlanFeatureVideosToUser();

        if ($agent && $invoice->fresh()->user->fresh()->subscription('cpd')->agent_id == null){
            $invoice->fresh()->user->fresh()->subscription('cpd')->setAgent($agent);
        }

        $this->dispatch((new SendEventTicketInvoiceJob($invoice->fresh()->user, $invoice->fresh())));
        $invoice->fresh()->user->subscription('cpd')->setInvoiceId($invoice);

        $invoice = $invoice->fresh();
        $items = $invoice->items->first();
        $items->quantity= $staff;
        $items->save();
        return response()->json(['message' => 'success', 'invoice' => $invoice], 200);
    }

    public function handleEftRegistration($request)
    {
        $plan = Plan::find($request->plan);

        // Do Black Friday Deal
        if ($request['bf']) {
            $plan->price = $plan->bf_price;
        }
        $staff = count($request->staff)+1;
        
        if ($plan->pricingGroup->count()) {
            $plan->price = $plan->price * ($staff);
            foreach ($plan->pricingGroup as $pricing) {
                if ((int) $pricing->min_user <= $staff && (int) $pricing->max_user >= $staff) {
                    $plan->price = $pricing->price * ($staff);
                    $this->pricingGroup = $pricing->id;
                }
            }
            $max = $plan->pricingGroup->max('max_user');
            if ($staff > $max) {
                $priceGroup = $plan->pricingGroup->where('max_user', $max)->first();

                if ($priceGroup) {
                    $plan->price = $priceGroup->price * ($staff);
                    $this->pricingGroup = $pricing->id;
                }
            }
            foreach ($plan->pricingGroup as $pricing) {
                if ((int) $pricing->min_user < $staff && (int) $pricing->max_user == 0) {
                    $plan->price = $pricing->price * ($staff);
                    $this->pricingGroup = $pricing->id;
                }
            }   
        }
        $invoice = $this->invoiceRepository->createSubscriptionInvoice($request->user(), $plan);
        $invoice->settle();
        $this->allocatePayment($invoice, $invoice->total - $invoice->discount, "Instant EFT Payment", 'instant_eft');

        if (auth()->user() && auth()->user()->subscription('cpd')){
            $agent = User::find(auth()->user()->subscription('cpd')->agent_id);
        }

        auth()->user()->subscriptions->where('name','cpd')->each(function ($subscription){
            $subscription->delete();
        });

        // Set user payment Method
        auth()->user()->update(['payment_method' => 'instant_eft']);
        $this->subscribeUser($request);
        $this->assignPlanFeatureVideosToUser();

        if ($agent && auth()->user()->fresh()->subscription('cpd')->agent_id == null){
            auth()->user()->fresh()->subscription('cpd')->setAgent($agent);
        }

        //$this->sendInvoiceRepository->sendInvoice(auth()->user(), $invoice->fresh());
        $invoice = $invoice->fresh();
        $items = $invoice->items->first();
        $items->quantity= $staff;
        $items->save();
        return response()->json(['message' => 'success', 'invoice' => $invoice], 200);
    }

    /**
     * Retrieve all the Subscription Plans
     */
    public function getPlans()
    {
        return Plan::active()->get();
    }

    public function allocatePayment($invoice, $amount, $description, $method)
    {
        $invoice->transactions()->create([
            'user_id' => auth()->user()->id, 
            'invoice_id' => $invoice->id, 
            'type' => 'credit',
            'display_type' => 'Payment', 
            'status' => 'Closed', 
            'category' => $invoice->type, 
            'amount' => $amount, 
            'ref' => $invoice->reference,
            'method' => $method,
            'description' => $description,
            'tags' => "Payment", 
            'date' => Carbon::now()
        ]);
    }
    
    /**
     * Subscribe User to Selected Plan
     */
    protected function subscribeUser(Request $request)
    {
        $this->dispatchFromArray(SubscribeUserToPlan::class, [
            'user_id' => $request->user()->id,
            'plan' => $request->plan,
            'features' => $request->features,
            'payment' => $request->paymentOption,
        ]);
        if (count($request->staff) > 0) {
            $staff = $request->staff;
            $this->subscriptionAssign($request, $staff);
        }
    }

    protected function assignPlanFeatureVideosToUser()
    {
        // $videoIds = [60,61,62,64];
        $videoIds = [25];
        $videos = Video::whereIn('id', $videoIds)->get();
        $user = auth()->user();
        $owned_webinars = $user->webinars->pluck('id')->toArray();
        foreach($videos as $video){
            if(!in_array($video->id,$owned_webinars)) {
                $user->webinars()->save($video);
            }  
        }
    }

    /**
     * Subscribe Member to Selected Plan.
     */
    protected function subscribeMember(Request $request, $member)
    {
        $subscriptions = $this->dispatchFromArray(SubscribeUserToPlan::class, [
            'user_id' => $member,
            'plan' => $request->plan,
            'features' => $request->features,
            'payment' => $request->paymentOption,
        ]);
        $subscriptionGroup = SubscriptionGroup::create([
            'user_id'=> $member,
            'admin_id'=> auth()->user()->id,
            'plan_id'=> $request->plan,
            'pricing_group_id'=> $this->pricingGroup ?? 0,
            'subscription_id'=> @$subscriptions->id
        ]);
    }

    /**
     * Setup User Debit Order
     */
    protected function createDebitOrder($request)
    {
        if ($request->user()->debit){
            $request->user()->debit()->delete();
        }

        $request->user()->debit()->save(new DebitOrder([
            'bank' => $request->debit['bank'],
            'number' => $request->debit['account_number'],
            'type' => $request->debit['account_type'],
            'billable_date' => $request->debit['billable_date'],
            // 'branch_name' => $request->debit['branch_name'],
            'branch_name' => '',
            'branch_code' => $request->debit['branch_code'],
            'last_debit' => Carbon::now()->addMonths(12),
            'id_number' => (@$request->debit['id_number']) ? @$request->debit['id_number'] : '',
            'registration_number' => (isset($request->debit['registration_number'])) ? $request->debit['registration_number'] : '',
            'account_holder' => $request->debit['account_holder'],
            'type_of_account' => $request->debit['type_of_account'],
            'bill_at_next_available_date' => true,
            'next_debit_date' => Carbon::now()->addDays(1),
            'active' => true,
            'peach' => true,
            'otp' => $request->debit['otp']
        ]));

        $request->user()->update([
            'payment_method' => 'debit_order'
        ]);
        $this->debitOrderRepository->sendNewCreatedDebitOrderDetailsEmail($request->user());
    }

    /**
     * Handle 3DS on Return
     */
    protected function handleThreeDs($request)
    {
        $payment = $this->peach->fetchPayment($request->id);

        if(! Card::where('token', $payment->registrationId)->exists() && $payment->successful()) {
            $card = new Card([
                'token' => $payment->registrationId,
                'brand' => $payment->paymentBrand,
                'number' => $payment->card['bin'] . '******' . $payment->card['last4Digits'],
                'exp_month' => $payment->card['expiryMonth'],
                'exp_year' => $payment->card['expiryYear']
            ]);

            auth()->user()->cards()->save($card);

            if(count(auth()->user()->cards) == 1) {
                auth()->user()->update([
                    'primary_card' => $card->id
                ]);
            }

            alert()->success('Credit card added successfully.', 'Success');
        } else {
            alert()->error('Credit card already added or invalid.', 'Could not save credit card');
        }
    }

    protected function subscriptionAssign($request, $staffs)
    {
        foreach ($staffs as $staff) {
            $user = User::find($staff);

            if ($user && $user->subscription('cpd')) {
                $agent = User::find($user->subscription('cpd')->agent_id);
            }

            $user->subscriptions->where('name', 'cpd')->each(function ($subscription) {
                $subscription->delete();
            });
            $this->subscribeMember($request, $staff);
            $user->fresh()->subscription('cpd')->setPlanInGroup(true);
            if (@$agent && @$user->fresh()->subscription('cpd')->agent_id == null) {
                $user->fresh()->subscription('cpd')->setAgent($agent);
            }
        }

        if(count($staffs) > 0) {
            $company_staffs = auth()->user()->company->staff;
            $plan = Plan::where('price', '0')->where('interval', 'year')->get()->first();
            foreach ($company_staffs as $company_staff) {
                if(!in_array($company_staff->id , $staffs)) {
                    if($company_staff->fresh()->subscription('cpd')->plan_id == $plan->id) {
                        CompanyUser::where('user_id', $company_staff->id)->where('company_id', auth()->user()->company->id)->delete();
                    }
                }
            }
        }
    }
}