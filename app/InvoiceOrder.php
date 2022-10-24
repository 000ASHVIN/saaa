<?php

namespace App;

use App\AppEvents\Ticket;
use App\Billing\Creditnote;
use App\Billing\Invoice;
use App\Billing\Item;
use App\Billing\Transaction;
use App\Store\Cart;
use App\Store\Order;
use App\Users\User;
use Carbon\Carbon;
use App\Traits\ActivityTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Course;
use DB;
use App\AppEvents\Event;
use App\Donation;
use App\Repositories\Donation\DonationRepository;

class InvoiceOrder extends Model
{
    use SoftDeletes;
    use ActivityTrait;
    protected $table = 'invoice_orders';
    protected $fillable = [
        'type',
        'discount',
        'vat_rate',
        'sub_total',
        'total',
        'balance',
        'date_converted',
        'paid',
        'status',
        'created_at',
        'updated_at',
        'note_id',
        'expired_at',
        'donation',
        'donation_id',
        'user_id',
        'is_terms_accepted'
    ];

    protected $dates = ['date_converted', 'deleted_at', 'expired_at'];
    protected static function boot()
    {
        parent::boot();
        foreach (['created', 'updated', 'deleted'] as $event) {
            static::$event(function ($model) use ($event) {
                $ActivityLog = ActivityLog::create([
                    'user_id'=> (auth()->check())?auth()->user()->id:0,
                    'model'=> get_class($model),
                    'model_id'=>$model->id,
                    'action_by'=> 'manually',
                    'action'=> $event,
                    'data'=> json_encode(request()->all()),
                    'request_url'=> request()->path()
                ]);
            });
        }
        static::creating(function ($order) {
            // Set Expired Date
            $expireDate = Carbon::now()->addDays(30)->endOfDay();
            $order->expired_at = $expireDate;

            // Set refference
            $reference = $order->getNextReference();
            $order->reference = $reference;
            if ($order->vat_rate === null)
                $order->vat_rate = config('billing.vat_rate', 15);

            if(request()->has('terms') && request()->terms) {
                $order->is_terms_accepted = 1;
            }
        });
    }
    public static function createFromCart($user = null, $shippingMethod = null, $donations = null)
    {
        if (!$user)
            $user = auth()->user();

        $order = new static;
        $order->setUser($user);
        $order->type = 'store';

        if($donations) {
            $order->donation = $donations;
        }

        $order->save();
        $order->addCartItemsAsLineItems();

        if ($shippingMethod)
        $order->createShippingItem($shippingMethod);
        $order->autoUpdateAndSave();

        return $order;
    }
    public function fetchSettlementTotal()
    {
        return $this->total - $this->balance;
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
        $latest = static::withTrashed()->latest('id')->first();
        if ($latest) {
            $ref = (int)$latest->reference += 1;
            return str_pad($ref, 6, '0', STR_PAD_LEFT);
        } else {
            return str_pad(1, 6, '0', STR_PAD_LEFT);
        }
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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'invoice_order_items');
    }

    public function addItems($items)
    {
        return $this->items()->saveMany($items);
    }

    public function addItem($item)
    {
        return $this->addItems([$item]);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function pendingOrders()
    {
        return $this->orders()->where('is_pending', true)->get();
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

            // This needs to be fixed.
            foreach ($this->pendingOrders() as $pendingOrder){
                $pendingOrder['is_pending'] = false;
                $pendingOrder->save();
            }
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
            $this->update(['discount' => $discount]);
            $this->save();
            $this->payments()->create([
                'tags' => 'discount',
                'amount' => $discount,
                'description' => 'Discount #'.$this->reference,
                'date_of_payment' => Carbon::now()
            ]);
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
        $this->balance = $this->total - $this->payments->sum('amount');
    }

    public function updateOrderBalance()
    {
        $this->balance = 0;
    }

    public function autoUpdateStatus()
    {
        $this->status = 'unpaid';
        if ($this->cancelled)
            $this->status = 'cancelled';
        else if ($this->total <= 0 || $this->balance <= 0) {
            $this->paid = true;
            $this->status = 'paid';
        } else if ($this->balance > 0)
            $this->status = 'unpaid';
    }

    /**
     * @return void
     */
    public function autoUpdateAndSave()
    {
        $this->autoUpdateDiscount();
        $this->autoUpdateTotal();
        $this->autoUpdateBalance();
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

    public function settle($method, $description)
    {
        $this->paid = true;
        $this->status = 'paid';
        $this->date_converted = Carbon::now();
        $this->allocatepayment($method, $description);
        $this->updateOrderBalance();
        $this->releasePendingOrders();
        $this->assignCpdStoreItem();
        $this->save();
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class)->withTrashed();
    }

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(InvoiceOrderPayment::class)->orderBy('created_at', 'desc');

    }

    public function allocatepayment($method, $description)
    {
        if ($this->total - $this->payments->sum('amount') > 0){
            $this->payments()->create([
                'amount' => $this->total - $this->discount,
                'description' => $description,
                'date_of_payment' => Carbon::now()->addMinutes(2),
                'method' => $method,
                'tags' => 'payment',
            ]);
        }
    }

    /*
     * Conver the Order to Invoice.
     */
    public function convert()
    {
        /*
         * Check if order contains a discount
         */
        if ($this->discount > 0){
            $discount = $this->discount;
        }else{
            $discount = 0;
        }

        $this->donation_id = 0;
        if($this->donation>0 && !$this->donation_id) {
            $user = $this->user;

            $donationRepository = new DonationRepository();
            $donate = $donationRepository->createForUserAndNotify($user, $this->donation);

            $this->donation_id = $donate->id;
            $this->save();
        }

        /*
         * Create a new invoice for the user
         */
        $invoice = $this->user->invoices()->create([
            'discount' => $discount,
            'vat_rate' => 15,
            'sub_total' => $this->sub_total,
            'total' => $this->total,
            'balance' => $this->total - $this->discount,
            'paid' => true,
            'status' => $this->status,
            'type' => $this->type,
            'donation_id' => $this->donation_id,
            'donation' => $this->donation,
            'is_terms_accepted' => $this->is_terms_accepted,
        ]);

        /*
         * Add all the current order items to new Invoice
         */
        $invoice->addItems($this->items);

        /*
         * Assign the invoice to this order
         */
        $this->invoice()->associate($invoice);

        if($invoice->type == 'course')
        {
            foreach($invoice->items as $item){
                $productData = $item->productable;
                if(get_class($productData) == get_class(new Course()))
                {
                    if($productData->exclude_vat == 1){
                        $invoice->vat_rate = 0;
                        $invoice->save();
                    }
                }
            }
        }
        
        /*
         * Save the invoice_id to the Note.
         */
        if ($this->note_id){
            $note = Note::find($this->note_id);
            $note->update([
                'invoice_id' => $this->invoice_id
            ]);
        }

        /*
         * If Purchase Order has Ticket
         */
        if ($this->ticket){
            $this->ticket->update(
                ['invoice_id' => $invoice->id]
            );
        }

        if($this->type=='event')
        {
            foreach($this->items as $item)
            {
                if($item->item_type == get_class(new Event())){
                    $ticket = Ticket::onlyTrashed()->where('user_id',$this->user_id)->where('event_id',$item->item_id)->first();
                    if($ticket){
                        $ticket->restore();
                    }
                }
            }
        }

        if($this->type == 'course'){
            $array =  ['110','115'];
            $course = $this->items->where('type','course')->first();
            if($course && $course->item_type==get_class(new course())){
                $Course_id = Course::where('id',$course->item_id)->first();
                if($Course_id){
                    DB::table('course_invoice')->insert([
                        'course_id' => $Course_id->id,
                        'invoice_id' => $invoice->id
                    ]);
                    if($Course_id->type_of_course == 'semester'){
                        if($course->course_type == 'monthly' && $Course_id->order_price>0)
                        {
                            if(!in_array($Course_id->id,$array)){
                            $this->allocateOrder($Course_id,$course->course_type);  
                            }
                        }
                    } 
                }
            }
        }
        
        /*
         * Create a new debit transaction for this invoice
         */
        if($invoice->type != 'subscription' && $this->type != 'webinar'){
            $invoice->fresh()->transactions()->create([
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

        /*
         * Check the discount, and if we have discount, Let's create a credit note for this Invoice
         */
        if($discount > 0)
        {
            Transaction::create([
                'user_id' => $invoice->user->id,
                'invoice_id' => $invoice->id,
                'type' => 'credit',
                'display_type' => 'Credit Note',
                'status' => 'Closed',
                'category' => $invoice->type,
                'amount' => $discount,
                'ref' => $invoice->reference,
                'method' => 'Applied',
                'description' => "Invoice #{$invoice->reference} discount",
                'tags' => "Discount",
                'date' => $invoice->created_at->addSeconds(10)
            ]);
        }
        return $invoice;
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

    public function cancel()
    {
        $this->paid = false;
        $this->status = 'cancelled';
        $this->date_converted = Carbon::now();
        $this->balance = 0;
        $this->save();
    }

    public function donation()
    {
        return $this->belongsTo(Donation::class);
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

}