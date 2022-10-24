<?php

namespace App\Users;

use App\PricingGroup;
use App\Assessment;
use App\Blog\Comment;
use App\Blog\Post;
use App\Body;
use App\Card;
use App\Company;
use App\CompanyUser;
use App\CouponUser;
use App\InvoiceOrder;
use App\Models\Course;
use App\Models\Cycle;
use App\Note;
use App\Notes;
use App\Charge;
use App\Rep;
use App\Settings;
use App\SMS;
use App\SupportTicket;
use App\UpgradeFeatures;
use App\UpgradeSubscription;
use App\Video;
use App\Wallet;
use Carbon\Carbon;
use App\DebitOrder;
use App\ImportBatch;
use App\Store\Order;
use App\Store\Product;
use App\Billing\Invoice;
use App\AppEvents\Ticket;
use App\CustomDebitOrders;
use App\CustomEftPayments;
use App\Activities\Activity;
use App\Assessments\Attempt;
use App\Billing\Transaction;
use App\SuspendNotification;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use App\Subscriptions\Traits\PlanSubscriber;
use Bican\Roles\Traits\HasRoleAndPermission;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Subscriptions\Models\CustomPlanFeature;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Bican\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;
use Illuminate\Support\Facades\Storage;
use Nicolaslopezj\Searchable\SearchableTrait;
use DB;
use App\Models\CouponDiscount;

use App\Subscriptions\Models\Plan;
use App\AgentGroup;
use App\Thread;
use App\SubscriptionUpgrade;
use App\Traits\ActivityTrait;
use App\ActivityLog;
/**
 * Class User
 * @package App\Users
 * @property mixed $cycles
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int $id
 * @property array $settings
 * @property \Carbon\Carbon $deleted_at
 * @property mixed $primary_card
 * @property mixed $cards
 */

class User extends Model implements AuthenticatableContract,
    CanResetPasswordContract,
    HasRoleAndPermissionContract
{
    use Authenticatable, CanResetPassword, SoftDeletes, PlanSubscriber, HasRoleAndPermission, SearchableTrait,ActivityTrait;

    /**
     * @var string
     */
    protected $table = 'users';

    protected $appends = ['availableWallet', 'balance', 'hours'];

    protected $casts = [
        'settings' => 'json',
        'blog_intrests' => 'json',
        'additional_professional_bodies' => 'json'
    ];

    protected $searchable = [
        'columns' => [
            'first_name' => 10,
            'last_name' => 8,
            'email' => 5,
        ]
    ];

    /**
     * @var array
     */
    protected $hidden = ['password', 'remember_token', 'billing_email_address', 'company'];
    /**
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function primaryCard()
    {
        return $this->hasOne(Card::class, 'id', 'primary_card');
    }

    protected $fillable = [
        'first_name',
        'last_name',
        'debt_arrangement',
        'email',
        'password',
        'avatar',
        'status',
        'deleted_at_description',
        'id_number',
        'cell',
        'is_cell_verified',
        'cpd_with_sait',
        'alternative_cell',
        'primary_card',
        'payment_method',
        'body_id',
        'membership_number',
        'membership_verified',
        'suspended_at',
        'body_responded_declined',
        'body_responded_approved',
        'completed',
        'specified_body',
        'settings',
        'blog_intrests',
        'billing_email_address',
        'additional_professional_bodies',
        'payment_arrangement',
        'round_robin_notified',
        'round_robin_notified_date',
        'additional_users',
        'handesk_token',
        'handesk_id',
        'interest',
        'employment',
        'industry',
        'is_merged'
    ];

    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->has('invoice');
    }

    public function getBalanceAttribute()
    {
        return $this->getBalance();
    }

    public function getBalance()
    {
        return $this->transactions()->has('invoice')->where('type', 'debit')->sum('amount') - $this->transactions()->has('invoice')->where('type', 'credit')->sum('amount');
    }

    public function balanceInRands()
    {
        return $this->getBalance() / 100;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * @param $type
     * @return mixed
     */
    public function getAddress($type)
    {
        return $this->addresses()->whereType($type)->first();
    }

    /**
     * @return mixed
     */
    public function cpds()
    {
        return $this->hasMany(Cpd::class)->orderBy('date', 'desc');
    }

      /**
     * @return mixed
     */
    public function activity_log()
    {
        return $this->hasMany(ActivityLog::class)->with(['user'])->latest();
    }
    public function getHoursAttribute()
    {
        return $this->cpds->sum('hours');
    }

    /**
     * @return mixed
     */
    public function activity()
    {
        return $this->hasMany(Activity::class)->with(['user', 'subject'])->latest();
    }

    public function lastLoginActivity(){

        $activity = $this->activity_log->where('user_id',$this->id)->Where('request_url', 'auth/login')->sortByDesc('id')->first();
        if(!$activity)
        {
           $activity = $this->activity_log->where('user_id',$this->id)->sortByDesc('id')->first();
        }
        return ($activity) ? $activity : false;
    }

    public function latestActivities()
    {
        $activity = collect();
        if($this->isPracticePlan()){
            if($this->company){
                foreach($this->company->staff as $staff){
                    $staff->latestActivities()->each(function ($item, $key) use ($activity) {
                        $activity->push($item);
                    });
                
                }
            }

        }

    
        $activity_log = $this->activity_log()->get();

        $activities =$this->activity()->get();

        
        $list = collect([]);


        foreach($activities as $act) {
            $list = $list->push($act);
        }

        foreach($activity_log as $actlog) {
            $list = $list->push($actlog);
        }
        foreach($activity as $acti) {
            $list = $list->push($acti);
        }
        $allData = $list->sortByDesc('created_at');

        return $allData;
    }

    /**
     * @param $request
     */
    public function updateProfile($request)
    {
        $cell = $request->full_number;
        $this->update($request->only('first_name', 'last_name'));
        $this->profile()->update($request->only(
            'position',
            'cell',
            'company',
            'tax',
            'website',
            'about_me',
            'id_number',
            'area',
            'province',
            'npo_registration_number'
        ));
        $this->profile->cell = $cell;
        $this->profile->save();
    }

    /**
     * @param $name
     * @param $related
     * @return mixed
     */
    public function recordActivity($name, $related)
    {
        return $related->recordActivity($name);
    }

    /**
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function charges()
    {
        return $this->hasMany(Charge::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function debit()
    {
        return $this->hasOne(DebitOrder::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function custom_debit()
    {
        return $this->hasOne(CustomDebitOrders::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function custom_eft()
    {
        return $this->hasOne(CustomEftPayments::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class)->orderBy('created_at', 'desc');
    }

    public function addInvoices($invoices)
    {
        return $this->invoices()->saveMany($invoices);
    }

    public function addInvoice($invoice)
    {
        return $this->addInvoices([$invoice]);
    }

    public function isRegisteredForEvent($event)
    {
        $event = $this->tickets->where('event_id',$event->id)->first();
        if($event)
        {
            return true;
        }
        return false;
    }

    public function isRegisteredEventTicket($event)
    {
        foreach ($this->tickets as $ticket) {
            if ($ticket->event && $ticket->event->id == $event->id)
                return $ticket;
        }
        return false;
    }


    public function register($first_name, $last_name, $email, $password, $cell, $id_number, $alternative_cell, $membership_number, $specified_body, $interest, $employment, $industry)
    {
        return new static(compact('first_name', 'last_name', 'email', 'password', 'cell', 'id_number', 'alternative_cell', 'membership_number', 'specified_body', 'interest', 'employment', 'industry'));
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'store_orders')->withTimestamps();
    }

    public function addProduct($product, $invoice = null)
    {
        if ($invoice)
            return $this->products()->save($product, [
                'invoice_id' => $invoice->id
            ]);

        return $this->products()->save($product);
    }

    public function productsWithResources()
    {
        return $this->products()->with(['links', 'files']);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function attempts()
    {
        return $this->belongsTo(Attempt::class);
    }

    public function hasCell()
    {
        return boolval($this->cell || trim($this->cell) != "");
    }

    public function requireContactDetails()
    {
        return $this->profile && count($this->invoices) > 0;
    }

    public function batches()
    {
        return $this->morphMany(ImportBatch::class, 'importable');
    }

    public function findUser($id)
    {
        $user = User::findorFail($id);
        return $user;
    }

    public function overdueInvoices()
    {
        $invoices = $this->allUnpaidInvoices();
        return $this->filterOverdueInvoices($invoices);
    }

    public function ageAnalysis()
    {
        $invoices = $this->overdueInvoices();
        if (count($invoices)){
            return Carbon::now()->diff($invoices->last()->created_at)->days;
        }else
            return null;
    }

    public function getRolesAttribute()
    {
        return $this->roles()->lists('id')->toArray();
    }

    // return all unpaid invoices for the user.
    public function allUnpaidInvoices()
    {
        $invoices = $this->invoices->where('status', 'unpaid')
            ->where('paid', 0);
        return $invoices;
    }

    // Filter the overdue invoices and add 15 days
    public function filterOverdueInvoices($invoices)
    {
        if ($this->payment_method == 'eft'){
            return $invoices->filter(function ($invoice) {
                if ($invoice->created_at->addDays(15) <= Carbon::now() && $invoice->balance>0) {
                    return $invoice;
                }
            });
        }else{
            return $invoices->filter(function ($invoice) {
                if ($invoice->created_at->addDays(40) <= Carbon::now() && $invoice->balance>0) {
                    return $invoice;
                }
            });
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class)->orderBy('id');
    }

    public function suspended_notification()
    {
        return $this->hasMany(SuspendNotification::class);
    }

    public function getCellAttribute()
    {
        return $this->attributes['cell'];
        // return substr(preg_replace("/[^0-9]/", "", $this->attributes['cell']), 0, 10);
    }

    public function custom_features()
    {
        return $this->hasOne(CustomPlanFeature::class);
    }

    // Set default user avatar.
    public function getAvatarAttribute()
    {
        return asset('storage/'.$this->attributes['avatar']) ? : "http://imageshack.com/a/img924/5552/cZ1ADM.jpg";
    }

    // Set name to correct format.
    public function full_name()
    {
        return ucfirst(strtolower($this->first_name)).' '.ucfirst(strtolower($this->last_name));
    }

    public function getNameAttribute() {
        return ucwords($this->first_name . ' ' . $this->last_name);
    }

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    /* The wallet for the user. */
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function getavailableWalletAttribute()
    {
        if ($this->wallet){
            return $this->wallet->amount;
        }else{
            return 0;
        }
    }

    public function completed_Assessments()
    {
       $assessments = Attempt::with('assessment')->where('user_id', $this->id)->get();
       return $assessments;
    }

    public function body()
    {
        return $this->belongsTo(Body::class);
    }

    public function getMembershipNumberAttribute()
    {
        return ($this->attributes['membership_number'] )? : "None";
    }

    public function upgrades()
    {
        return $this->hasMany(UpgradeSubscription::class, 'member_id');
    }
    public function employing_companies()
    {
        return $this->belongsToMany(Company::class);
    }
    public function getPendingUpgradeAttribute()
    {
        $check = $this->upgrades->where('completed', '==', false);
        if ($check->count() >= 1){
            return true;
        }else
            return false;
    }

    public function showPopup()
    {
        if(Cache::has('debit-course-update-' . $this->id))
        {
            return true;
        }
        $recurring = false;
        if($this->subscription('cpd'))
        {
            if($this->subscription('cpd')->plan->price > 0 && $this->subscription('cpd')->plan->interval == 'month'){
                $recurring = true;
            }
        }
        
        $Courses = $this->subscriptions->where('name', 'course');
        foreach($Courses as $course){
            if($course->plan && $course->plan->interval == 'month' && !in_array($course->plan_id,['314','324']))
            {
                $recurring =true;
            }
        }
        
        if ($recurring && ($this->payment_method== '' || $this->payment_method == 'debit_order')) {
            if($this->payment_method == 'debit_order' && $this->debit && ($this->debit->branch_code == "" || $this->debit->number == ""))
            {
                return true;
            }
            if($this->payment_method == 'debit_order' && !$this->debit)
            {
                return true;
            }
        }
        return false;  
    }
    public function showCreditPopup()
    {
        $recurring = false;
        if($this->subscription('cpd'))
        {
            if($this->subscription('cpd')->plan->price > 0 && $this->subscription('cpd')->plan->interval == 'month'){
                $recurring = true;
            }
        }
        
        $Courses = $this->subscriptions->where('name', 'course');
        foreach($Courses as $course){
            if($course->plan && $course->plan->interval == 'month')
            {
                $recurring =true;
            }
        }
        
        if ($recurring && ($this->payment_method== '' || $this->payment_method == 'credit_card')) {
           
            if(count($this->cards)==0)
            {
                return true;
            }
        }
        return false;  
    }
    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function company_admin()
    {
        if ($this->company && $this->company->user_id == $this->id){
            return true;
        }else{
            return false;
        }
    }
    public function ViewResourceCenter()
    {
        $viewAccess = false;
        if($this->subscribed('cpd'))
        {
            $Professions = $this->subscription('cpd')->plan->professions;
            if($Professions->count())
            {
                foreach($Professions as $pro)
                {
                    if($pro->view_resource_center)
                    {
                        $viewAccess = true;
                    }
                }
            }
        }
        return $viewAccess;
    }
    public function hasCompany()
    {
        $company = CompanyUser::where('user_id', $this->id)->get();
        if (isset($company)){
            return $company->first();
        }
    }

    public function smses()
    {
        return $this->hasMany(SMS::class);
    }

    public function coupons()
    {
        return $this->hasMany(CouponUser::class);
    }
    public function CouponDiscount()
    {
        return $this->hasMany(CouponDiscount::class);
    }
    public function DiscountForCourse($course)
    {
        $course = $this->CouponDiscount()->where('course_id',$course)->first();
        if($course)
        {
           return $course->course->getPromoCodesDiscountByCode($course->promocode->code,$course->course->recurring_monthly_fee);
        }
    }
    public function settings()
    {
        return new Settings($this);
    }

    public function webinars()
    {
        return $this->belongsToMany(Video::class, 'webinar_users')->orderBy('created_at', 'desc');
    }
    public function webinarsPending()
    {
        $InvoiceOrder = $this->invoiceOrders()->whereHas('items', function ($query) {
            $query->where('description','=','Webinar On-Demand');
        })->where('user_id',$this->id)->where('status','!=','paid')->where('paid','!=',1)->get();
        $items = collect();
        foreach($InvoiceOrder as $order)
        {
            foreach($order->items as $item)
            {
                $video = Video::find($item->item_id);
                if($video)
                {
                    if($video->type == 'series')
                    {
                        $webinar = $video->webinars->pluck('id')->toArray();
                        foreach($webinar as $web)
                        {
                            $items->push($web);
                        }
                        
                    }
                }
                    $items->push($item->item_id);
            }
        }
        $InvoiceOrderConfirm = $this->invoiceOrders()->whereHas('items', function ($query) {
            $query->where('description','=','Webinar On-Demand');
        })->where('user_id',$this->id)->where('status','=','paid')->where('paid','=',1)->get();
        $newItem = collect();
        foreach($InvoiceOrderConfirm as $order)
        {
            foreach($order->items as $item)
            {
                $video = Video::find($item->item_id);
                if($video)
                {
                    if($video->type == 'series')
                    {
                        $webinar = $video->webinars->pluck('id')->toArray();
                        foreach($webinar as $web)
                        {
                            $newItem->push($web);
                        }
                        
                    }
                }
                    $newItem->push($item->item_id);
            }
        }
        $newItem = $newItem->all();
        $filtered = $items->filter(function ($value) use($newItem) {
            return !in_array($value,$newItem);
        });
        $items = $filtered;
        $webinars = $this->webinars();
        if($items->count())
        {
            $webinars = $webinars->whereNotIn('webinar_users.video_id',$items->toArray());
        }
        return $webinars->get(); 
    }

    public function free_webinars()
    {
        return $this->belongsToMany(Video::class, 'webinar_users')
            ->where('amount', '<=', '0')
            ->where('type', 'single')
            ->orderBy('created_at', 'desc');
    }

    public function paid_webinars()
    {
        return $this->belongsToMany(Video::class, 'webinar_users')
            ->where('amount', '>', '0')
            ->where('type', 'single')
            ->orderBy('created_at', 'desc');
    }

    public function isWebinarsSubscribed($videos)
    {
        foreach ($this->webinars as $webinar) {
            if ($webinar->id == $videos->id)
                return true;
        }
        return false;
    } 
    public function PracticePlan()
    {
        if($this->subscribed('cpd'))
        {
           if($this->subscription('cpd')->plan->is_practice)
           {
            return true;
           }
        }
        return false;
    }

    public function requiredProfessionalBody()
    {
        if ($this->body != null){
            return false;
        }
    }

    public function cycles()
    {
        return $this->belongsToMany(Cycle::class);
    }

    public function nextEvent()
    {
        $tickets =  $this->tickets->filter(function ($ticket){
           if ($ticket->start_date >= Carbon::now()){
               return $ticket;
           };
        });

        if (count($tickets)){
            return $tickets->sortBy('start_date')->first();
        }else
            return null;
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    public function saveCategories($categories)
    {
        if (is_null($this->blog_intrests)){
            $intrests = [];
            foreach ($categories as $category){
                array_push($intrests, $category->title);
            }
            $this->update(['blog_intrests' => $intrests]);
        }else{
            foreach ($categories as $category){
                if (! in_array($category->title, $this->blog_intrests)){
                    $saving = array_merge($this->blog_intrests, [$category->title]);
                    $this->update(['blog_intrests' => $saving]);
                }
            }
        }
    }

    public function saveAdditionalProfessionalBodies($bodies)
    {
        if (! empty($bodies)){
            $NewBodies = [];
            foreach ($bodies as $bodyId){array_push($NewBodies, Body::find($bodyId)->id);}
            $this->update(['additional_professional_bodies' => $NewBodies]);
        }else{
            $this->update(['additional_professional_bodies' => []]);
        }
    }

    public function extraProfessionalBodies()
    {
        $bodies = collect();
        foreach ($this->additional_professional_bodies as $bodyId){
            $bodies->push(Body::find($bodyId)->title);
        }
        if (count($bodies)){
            return implode(", ",$bodies->toArray());
        }else{
            return "";
        }
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function getWebinarCategoryCount($tag)
    {
        $data = collect();
        $webinars = $this->webinars()->where('tag', $tag)->get()->filter(function ($video) { if ($video->category || $video->category = ''){ return $video; } })->groupBy('category');
        foreach ($webinars as $key => $value){
            if ($key != 'null'){
                $data->push([
                    'slug' => $key,
                    'name' => ucwords(str_replace('_', ' ', $key)),
                    'count' => count($value)
                ]);
            }
        }
        return $data;
    }

    public function wasInvoiceForSubscriptionThisMonth()
    {
        if (count($this->invoices->where('type', 'subscription')->where('total', $this->subscription('cpd')->plan->price))){
            $invoice = $this->invoices->where('type', 'subscription')->first();
            if (Carbon::parse($invoice->created_at)->month == Carbon::now()->month){
                return $invoice;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function invoiceOrders()
    {
        return $this->hasMany(InvoiceOrder::class);
    }

    public function support_tickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    public function replyReceived()
    {
        $threads = collect();
        $this->support_tickets->each(function ($support) use($threads){
            $threads->push($support->thread);
        });

        return $threads->sum('replies');
    }

    public function rep()
    {
        return $this->hasOne(Rep::class);
    }

    public function round_include()
    {
        if (count($this->invoices) == 0 &&
            count($this->transactions) == 0 &&
            count($this->invoiceOrders) == 0 &&
            count($this->orders) == 0 &&
            $this['round_robin_notified'] == false
        ){
            return true;
        }else{
            return false;
        }
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class,'course_user');
    }

    public function isCourseSubscribed($courses)
    {
        foreach ($this->courses as $course) {
            if ($course->id == $courses->id)
                return true;
        }
        return false;
    }
    public function getCourseInvoice($course)
    {
        $course = $this->courses->where('id',(int) $course)->first();
        if($course){
            $invoices  = $this->invoices->where('type','course')->where('status','paid')->pluck('id');
            if($invoices)
            {
                $data = DB::table('course_invoice')->select('invoice_id')->whereIn('invoice_id', $invoices)->where('course_id',$course->id)->orderBy('id','desc')->first();
                if($data){
                    return $data;
                }
            }
            
        }
        return []; 
    }
    public function isPracticePlan()
    {
        $items = collect();
        $planId = 0;
        if($this->subscription('cpd') && $this->subscription('cpd')->plan->is_practice)
        {
            foreach($this->invoices as $invoice)
            {
                if($invoice->status=='paid' && $invoice->items->count())
                {
                foreach($invoice->items as $item)
                {
                    if($item->item_id>0)
                    {
                        $plan = Plan::find($item->item_id);
                        if ($plan && $plan->is_practice) {
                            $items->push($item);
                        }
                    }

                }
                }
            }
            $items = $items->sortByDesc('id');
            $item = 0;
            if($items->count()){
                $item = $items->first();
                $item = $item->quantity;
            }
            if($this->subscription('cpd')->plan->interval == 'year'){
                $items = $items->filter(function ($value) {
                    return $value->created_at > Carbon::now()->subYears(1);
                });
                $item = $items->sum('quantity');
            }
            $additional_users = '';
            if(isset($this->additional_users))
            {
                $additional_users = $this->additional_users;
            }
            $planId = $item + $additional_users; 
        }
        return $planId;
    }
    public function getCourseInvoiceByPlanId($course)
    {
        $course = $this->courses->where('monthly_plan_id', (int) $course)->first();
        if($course){
            $invoices  = $this->invoices->where('type','course')->where('status','paid')->pluck('id');
            if($invoices)
            {
                $data = DB::table('course_invoice')->select('invoice_id')->whereIn('invoice_id', $invoices)->where('course_id',$course->id)->get();
                if($data){
                    return true;
                }
            }
            
        }
        return false; 
    }
    public function getPricingGroup()
    {
        $pricingId =0;
        $items = collect();
        foreach($this->invoices as $invoice)
        {
            if($invoice->isPaid() && $invoice->items->count())
            {
               foreach($invoice->items as $item)
               {
                   if($item->item_id>0)
                   {
                    $items->push($item);
                   }

               }
            }
        }
        if($items->count())
        {
            $pricing = PricingGroup::where ('price',$items[0]->price)->first();
            if($pricing)
            {
                $pricingId = $pricing->id;
            }
        }
        
        return $pricingId;
    }

    public function subscriptionUpgrade()
    {
        return $this->hasMany(SubscriptionUpgrade::class)->where('is_completed',0)->orderBy('id','desc')->first();
    }
    public function isCourseAssigned($refrence)
    {
        $course = $this->courses->where('reference',$refrence)->first();
        if($course){
            $invoices  = $this->invoices->where('type','course')->where('status','paid')->pluck('id');
            if($invoices)
            {
                $data = DB::table('course_invoice')->select('invoice_id')->whereIn('invoice_id', $invoices)->where('course_id',$course->id)->first();
                if($data){
                    return true;
                }
            }
            
        }
        return false;
    }

    public function agentGroups()
    {
        return $this->belongsToMany(AgentGroup::class, 'agent_group_users');
    }

    public function getAgentIdentifierAttribute() 
    {
        return $this->first_name.' '.$this->last_name.' ('.$this->email.')';
    }
    
    public function totalStaff(){
        return ($this->company)?($this->company->staff->count()+$this->company->invites()->where('completed',false)->count())+1:1;
    }
    public function canRenew() {

        $expiry_date = Carbon::now()->addDays(31)->endOfDay();
        $now = Carbon::now()->startOfDay();

        $show = false;
        // if($this->subscribed('cpd')) {
            $subscription = $this->subscription('cpd');
            if($subscription) {
                $plan = $subscription->plan;
                if($subscription->ends_at==null && $subscription->starts_at==null)
                {
                    return true;
                }
                if( 
                    $subscription->ends_at < $expiry_date && 
                    $plan->interval == 'year' &&
                    !$plan->is_practice &&
                    !$subscription->canceled()
                ) {
                    $show = true;
                }
            }
        // }
        return $show;

    }

    public function canSelfRenew() {

        $expiry_date = Carbon::now()->addDays(31)->endOfDay();
        $now = Carbon::now()->startOfDay();
        
        $show = false;
        // if($this->subscribed('cpd')) {
            $subscription = $this->subscription('cpd');
            if($subscription) {
                $plan = $subscription->plan;
                // $subscription->ends_at > $now &&
                if( 
                    $subscription->ends_at < $expiry_date && 
                    $plan->price > 0 &&
                    $plan->interval == 'year' &&
                    !$plan->is_practice &&
                    !$subscription->canceled()
                ) {
                    $show = true;
                }
            }
        // }
        return $show;

    }

    public function showSelfRenewPopup() {
        $expiry_date = Carbon::now()->addDays(1)->endOfDay();
        $now = Carbon::now()->startOfDay();
        $show = false;
        $subscription = $this->subscription('cpd');
        if($subscription) {
            $plan = $subscription->plan;
            if(
                $subscription->ends_at >= $now && 
                $subscription->ends_at <= $expiry_date && 
                $plan->price > 0 &&
                $plan->interval == 'year' &&
                !$plan->is_practice
            ) {
                return true;
            }

            if($plan->price > 0 &&
                $plan->interval == 'year' &&
                $subscription->suspended() &&
                !$plan->is_practice 
            ) {
                    $show = true;
            }
        }
        return $show;
    }

   

    public function GetAllCPD()
    {
        $cpds = Cpd::with('user');
        $users = [auth()->user()->id];
        if($this->isPracticePlan()){
            if($this->company->staff)
            {
                if(isset(request()->employee) && request()->employee!=""){
                    $users = [request()->employee];
                }else{
                     $users= array_merge($this->company->staff->pluck('id')->toArray(),$users);
                }
            }
        }
		
        $cpds= $cpds->whereIn('user_id',$users);
        return $cpds;
    }
}