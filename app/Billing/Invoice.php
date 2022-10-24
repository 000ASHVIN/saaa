<?php

namespace App\Billing;

use App\AppEvents\Ticket;
use App\Billing\Creditnote;
use App\Billing\Transaction;
use App\Coupon;
use App\CreditMemo;
use App\Installment;
use App\Note;
use App\Store\Cart;
use App\Store\Discount;
use App\Store\Order;
use App\Store\ProductListing;
use App\Store\ShippingInformationStatus;
use App\Subscriptions\Models\Subscription;
use App\Users\User;
use App\Video;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mockery\Exception;
use App\Donation;
use App\Repositories\Donation\DonationRepository;
use App\Traits\ActivityTrait;
use App\ActivityLog;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\InvoiceOrder;
use App\CourseProcess;

/**
 * Class Invoice
 * @package App\Billing
 */
class Invoice extends Model
{
    use SoftDeletes;
    use ActivityTrait;

//    protected $appends = ['total_due', 'invoice_discount'];

    /**
     * @var string
     */
    protected $table = 'invoices';

    /**
     * @var array
     */
    protected $fillable = [
        'type',
        'reference',
        'discount',
        'vat_rate',
        'sub_total',
        'total',
        'balance',
        'date_settled',
        'paid',
        'cancelled',
        'status',
        'created_at',
        'ptp_date',
        'donation',
        'donation_id',
        'user_id',
        'is_terms_accepted'
    ];

    /**
     * @var array
     */
    protected $dates = ['date_settled', 'deleted_at'];

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $reference = $invoice->getNextReference();
            $invoice->reference = $reference;
            if ($invoice->vat_rate === null)
                $invoice->vat_rate = config('billing.vat_rate', 15);

            if(request()->has('terms') && request()->terms) {
                $invoice->is_terms_accepted = 1;
            }
        });

        static::created(function ($invoice) {
            $ActivityLog = ActivityLog::create([
                'user_id'=> (auth()->check())?auth()->user()->id:0,
                'model'=> get_class($invoice),
                'model_id'=>$invoice->id,
                'action_by'=> 'manually',
                'action'=> 'created',
                'data'=> json_encode(request()->all()),
                'request_url'=> request()->path()
            ]);
            if($invoice->type != 'store' && $invoice->type != 'course' && $invoice->type != 'event')
            {
                $invoice->transactions()->create([
                    'user_id' => $invoice->user->id, 
                    'invoice_id' => $invoice->id, 
                    'type' => 'debit',
                    'display_type' => 'Invoice', 
                    'status' => ($invoice->paid) ? 'Closed' : 'Open',
                    'category' => $invoice->type,
                    'amount' => $invoice->total, 
                    'ref' => $invoice->reference, 
                    'method' => 'Void', 
                    'description' => "Invoice #{$invoice->reference}", 
                    'tags' => "Invoice", 
                    'date' => $invoice->created_at
                ]);
            }
            CourseProcess::convertLeadFromInvoice($invoice->fresh());
        });

        static::updated(function ($invoice) {

            $ActivityLog = ActivityLog::create([
                'user_id'=> (auth()->check())?auth()->user()->id:0,
                'model'=> get_class($invoice),
                'model_id'=>$invoice->id,
                'action_by'=> 'manually',
                'action'=> 'updated',
                'data'=> json_encode(request()->all()),
                'request_url'=> request()->path()
            ]);
            if($invoice->type == 'store')
            {
                $hasNoCapturedPayments = is_null($invoice->transactions()->where('type', 'debit')->where('tags', 'Invoice')->first());

                if($hasNoCapturedPayments)
                {
                    $invoice->transactions()->create([
                        'user_id' => $invoice->user->id,
                        'invoice_id' => $invoice->id,
                        'type' => 'debit',
                        'display_type' => 'Invoice',
                        'status' => ($invoice->paid) ? 'Closed' : 'Open',
                        'category' => $invoice->type,
                        'amount' => $invoice->total,
                        'ref' => $invoice->reference,
                        'method' => 'Void',
                        'description' => "Invoice #{$invoice->reference}",
                        'tags' => "Invoice",
                        'date' => $invoice->created_at
                    ]);
                }
            }
            $owes = $invoice->fresh()->transactions->where('type', 'debit')->sum('amount') - $invoice->fresh()->transactions->where('type', 'credit')->sum('amount');
            if($invoice->status != 'unpaid' && $owes <= 0) {
                $invoice->status = 'paid';
                $invoice->paid = 1; 
            }
        });
    }

    /**
     * @param null $user
     * @param null $shippingMethod
     * @return Invoice
     */
    public static function createFromCart($user = null, $shippingMethod = null)
    {
        if (!$user)
            $user = auth()->user();

        $invoice = new static;
        $invoice->setUser($user);
        $invoice->type = 'store';
        $invoice->save(); //needed so that cart items can be added
        $invoice->addCartItemsAsLineItems();
        if ($shippingMethod)
            $invoice->createShippingItem($shippingMethod);
        $invoice->autoUpdateAndSave();
        return $invoice;
    }

    public function fetchSettlementTotal()
    {
        return $this->total - $this->transactions->where('type', 'credit')->sum('amount');
    }

    public function isPaid()
    {
        return $this->fetchSettlementTotal() <= 0;
    }

    /**
     * Return Unpaid Invoices
     *
     * @param $query
     * @return mixed
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid')
                    ->where('status', 'partial')
                     ->where('paid', false);
    }

    /**
     * @return string
     */
    public function getNextReference()
    {
        $latest = static::latest('id')->first();

        if ($latest) {
            $ref = (int)$latest->reference += 1;
            return str_pad($ref, 6, '0', STR_PAD_LEFT);
        } else {
            return str_pad(1, 6, '0', STR_PAD_LEFT);
        }
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function setUser($user)
    {
        return $this->user()->associate($user);
    }

    public function getBalanceAttribute()
    {
        return $this->attributes['balance'] = ($this->transactions()->whereType('debit')->sum('amount') - $this->transactions()->whereType('credit')->sum('amount')) / 100;
     }

    public function getBalanceStaticAttribute() {

        $debit = 0;
        $credit = 0;

        foreach($this->transactions as $transaction) {
            if($transaction->type == 'debit') {
                $debit +=  $transaction->amount; 
            }

            if($transaction->type == 'credit') {
                $credit +=  $transaction->amount; 
            }
        }

        return $debit - $credit;

    }

//    public function getTotalDueAttribute()
//    {
//        return $this->attributes['total_due'] = $this->transactions->where('type', 'debit')->sum('amount') - $this->transactions->where('type', 'credit')->sum('amount');
//    }
//
//    public function getInvoiceDiscountAttribute()
//    {
//        return $this->attributes['invoice_discount'] = $this->transactions->where('type', 'credit')->where('tags', 'Discount')->sum('amount');
//    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'items_lists');
    }

    public function addItems($items)
    {
        return $this->items()->saveMany($items);
    }

    public function addItem($item)
    {
        return $this->addItems([$item]);
    }

    /**
     * @return mixed
     */
    public function payments()
    {
        return $this->hasMany(Payment::class)->orderBy('created_at', 'desc');
    }

    /**
     * @return int
     */
    public function totalPayments()
    {
        $totalPayments = 0;
        $payments = $this->transactions;
        foreach ($payments as $payment) {
            $totalPayments += $payment->amount;
        }
        return $totalPayments;
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function pendingOrders()
    {
        return $this->orders()->where('is_pending', true);
    }

    public function releasePendingOrders()
    {
        $orders = $this->orders()->with(['shippingInformation'])->get();

        if(count($orders))
        {
            foreach ($orders as $order) {
                if ($order->shippingInformation) {
                    $order->shippingInformation->updateStatusBySlug('awaiting-stock');
                    $order->shippingInformation->save();
                }
            }
            $this->pendingOrders()->update(['is_pending' => false]);
        }        
    }
  /*
   * Assign the store product cpd to user.
   */
    public function assignCpdStoreItem()
    {
        $orders = $this->orders;
        try{
            $orders->each(function ($order){
                if ($order->product && $order->product->cpd_hours > 0){
                    $this->user->cpds()->create([
                        'date' => Carbon::now(),
                        'hours' => $order->product->cpd_hours,
                        'source' => $order->product->title,
                        'attachment' => null
                    ]);
                }

                $links = $order->product->links()->where('name', 'Recording')->get();

                if (count($links)){
                    foreach ($links as $link){
                        $video = Video::where('download_link', $link->url)->first();
                        if ($video && ! $this->user->webinars->contains($video)){
                            $this->user->webinars()->save($video);
                        }
                    }
                }
            });
        }catch (Exception $exception){
            return;
        }
    }

    public function autoUpdateDiscount()
    {
        $discount = 0;
        $items = $this->items;
        foreach ($items as $item) {
            $discount += ($item->discount * $item->quantity);
        }

        if($discount > 0)
        {
            if ($this->fresh()->transactions->where('type', 'debit')->sum('amount') - $this->fresh()->transactions->where('type', 'credit')->sum('amount') > 0){
                Transaction::create([
                    'user_id' => $this->user->id,
                    'invoice_id' => $this->id,
                    'type' => 'credit',
                    'display_type' => 'Credit Note',
                    'status' => 'Closed',
                    'category' => $this->type,
                    'amount' => $discount,
                    'ref' => $this->reference,
                    'method' => 'Applied',
                    'description' => "Invoice #{$this->reference} discount",
                    'tags' => "Discount",
                    'date' => $this->created_at->addSeconds(10)
                ]);
            }
        }
    }

    /**
     * @return void
     */
    public function autoUpdateTotal()
    {
        $total = 0;
        $items = $this->items;
        foreach ($items as $item) {
            $total += ($item->price * $item->quantity);
        }

        if($this->donation) {
            $total += $this->donation;
        }

        $this->sub_total = $total;
        $this->total = $total;
    }

    /**
     * @return void
     */
    public function autoUpdateBalance()
    {
        $this->balance = $this->total - $this->totalPayments();
    }

    public function autoUpdateStatus()
    {
        $this->status = 'unpaid';
        if ($this->cancelled)
            $this->status = 'credit noted';
        else if ($this->total - $this->transactions->where('type', 'credit')->sum('amount') <= 0) {
            $this->paid = true;
            $this->status = 'paid';
            $this->assignWebinarVideosToUser();
        } else if ($this->totalPayments() > 0)
            $this->status = 'partial';
    }

    /**
     * @return void
     */
    public function autoUpdateAndSave()
    {
        $this->autoUpdateDiscount();
        // $this->autoUpdateSubTotal();
        $this->autoUpdateTotal();
//         $this->autoUpdateBalance();
        $this->autoUpdateStatus();
        $this->save();
    }

    /**
     * @return void
     */
    public function addCartItemsAsLineItems()
    {
        $this->addItems(Cart::getAllInvoiceItems());
    }

    /**
     * @param $shippingMethod
     * @return null|Item
     */
    public function createShippingItem($shippingMethod)
    {
        $shippingItem = null;
        if ($shippingMethod && $shippingMethod->price > 0) {
            $shippingItem = Item::create([
                'type' => 'shipping',
                'name' => $shippingMethod->title,
                'description' => $shippingMethod->description,
                'price' => $shippingMethod->price,
                'item_id' => $shippingMethod->id,
                'item_type' => get_class($shippingMethod),
            ]);
            $this->addItem($shippingItem);
        }
        return $shippingItem;
    }

    public function credits()
    {
        return $this->hasMany(Creditnote::class)->orderBy('created_at', 'desc');
    }

    /**
     * @param $code
     * @return bool
     */
    public function applyCoupon($code)
    {
        if (!$code)
            return false;

        $coupon = Coupon::whereCode($code)->first();

        if (!$coupon)
            return false;

        $this->discount = $coupon->getDiscount($this->total);
        $this->balance -= $this->discount;

        return $this->save();
    }

    /**
     * @return bool
     */
    public function settle()
    {
        $this->paid = true;
        $this->status = 'paid';
        $this->date_settled = Carbon::now();
        $this->releasePendingOrders();
        $this->assignCpdStoreItem();
        $this->updateSubscriptionOverdueStatus();
        $this->assignWebinarVideosToUser();
        $this->checkDonations();
        $this->enableSubscription();
        $this->save();
    }
    public function checkCourse()
    {
        $array =  ['110','115'];
        if($this->type == 'course'){
            $course = $this->items->where('type','course')->first();
            if($course && $course->item_type==get_class(new course())){
                $Course_id = Course::where('id',$course->item_id)->first();
                if($Course_id){
                    DB::table('course_invoice')->insert([
                        'course_id' => $Course_id->id,
                        'invoice_id' => $this->id
                    ]);
                    if($Course_id->type_of_course == 'semester'){
                        if($course->course_type == 'monthly'  && $Course_id->order_price>0)
                        {
                            if(!in_array($Course_id->id,$array)){
                            $this->allocateOrder($Course_id,$course->course_type);  
                            }
                        }
                    } 
                }
            }
        }
    }
    public function allocateOrder($Course_id,$extra_details)
    {
        $order = new InvoiceOrder();
        $order->type = 'course';
        $order->setUser($this->user);
        $order->save();
        $item = new Item;
        $item->type = 'course';
        $item->name = $Course_id->title;
        $item->description = $Course_id->title;
        $item->price = $Course_id->order_price;
        $item->item_id = $Course_id->id;
        $item->item_type = get_class($Course_id);
        $item->save();
        $order->addItem($item);
        $order->autoUpdateAndSave();
        $description = 'New Course order is generated '.$Course_id->title.' (' . ucfirst(@$extra_details) . ')';
        $note = $this->saveUsernote($type = 'course_subscription', $description);
        $this->user->notes()->save($note);
    }

    public function saveUserNote($type, $description)
    {
        $note = new Note([
            'type' => $type,
            'description' => $description,
            'logged_by' => auth()->user()->first_name." ".auth()->user()->last_name,
        ]);

        return $note;
    }

    public function enableSubscription()
    {
        if($this->items->count())
        {
            foreach($this->items as $item)
            {
                if($item->type == 'subscription'){
                    $subscriptions = Subscription::where('user_id',$this->user_id)->where('plan_id',$item->item_id)->first();
                    if($subscriptions && $subscriptions->suspended())
                    {
                        $subscriptions->unsuspend();
                    }
                }
            }
        }
    }

    public function checkDonations() {
        if($this->donation>0 && !$this->donation_id && $this->user) {
            $donationRepository = new DonationRepository();
            $donate = $donationRepository->createForUserAndNotify($this->user, $this->donation);

            $this->donation_id = $donate->id;
            $this->save();
        }
    }

    public function updateSubscriptionOverdueStatus()
    {
//        if (count($this->installments) > 0) {
//            $installmentSubscriptions = $this->installmentSubscriptions;
//
//            foreach ($installmentSubscriptions as $installmentSubscription) {
//                $installmentSubscription->autoUpdateIsOverdue();
//            }
//        }
    }

    public function installmentSubscriptions()
    {
        return $this->belongsToMany(Subscription::class, 'installments')->withTimestamps();
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function creditmemos()
    {
        return $this->hasMany(CreditMemo::class);
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class)->withTrashed();
    }

    public function note()
    {
        return $this->hasOne(Note::class);
    }

    public function getLineItemsAttribute()
    {
        $str = implode(" ",$this->items->pluck('description')->unique('description')->toArray());
        return $str;
    }

    public function assignWebinarVideosToUser()
    {
        if ($this->type == 'event' && $this->ticket && $this->ticket->pricing){
            $this->user->webinars()->saveMany($this->ticket->pricing->videos);
        }
    }

    public function cancelInvoiceAndCreditNote($invoice, $description)
    {
        $transaction = $invoice->transactions()->create([
            'user_id' => $invoice->user->id,
            'invoice_id' => $invoice->id,
            'type' => 'credit',
            'display_type' => 'Credit Note',
            'status' => 'Closed',
            'category' => $invoice->type,
            'amount' => $invoice->balance,
            'ref' => $invoice->reference,
            'method' => 'Void',
            'description' => "Invoice #{$invoice->reference} cancellation",
            'tags' => "Cancellation",
            'date' => Carbon::now()
        ]);

        $invoice->cancelled = 1;
        $invoice->status = 'credit noted';
        $invoice->save();
        $this->store($transaction);

        $note = new Note([
            'type' => 'credit_note',
            'description' => '# '.$invoice->reference.' '.$description,
            'logged_by' => 'system',
        ]);

        $note->invoice()->associate($invoice);
        $invoice->user->notes()->save($note);
    }
    
    public function store($transaction)
    {
        $user = $transaction->user->id;
        $invoice = $transaction->invoice;
        $reference = 'Invoice #'.$transaction->invoice->reference;

        if ($transaction->amount * 100 != 0){
            $memo = New CreditMemo([
                'user_id' => $user,
                'reference' => $reference,
                'tags' => $transaction->tags,
                'description' => $transaction->description,
                'category' => $transaction->category,
                'amount' => ($transaction->amount * 100),
                'transaction_date' => $transaction->created_at
            ]);

            $invoice->creditmemos()->save($memo);
            return $memo;
        }
    }
    public function isRecurring(){
        try{
        $invoice = Invoice::with('items')->where('id','<',$this->id)->where('type','subscription')->orderBy('id','desc')->first();
        $item = ($this->items->count())?$this->items->where('type','subscription')->first():false;
        
        $recuring =  ($invoice->items->count() && $item)?$invoice->items->where('type','subscription')->where('item_id',$item->item_id)->first():false;
        if($recuring){
            return true;
        }
        return false;
    }catch(\Exception $e){
        return false;
    }
    }

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }

    public function salesNotes() {
        return $this->hasOne(Note::class, 'invoice_id')
            ->whereIn('type', ['subscription_upgrade', 'subscription_upgrade_procedure', 'new_subscription', 'recurring_subscription', 'store_items', 'course_subscription','webinars_on_demand','event_registration']);
    }

    public function getSalesPersonAttribute() {
        return $this->salesNotes ? $this->salesNotes->logged_by : null;
    }

    public function updateSalesPerson($name) {

        $note = $this->salesNotes; 
        if($note) {
            $note->logged_by = $name;
            $note->save();
        }
    }

}