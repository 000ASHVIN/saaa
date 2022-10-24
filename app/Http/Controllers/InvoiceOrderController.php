<?php
namespace App\Http\Controllers;
use App\Billing\CreditCardBillingRepository;
use App\Billing\Invoice;
use App\Card;
use App\InvoiceOrder;
use App\Jobs\SendEventTicketInvoiceJob;
use App\Note;
use App\Peach;
use App\Repositories\Invoice\SendInvoiceRepository;
use App\Repositories\InvoiceOrder\InvoiceOrderRepository;
use App\Repositories\WalletRepository\WalletRepository;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\Orders\PdfGenerateInvoiceOrder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Subscriptions\Models\Plan;
use App\Jobs\SubscribeUserToPlan;
use Illuminate\Http\Request;
use App\Users\User;
use App\Subscriptions\Models\SubscriptionGroup;
use App\Video;
use App\Billing\Item;
use Carbon\Carbon;
use App\Models\Course;
use App\AppEvents\Event;
use App\AppEvents\Pricing;
use App\AppEvents\Venue;
use App\AppEvents\Ticket;
use App\AppEvents\DietaryRequirement;
use App\AppEvents\Extra;
use App\AppEvents\Date;

class InvoiceOrderController extends Controller
{
    protected $generate;
    protected $creditCardBillingRepository;
    protected $peach;
    private $walletRepository;
    private $sendInvoiceRepository;
    private $invoiceOrderRepository;
    private $pricingGroup;
    public function __construct(PdfGenerateInvoiceOrder $generate, CreditCardBillingRepository $creditCardBillingRepository, Peach $peach, WalletRepository $walletRepository, SendInvoiceRepository $sendInvoiceRepository, InvoiceOrderRepository $invoiceOrderRepository)
    {
        $this->peach = $peach;
        $this->pricingGroup = 0;
        $this->generate = $generate;
        $this->walletRepository = $walletRepository;
        $this->sendInvoiceRepository = $sendInvoiceRepository;
        $this->invoiceOrderRepository = $invoiceOrderRepository;
        $this->creditCardBillingRepository = $creditCardBillingRepository;
    }
    public function show($id)
    {
        $order = InvoiceOrder::find($id);
        $pdf = App::make('snappy.pdf.wrapper');
        $pdf->loadView('orders.view', compact('order'));
        return $pdf->inline();
    }

    public function getSettle($id)
    {
        if(request()->has('threeDs'))
            $this->handleThreeDs(request());

        $order = InvoiceOrder::find($id);
        if ($order->balance <= 0) {
            alert()->error('You cannot settle an invoice with no outstanding balance', 'Error');
            return redirect()->route('dashboard');
        }

        if ($order->user->id != auth()->user()->id && !auth()->user()->hasRole('admin')) {
            return redirect()->route('dashboard');
        }

        $business = Plan::whereIn('package_type', ['business','individual','trainee'])->get();
        $individual = Plan::where('package_type', 'individual')->get()->sortBy('interval');
        $trainee = Plan::where('package_type', 'trainee')->get()->sortBy('interval');

        $paymentsTotal = $order->total - $order->balance ;
        return view('orders.settle', compact('order', 'paymentsTotal','business'));
    }

    public function postSettle(Requests\SettleInvoiceRequest $request, $id)
    {
        $order = InvoiceOrder::findOrFail($id);
      
        

        $plan = New Plan();
        if($order->items->count()>0 && $order->type=='subscription'){
            $plan = Plan::where('name',$order->items[0]->name)->first();
            $plan->pricingGroup = $plan->pricingGroup;
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
        
        // if ($order->type=='subscription' && count($request->staff) > 0) {
        //     $staff = $request->staff;
        //     $this->subscriptionAssign($request, $staff,$plan);
        // }
        DB::transaction(function () use ($order, $request,$plan) {

            if ($request->paymentOption == 'eft') {
                $invoice = $this->invoiceOrderRepository->processCharge($order, 'instant_eft', 'Instant EFT Payment');
                $this->dispatch((new SendEventTicketInvoiceJob($invoice->fresh())));
                alert()->success('Your Order has been settled.', 'Success');
                if($order->type=='webinar')
                {
                  // $this->allocateWebinar($order->user,$order,$v);
                   $invoice->type="store";
                   $invoice->save();
                } 
                if ($order->type=='subscription' && $plan && $plan->is_practice) {
                    $staff = $request->staff;
                    $this->subscriptionAssign($request, $staff,$plan);
                }
                $this->allocateProduct($invoice);
                return response()->json(['message' => 'success'], 200);
            }

            if ($request->paymentOption == 'cc') {
                $card = Card::find($request->card);
                $payment = $this->peach->charge(
                    $card->token,
                    $order->total - $order->discount,
                    'Order #' . $order->reference,
                    $order->reference
                );

                if (preg_match("/^(000\.000\.|000\.100\.1|000\.[36])/", $payment['result']['code']) === 1) {
                    $invoice = $this->invoiceOrderRepository->processCharge($order, 'cc', 'Credit Card Payment');
                    $this->dispatch((new SendEventTicketInvoiceJob($invoice->fresh())));
                    if($order->type=='webinar')
                    {
                        $user = $order->user;
                         $video = $order->items[0]->item_id;
                        $v = Video::find($video);
                       $this->allocateWebinar($user,$order,$v);
                       $invoice->type="store";
                       $invoice->save();
                    } 
                    if ($order->type=='subscription' && $plan && $plan->is_practice) {
                        $staff = $request->staff;
                        $this->subscriptionAssign($request, $staff,$plan);
                    }
                    $this->allocateProduct($invoice);
                    alert()->success('Your Order has been settled.', 'Success');
                    return response()->json(['message' => 'success'], 200);
                } else {
                    return response()->json([
                        'errors' => [
                            'card' => $payment['result']['description']
                        ]
                    ], 422);
                }
            }
           
            if ($request->paymentOption == 'wallet') {
                $this->walletRepository->payInvoice(auth()->user()->id, $order->fresh()->id);
                $this->invoiceOrderRepository->sendInvoiceOrder(auth()->user(), $order->fresh());
                if($order->type=='webinar')
                {
                 //  $this->allocateWebinar($user,$order,$v);
                   $invoice->type="store";
                   $invoice->save();
                } 
                if ($order->type=='subscription' && $plan && $plan->is_practice) {
                    $staff = $request->staff;
                    $this->subscriptionAssign($request, $staff,$plan);
                }
                return response()->json(['message' => 'success'], 200);
            } else {
                return response()->json([
                    'errors' => 'Not Enought Credit'
                ], 422);
            }
            return "Done";
        });
    }

      // Allocate as per class
      public function allocateProduct($invoice)
      {
          $invoiceData = $invoice->items;
          foreach($invoiceData as $item){
              $productData = $item->productable;
              $extra_details = json_decode($item->extra_details);
              if(isJson($item->extra_details)){
              if(get_class($productData) == get_class(new Video()))
              {
                  $this->allocateVideo($invoice,$productData);
              }
              else if(get_class($productData) == get_class(new Course()))
              {
                  $this->allocateCourse($invoice,$productData);
              }
              else if(get_class($productData) == get_class(new Event()))
              {
                  $this->allocateEvent($invoice,$productData,$extra_details);
              }
             }
          }
      }
  
      // Allocate Video
      public function allocateVideo($invoice,$productData)
      {
          if(!$invoice->user->webinars->contains($productData->id)){
              $invoice->user->webinars()->save($productData);
          }
      }
  
      // Allocate Course
      public function allocateCourse($invoice,$productData)
      {
          $user = $invoice->user;
          if(!$user->courses->contains($productData->id)){
              $user->courses()->save($productData);
          }
      }
  
      // Allocate Event
      public function allocateEvent($invoice,$productData,$extra_details)
      {
          $user = $invoice->user;
          if(!$user->isRegisteredForEvent($productData)){
              return true;
          }
        //   dd($extra_details);
          $event = $productData;
          $venue = Venue::findOrFail($extra_details->venue_id);
          $pricing = Pricing::where('event_id', $event->id)->where('venue_id', $venue->id)->first();
          $order = collect();
          if($extra_details->order_id)
          {
              $order = InvoiceOrder::find($extra_details->order_id);
          }
          // Create Event Ticket
          $ticket = $this->createTicket($user,$extra_details, $pricing,$order);
          // $ticket = $this->createTicket($extra_details, $pricing, $order);
  
           // Set Extras
          if (count($extra_details->get('extras', [])) >= 1) {
              $this->setExtras($ticket, $order, $extra_details);
          }
  
          // Set Dietary
          if ($extra_details->dietary > 0) {
              $this->setDietary($order, $extra_details->dietary, $extra_details->dates);
          }
          $this->setDates($extra_details->dates, $ticket);
          
      }
      public function setDates($dates, $ticket)
      {
          foreach ($dates as $date) {
              $toAdd = Date::find($date['id']);
              $ticket->dates()->attach($toAdd);
          }
      }
  
      public function setExtras($ticket, $order, $request)
      {
          foreach ($request->extras as $extra) {
              $toAdd = Extra::find($extra['id']);
              if ($toAdd->price > 0)
                  $this->addExtraToInvoice($toAdd, $order, $ticket->event->name);
              $ticket->extras()->attach($toAdd);
          }
      }
  
      public function setDietary($order, $dietary, $dates)
      {
          $multiplier = count($dates);
          $toAdd = DietaryRequirement::find($dietary);
          if ($toAdd->price > 0) {
              for ($i=1; $i <= $multiplier; $i++) {
                  $this->addDietaryToInvoice($toAdd, $order);
              }
          }
      }
  
      // Generate a new ticket
      public function createTicket($user,$extra_details, $pricing,$order)
      {
          $ticket = new Ticket;
          $ticket->code = str_random(20);
          $ticket->name = $pricing->name;
          $ticket->description = $pricing->description;
          $ticket->first_name = $user->first_name;
          $ticket->last_name = $user->last_name;
          $ticket->user_id = $user->id;
          $ticket->event_id = $extra_details->event;
          $ticket->venue_id = $extra_details->venue;
          $ticket->pricing_id = $extra_details->pricing;
          $ticket->invoice_order_id = $order->id;
          $ticket->dietary_requirement_id = $extra_details->dietary;
          $ticket->invoice_order_id = '111';
          $ticket->dietary_requirement_id = '1';
          $ticket->email = $user->email;
          $ticket->save();
          return $ticket;
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

    public function cancel($id)
    {
        $order = InvoiceOrder::find($id);

        if ($order->type == 'event'){

            DB::transaction(function () use($order) {
                $this->saveCancellationNote($order);
                $order->ticket->delete();
                $order->cancel();
            });

            alert()->success('Order has been cancelled', 'Success!');
            return back();

        }else{
            DB::transaction(function () use($order) {
                $this->saveCancellationNote($order);
                $storeOrder = $order->orders;
                foreach ($storeOrder as $storeO){
                    $storeO->delete();
                }
                $order->cancel();
            });
            alert()->success('Order has been cancelled', 'Success!');
            return back();
        }
    }

    /**
     * @param $order
     */
    public function saveCancellationNote($order)
    {
        $note = new Note([
            'type' => 'general',
            'description' => auth()->user()->first_name . ' ' . auth()->user()->last_name . ' has cancelled order #' . $order->reference,
            'logged_by' => auth()->user()->first_name . ' ' . auth()->user()->last_name,
        ]);
        $order->user->notes()->save($note);
    }
    protected function subscriptionAssign($request, $staffs,$plan)
    {
        $userMain = auth()->user();

        if ($userMain && $userMain->subscription('cpd')) {
            $agent = User::find($userMain->subscription('cpd')->agent_id);
        }

        $userMain->subscriptions->where('name', 'cpd')->each(function ($subscription) {
            $subscription->delete();
        });
        $subscriptions = $this->dispatchFromArray(SubscribeUserToPlan::class, [
            'user_id' => $userMain->id,
            'plan' => $plan->id,
            'features' => $request->features,
            'payment' => $request->paymentOption,
        ]);


        // if (@$agent && @$userMain->fresh()->subscription('cpd')->agent_id == null) {
        //     $user->fresh()->subscription('cpd')->setAgent($agent);
        // }
        
        if(count($staffs)>0){
            foreach ($staffs as $staff) {
                $user = User::find($staff);

                if ($user && $user->subscription('cpd')) {
                    $agent = User::find($user->subscription('cpd')->agent_id);
                }

                $user->subscriptions->where('name', 'cpd')->each(function ($subscription) {
                    $subscription->delete();
                });
                $this->subscribeMember($request, $staff,$plan);

                if (@$agent && @$user->fresh()->subscription('cpd')->agent_id == null) {
                    $user->fresh()->subscription('cpd')->setAgent($agent);
                }
            }
        }
    }
    /**
     * Subscribe Member to Selected Plan.
     */
    protected function subscribeMember(Request $request, $member,$plan)
    {
        $subscriptions = $this->dispatchFromArray(SubscribeUserToPlan::class, [
            'user_id' => $member,
            'plan' => $plan->id,
            'features' => $request->features,
            'payment' => $request->paymentOption,
        ]);

       
        $subscriptionGroup = SubscriptionGroup::create([
            'user_id'=> $member,
            'admin_id'=> auth()->user()->id,
            'plan_id'=> $plan->id,
            'pricing_group_id'=> $this->pricingGroup,
            'subscription_id'=> @$subscriptions->id
        ]);
    }

    public function generateInvoice($user, $video, $request)
    {
        $invoice = new Invoice;
        $invoice->type = 'webinar';
        $invoice->setUser($user);
        if($request->company)
        {
            $invoice->company_id = $request->company;
        }
        $invoice->save();

        $item = new Item;
        $item->type = 'webinar';
        $item->name = $video->title;
        $item->description = 'Webinar On-Demand';
        $item->price = $video->amount;
        $item->discount = '0';
        $item->item_id = $video->id;
        $item->item_type = get_class($video);
        $item->save();

        $this->products[] = $item;
        return $invoice;
    }


    public function allocateWebinar($user,$order,$v)
    { 
        $cpd = $this->assignCPDHours($user, $v->hours, 'Webinars On Demand - '.$v->title, null, $v->category, false);
        $this->assignCertificate($cpd, $v);
        $user->webinars()->save($v);
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