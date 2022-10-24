<?php

namespace App\Http\Controllers\Admin;

use App\Billing\InvoiceRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Jobs\SendEventTicketInvoiceJob;
use App\Jobs\sendUpgradeNotification;
use App\Jobs\SubscribeUserToPlan;
use App\Note;
use App\Repositories\CreditMemo\CreditMemoRepository;
use App\Subscriptions\Models\CustomPlanFeature;
use App\Subscriptions\Models\Period;
use App\Subscriptions\Models\Plan;
use App\Users\User;
use App\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\DebitOrder;

class SubscriptionController extends Controller
{
    protected $invoiceRepository;
    /**
     * @var CreditMemoRepository
     */
    private $creditMemoRepository;

    public function __construct(InvoiceRepository $invoiceRepository, CreditMemoRepository $creditMemoRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->creditMemoRepository = $creditMemoRepository;
    }

    public function upgradeSubscription(Request $request)
    {
        $this->validate($request, [
            'plan_id',
            'payment_method',
            'user_id'
        ]);

        DB::transaction(function () use($request) {

            // Subscribe user to new plan
            $user = User::find($request->user_id);
            $plan = Plan::find($request->plan_id);

            if ($request->bf_discount == true){
                $plan->price = $plan->bf_price;
            }

            if ($plan->is_custom){
                $max_no_of_features = $plan->max_no_of_features;
                if($max_no_of_features!=0) {
                    $validator = Validator::make($request->all(), [
                        'features' => 'required|array|max:'.$max_no_of_features.'|min:'.$max_no_of_features
                    ]);
                }
                else {
                    $validator = Validator::make($request->all(), [
                    ]);
                }

                if ($validator->fails()){
                    alert()->warning($validator->errors()->first(), 'warning');
                    return back();
                }
            }

                // Set the payment method for the user.
                if (! $user->payment_method){
                    $user->fresh()->update(['payment_method' => $request['payment_method']]);
                }
                if($request['payment_method'] == 'debit_order')
                {
                    $user->fresh()->update(['payment_method' => $request['payment_method']]);
                }
                if (! $user->subscribed('cpd')){

                    /* Safety Check */
                    $user->subscriptions->where('name','cpd')->each(function ($subscription){
                        $subscription->delete();
                    });

                    $user->newSubscription('cpd', $plan)->create();
                    $this->saveComprehensiveTopics($request, $plan, $user);

                    $description = 'New CPD Subscription started for '.$plan->name.' (' . ucfirst($plan->interval) . 'ly)';
                    $note = $this->saveUsernote($type = 'new_subscription', $description);
                    $user->notes()->save($note);

                    if ($plan->price > 0){
                        $invoice = $this->invoiceRepository->createSubscriptionInvoice(User::find($request->user_id), $plan);
                        $note->invoice()->associate($invoice);
                        $this->dispatch(new SendEventTicketInvoiceJob($invoice));
                        $user->fresh()->subscription('cpd')->setInvoiceId($invoice);
                        $user->fresh()->subscription('cpd')->suspend();
                    }

                    $viewFile = 'emails.upgrades.free_member_cpd';
                    $whereTo = env('APP_TO_EMAIL');
                    $subject = 'new subscription upgrade processed';
                    $from = config('app.email');

                    $this->dispatch(new sendUpgradeNotification($viewFile, $user->fresh(), $note, $from, $whereTo, $subject, '', $plan));

                    // Set the agent to the new subscription
                    if ($user->fresh()->subscription('cpd')->agent_id == null){
                        $user->fresh()->subscription('cpd')->setAgent(auth()->user());
                    }

                    $this->assignPlanFeatureVideosToUser($user);

                    // Update the user renew date to 1 day before his debit order date.
                    if ($request->payment_method == 'debit_order'){
                        if(!$user->debit)
                        {
                            $this->createDebitOrder($user);
                        }
                        $date = $user->fresh()->debit->getSubscriptionAndBillableDate();

                        $now = Carbon::now();
                        $user->fresh()->subscription('cpd')->starts_at = $now;

                        $endsat = Carbon::parse(($date - 1) . ' ' . date_format(Carbon::parse($now), 'F') . ' ' . Carbon::now()->year)->addMonth(1);
                        $user->fresh()->subscription('cpd')->ends_at = $endsat;
                        $user->fresh()->subscription('cpd')->save();

                        $user->fresh()->debit->update(['bill_at_next_available_date' => true]);
                        $user->fresh()->debit->update(['active' => true]);
                        $user->fresh()->debit->update(['billable_date' => $date]);
                        $user->fresh()->debit->next_debit_date = Carbon::now()->addDay(1)->startOfDay();
                        $user->fresh()->debit->save();
                    }
                }else{
                // Set the old plan.
                $old = Plan::find($user->subscription('cpd')->plan->id);

                if ($user->subscription('cpd')->plan->is_custom){
                    $user->custom_features->delete();
                }

                $user->subscriptions->where('name','cpd')->each(function ($subscription){
                    $subscription->delete();
                });

                $user->newSubscription('cpd', $plan)->create();
                $user = $user->fresh();

                // Change the plans for the user.
                $user->subscription('cpd')->plan_id = $plan->id;
                $user->subscription('cpd')->save();

                $this->saveComprehensiveTopics($request, $plan, $user);
                $this->assignPlanFeatureVideosToUser($user);

                $new = Plan::find($request->plan_id);

                // Save Note for upgrading subscription
                $description = 'CPD subscription upgraded from ' . $old->name . ' (' . ucfirst($old->interval) . 'ly) to ' . $new->name . ' (' . ucfirst($new->interval) . 'ly)';
                $note = $this->saveUserNote($type = 'subscription_upgrade', $description);

                if ($new->price > 0){
                    $invoice = $this->invoiceRepository->createSubscriptionInvoice(User::find($request->user_id), $plan);
                    $note->invoice()->associate($invoice);
                    $this->dispatch((new SendEventTicketInvoiceJob($invoice->fresh())));
                    $user->subscription('cpd')->setInvoiceId($invoice);
                    $user->fresh()->subscription('cpd')->suspend();
                }

                // Update the user renew date to 1 day before his debit order date.
                if ($request->payment_method == 'debit_order'){
                    if(!$user->debit)
                    {
                        $this->createDebitOrder($user);
                    }
                    $date = $user->fresh()->debit->getSubscriptionAndBillableDate();
                    $now = Carbon::now();
                    $user->subscription('cpd')->starts_at = $now;

                    if ($plan->interval == 'month'){
                        $endsat = Carbon::parse(($date - 1) . ' ' . date_format(Carbon::parse($now), 'F') . ' ' . Carbon::now()->year)->addMonth(1);
                    }else{
                        $endsat = Carbon::parse(($date - 1) . ' ' . date_format(Carbon::parse($now), 'F') . ' ' . Carbon::now()->year)->addMonth(1)->addYear(1);
                    }

                    $user->subscription('cpd')->save();

                    if ($old->price <= 0){
                        $user->fresh()->debit->update(['bill_at_next_available_date' => true]);
                    }

                    $user->fresh()->debit->update(['active' => true]);
                    $user->fresh()->debit->update(['billable_date' => $date]);

                    $user->fresh()->debit->next_debit_date = Carbon::now()->addDay(1)->startOfDay();
                    $user->fresh()->debit->save();
                }else{

                    $period = new Period($new->interval, $new->interval_count, Carbon::now());
                    $user->subscription('cpd')->starts_at = $period->getStartDate();
                    $user->subscription('cpd')->ends_at = $period->getEndDate();
                    $user->subscription('cpd')->canceled_at = NULL;
                    $user->subscription('cpd')->save();
                }

                if ($user->fresh()->subscription('cpd')->agent_id == null){
                    $user->fresh()->subscription('cpd')->setAgent(auth()->user());
                }

                $viewFile = 'emails.upgrades.notify_staff';
                $oldPlan = $old;
                $newPlan = $user->fresh()->subscription('cpd')->plan;
                $whereTo = env('APP_TO_EMAIL');
                $subject = 'new subscription upgrade processed';
                $from = config('app.email');
                $this->dispatch(new sendUpgradeNotification($viewFile, $user->fresh(), $note, $from, $whereTo, $subject, $oldPlan, $newPlan));
            }

            $user->notes()->save($note);
            alert()->success("Subscription Upgraded Successfully", 'Success!');
        });
        return redirect()->back();
    }

    protected function assignPlanFeatureVideosToUser($user)
    {
        $videos = Video::whereIn('id',[60,61,62,64])->get();
        $owned_webinars = $user->webinars->pluck('id')->toArray();
        foreach($videos as $video){
            if(!in_array($video->id,$owned_webinars)) {
                $user->webinars()->save($video);
            }
        }
    }

    /*
     * Save the comprehensive topics + adding default topic to comprehensive packages
     */
    public function saveComprehensiveTopics(Request $request, $plan, $user)
    {
        if ($plan->is_custom && !empty($request->features)) {
            $selected = $request->features;
            $default = config('byo_default');

            $features = array_merge($selected, $default);

            $oldFeatures = CustomPlanFeature::where('user_id', $user->id)->get();

            if ($oldFeatures){
                foreach ($oldFeatures as $oldFeature){
                    $oldFeature->delete();
                }
            }

            CustomPlanFeature::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'features' => $features
            ]);
        }
    }

    /*
     * Adding a new note the user profile.
     */
    public function saveUserNote($type, $description)
    {
        $note = new Note([
            'type' => $type,
            'description' => $description,
            'logged_by' => auth()->user()->first_name." ".auth()->user()->last_name,
        ]);
        return $note;
    }
     /**
     * @param $user
     * @return DebitOrder
     */
    private function createDebitOrder($user)
    {
        $debit_order = new DebitOrder([
            'bank' => '',
            'number' => '',
            'type' => '',
            'branch_name' => '',
            'branch_code' => '',
            'billable_date' => '2',
            'peach'=>1,
            'id_number' => $user->id_number,
            'registration_number' => '',
            'account_holder' => ucfirst($user->first_name) . ' ' . ucfirst($user->last_name),
            'type_of_account' => '',
        ]);
        $debit_order->user()->associate($user);
        $debit_order->save();
        // return $debit_order;
    }
}
