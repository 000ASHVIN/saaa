<?php
namespace App\Http\Controllers\Admin;
use DB;
use App\Note;
use Carbon\Carbon;
use App\Users\User;
use App\InvoiceOrder;
use App\Http\Requests;
use App\InvoiceOrderPayment;
use Illuminate\Http\Request;
use App\Billing\InvoiceTracking;
use App\Jobs\SubscribeUserToPlan;
use App\Subscriptions\Models\Plan;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Subscriptions\Models\Period;
use App\Subscriptions\Models\SubscriptionGroup;
use App\Repositories\InvoiceOrder\InvoiceOrderRepository;
use App\Repositories\InvoiceOrder\SendInvoiceOrderRepository;
use App\Video;

class InvoiceOrderController extends Controller
{
    private $invoiceOrderRepository;
    private $sendInvoiceOrderRepository;
    public function __construct(InvoiceOrderRepository $invoiceOrderRepository,SendInvoiceOrderRepository $sendInvoiceOrderRepository)
    {
        $this->invoiceOrderRepository = $invoiceOrderRepository;
        $this->sendInvoiceOrderRepository = $sendInvoiceOrderRepository;
    }

    public function getAllocate($id)
    {
        $order = InvoiceOrder::with('payments')->find($id);
        return view('admin.orders.allocate', compact('order'));
    }

    public function postAllocate(Request $request, $id)
    {
        $order = InvoiceOrder::find($id);
        $staff = $request->staffid;
        try{
            DB::transaction(function () use($order, $request){

                if ($order->balance == $request['amount']){
                    $this->paymentAllocation($request, $order, $amount = $request['amount']);

                }elseif($request['amount'] > $order->balance){
                    $remainder = $request['amount'] - $order->balance;
                    $this->paymentAllocation($request, $order, $amount = $order->balance);
                    $order->user->wallet->add($remainder, $request['method'], Carbon::now(), null, '#'.$order->fresh()->reference);

                }elseif ($request['amount'] <= $order->balance){
                    $this->paymentAllocation($request, $order, $amount = $request['amount']);
                }

                $order->autoUpdateBalance();
                $order->autoUpdateStatus();
                $order->save();

                if ($order->balance <= 0){
                    $invoice = $order->convert();
                    foreach ($order->fresh()->payments->where('tags', 'payment') as $payment){
                        $this->allocatePayment($invoice, $payment['amount'], $request['description'], Carbon::parse($payment['date_of_payment']), $payment['method']);
                        continue;
                    }

                    if($order->type=='webinar') {
                        $video_ids = $order->items->pluck('item_id')->toArray();
                        $videos = Video::find($video_ids);
                        $this->allocateWebinars($order, $videos);
                        $invoice->type="store";
                        $invoice->save();
                    } 
                    
                    if($order->items->count()>0 && $order->type=='subscription'){
                        $plan = Plan::where('id',$order->items[0]->item_id)->first();
                        $user = $order->user;
                        $this->assignPlan($order,$plan,$invoice);
                        
                    }
                   
                 
                    $invoice->fresh()->settle();
                    $invoice->fresh()->autoUpdateAndSave();
                    $invoice->update([ 'balance' => $invoice->balance ]);
                }
            });
        }catch (\Exception $exception){
            alert()->error('Something went wrong', 'Oops!');
            return back();
        }
        alert()->success('Your payment was allocated successfully', 'Success!');
        return back();
    }
    public function assignPlan($order,$plan,$invoice)
    {
            $user = User::find($order->user_id);
            $this->allocateSubscription($user,$plan,$invoice);
           
            //if(!empty($staff)) 
            //{
                // foreach($staff as $s){
                //     $users = User::find($s);
                //     $this->allocateSubscription($users,$plan,$invoice);
                //     $subscription_id = $users->subscription('cpd')->id;
                //     $subscriptionGroup = SubscriptionGroup::create([
                //         'user_id'=> $s,
                //         'admin_id'=> $user->id,
                //         'plan_id'=> $plan->id,
                //         'pricing_group_id'=> 0,
                //         'subscription_id'=> @$subscription_id
                //     ]);
                // }
            //}
    }

    public function allocateSubscription($user,$plan,$invoice)
    {
        
        if (! $user->subscribed('cpd')){

            /* Safety Check */
            $user->subscriptions->where('name','cpd')->each(function ($subscription){
                $subscription->delete();
            });

            $user->newSubscription('cpd', $plan)->create();
           
            $description = 'New CPD Subscription started for '.$plan->name.' (' . ucfirst($plan->interval) . 'ly)';
            $note = $this->saveUsernote($type = 'new_subscription', $description);
            $user->notes()->save($note);

            if ($plan->price > 0){
                $note->invoice()->associate($invoice);
                $user->fresh()->subscription('cpd')->setInvoiceId($invoice);
            }

            $viewFile = 'emails.upgrades.free_member_cpd';
            $whereTo = env('APP_TO_EMAIL');
            $subject = 'new subscription upgrade processed';
            $from = config('app.email');

           // $this->dispatch(new sendUpgradeNotification($viewFile, $user->fresh(), $note, $from, $whereTo, $subject, '', $plan));

            // Set the agent to the new subscription
            if ($user->fresh()->subscription('cpd')->agent_id == null){
                $user->fresh()->subscription('cpd')->setAgent(auth()->user());
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

        // Change the plans for the user.
        $user->newSubscription('cpd', $plan)->create();
        $user = $user->fresh();
        // $user->subscription('cpd')->plan_id = $plan->id;
        // $user->subscription('cpd')->save();

        //$this->saveComprehensiveTopics($request, $plan, $user);

        $new = $plan;

        // Save Note for upgrading subscription
        $description = 'CPD subscription upgraded from ' . $old->name . ' (' . ucfirst($old->interval) . 'ly) to ' . $new->name . ' (' . ucfirst($new->interval) . 'ly)';
        $note = $this->saveUserNote($type = 'subscription_upgrade', $description);

        if ($new->price > 0){
            $note->invoice()->associate($invoice);
            $user->subscription('cpd')->setInvoiceId($invoice);
        }

     
            $period = new Period($new->interval, $new->interval_count, Carbon::now());
            $user->subscription('cpd')->starts_at = $period->getStartDate();
            $user->subscription('cpd')->ends_at = $period->getEndDate();
            $user->subscription('cpd')->canceled_at = NULL;
            $user->subscription('cpd')->save();
      
        if ($user->fresh()->subscription('cpd')->agent_id == null){
            $user->fresh()->subscription('cpd')->setAgent(auth()->user());
        }

        $viewFile = 'emails.upgrades.notify_staff';
        $oldPlan = $old;
        $newPlan = $user->fresh()->subscription('cpd')->plan;
        $whereTo = env('APP_TO_EMAIL');
        $subject = 'new subscription upgrade processed';
        $from = config('app.email');
        //$this->dispatch(new sendUpgradeNotification($viewFile, $user->fresh(), $note, $from, $whereTo, $subject, $oldPlan, $newPlan));
    }
    }
    public function allocatePayment($invoice, $amount, $description, $date, $method)
    {
        $invoice->transactions()->create([
            'user_id' => $invoice->user->id,
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
            'date' => $date->addSeconds(10)
        ]);
    }
    /**
     * @param Request $request
     * @param $order
     * @param $amount
     */
    public function paymentAllocation(Request $request, $order, $amount)
    {
        $order->payments()->create([
            'tags' => 'payment',
            'description' => $request['description'],
            'amount' => $amount,
            'date_of_payment' => Carbon::parse($request['date_of_payment']),
            'method' => $request['method'],
        ]);
    }

    public function discount(Request $request, $id)
    {
        $order = InvoiceOrder::find($id);
        DB::transaction(function () use($order, $request){

            $order->payments()->create([
                'amount' => $request['amount'],
                'description' => 'Discount #'.$order->reference,
                'date_of_payment' => Carbon::now()->addMinutes(2),
                'method' => '',
                'tags' => 'discount',
            ]);

            $order->update([
                'balance' => $order->total - $order->payments->sum('amount'),
                'discount' => $order->payments->where('tags', 'discount')->sum('amount')
            ]);

            if ($order->balance <= 0){
                $invoice = $order->convert();

                $order->paid = true;
                $order->status = 'paid';
                $order->date_converted = Carbon::now();
                $order->updateOrderBalance();
                $order->releasePendingOrders();
                $order->assignCpdStoreItem();
                $order->save();

                foreach ($order->payments->where('tags', 'payment') as $payment){
                    $this->allocatePayment($invoice, $payment['amount'], $request['description'], Carbon::parse($payment['date_of_payment']), $payment['method']);
                    continue;
                }

                $invoice->fresh()->settle();
                $invoice->fresh()->autoUpdateAndSave();
                $invoice->update([ 'balance' => $invoice->balance ]);
                alert()->success('This order has been settled in full', 'Success!');

            }else{
                alert()->success('Discount has been applied successfully', 'Success!');
            }
        });
        return back();
    }

    public function resend_invoice_order($id)
    {
        $invoiceOrder = InvoiceOrder::find($id);

        // $pdf = \App::make('snappy.pdf.wrapper');
        // $pdf->loadView('orders.view', ['order' => $invoiceOrder]);
        // return $pdf->inline();
        $this->sendInvoiceOrderRepository->sendInvoice($invoiceOrder->user, $invoiceOrder);
   
            
        $note = new Note([
            'type' => 'email',
            'description' => 'I have re-send Order'.' #'.$invoiceOrder->reference.' to the client',
            'logged_by' => auth()->user()->first_name .' '.auth()->user()->last_name,
        ]);
        $invoiceOrder->user->notes()->save($note);

        alert()->success('The Order has been sent to the user successfully!', 'Order Sent!');
        return back();
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

    public function allocateWebinar($order,$v)
    {
        $cpd = $this->assignCPDHours($order->user, $v->hours, 'Webinars On Demand - '.$v->title, null, $v->category, false);
        $this->assignCertificate($cpd, $v);
        $order->user->webinars()->save($v);
    }

    public function allocateWebinars($order,$videos)
    {
        foreach($videos as $video) {

            // Allocate webinars to user
            $allVideos = collect();
            if($video->type=='series') {
                $allVideos = $video->webinars;

                $webinars = $allVideos->pluck('id')->toArray();
                $webinars[] = $video->id;
                $order->user->webinars()->detach($webinars);
                $order->user->webinars()->attach($webinars);
            }
            else {
                $allVideos->push($video);
                $order->user->webinars()->save($video);
            }

            // Assign cpd hours and certificate for all videos
            // foreach($allVideos as $v) {
            //     $cpd = $this->assignCPDHours($order->user, $v->hours, 'Webinars On Demand - '.$v->title, null, $v->category, false);
            //     $this->assignCertificate($cpd, $v);
            // }

        }

    }

    public function assignCPDHours($user, $hours, $source, $attachment, $category, $verifiable)
    {
        $cpd = $user->cpds()->create([
            'date' => Carbon::now(),
            'hours' => $hours,
            'source' => $source,
            'attachment' => $attachment,
            'category' => $category,
            'verifiable' => $verifiable,
        ]);
        return $cpd;
    }

    public function assignCertificate($cpd, $video)
    {
        $video = Video::where('title', 'LIKE', '%'.substr($cpd->source, 28).'%')->get()->first();
        if ($video){
            $cpd->certificate()->create([
                'source_model' => Video::class,
                'source_id' => $video->id,
                'source_with' => [],
                'view_path' => 'certificates.wob'
            ]); 
        }
    }
}
