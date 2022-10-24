<?php

namespace App\Models;

use App\Blog\Category;
use App\File;
use App\Link;
use App\Presenters\Presenter;
use App\Users\User;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Discount;
use App\AppEvents\PromoCode;
use App\Models\CouponDiscount;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Rutorika\Sortable\SortableTrait;
use App\Traits\SEOTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\ActivityTrait;
use App\ActivityLog;

class Course extends Model implements SluggableInterface {
    
    use SearchableTrait,SluggableTrait,ActivityTrait,SEOTrait,SoftDeletes;

    protected $Seoname = 'title';

    protected $searchable = [
        'columns' => [
            'title' => 10,
            'description' => 8,
            'short_description' => 5,
        ]
    ];

      /**
     * @var array
     */
    protected $sluggable = [
        'build_from' => 'title',
		'save_to' => 'slug',
    ];

    protected $appends = ['discount', 'discounted_price','annual_discount', 'annual_discounted_price','discounted_debit_order'];

    protected $guarded = [];
    protected $table = 'courses';

    protected $dates = ['start_date', 'end_date'];

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
        static::creating(function ($course) {
            $reference = $course->getNextReference();
            $course->reference = $reference;
        });
    }

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


    public function presenters()
    {
        return $this->belongsToMany(Presenter::class);
    }

    public function files()
    {
        return $this->belongsToMany(File::class);
    }

    public function links()
    {
        return $this->belongsToMany(Link::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function GetOrderByCourse($user,$course)
    {
        try{
            $orders = $user->invoices()->whereHas('items', function($q) use($course)
            {
                $q->where('items.item_id','=', $course);
            
            })->first();
            if(!$orders)
            {
                $orders = $user->invoiceOrders()->whereHas('items', function($q) use($course)
                {
                    $q->where('items.item_id','=', $course);
                
                })->first(); 
            }
            return $orders;
        }catch(\Exception $e){
           return [];

        }
    }

    public function getPresenterAttribute()
    {
        return $this->presenters->pluck('id')->toArray();
    }

    public function getTagsAttribute()
    {
        return $this->categories->pluck('id')->toArray();
    }

    public function getMonthlyEnrollmentFeeAttribute()
    {
        return $this->attributes['monthly_enrollment_fee'] / 100;
    }

    public function getYearlyEnrollmentFeeAttribute()
    {
        return $this->attributes['yearly_enrollment_fee'] / 100;
    }

    public function getRecurringMonthlyFeeAttribute()
    {
        return $this->attributes['recurring_monthly_fee'] / 100;
    }

    public function getRecurringYearlyFeeAttribute()
    {
        return $this->attributes['recurring_yearly_fee'] / 100;
    }
    public function CouponDiscount()
    {
        return $this->hasMany(CouponDiscount::class);
    }
    public function DiscountForUser($user)
    {
        $course = $this->CouponDiscount()->where('user_id',$user)->first();
        if($course)
        {
            if($course->promocode->discount_type == "amount"){
                return $this->getPromoCodesDiscountByCode($course->promocode->code,$this->recurring_monthly_fee);
            }
            return $this->recurring_monthly_fee;
        }else{
           return $this->recurring_monthly_fee;
        }
    }

    public function DiscountAmountForUser($user)
    {
        $course = $this->CouponDiscount()->where('user_id',$user)->first();
        if($course)
        {
            if($course->promocode->discount_type == "amount"){
                return $this->getPromoCodesDiscountAmountByCode($course->promocode->code,$this->recurring_monthly_fee);
            }
            return 0;
        }else{
            return 0;
        }
    }

    /**
     * @return Collection
     * Get the invoices for this course.
     */
    public function invoices()
    {
        $invoices = collect();
        $data = DB::table('course_invoice')->select('invoice_id')->where('course_id', $this->id)->get();

        foreach ($data as $invoiceArray) {
            $invoices->push($invoiceArray->invoice_id);
        }

        return $invoices;
    }
    
    public function promoCodes()
    {
        return $this->belongsToMany(PromoCode::class,"course_promo_code");
    }

    public function getPromoCodesDiscount()
    {
        $totalPromoCodesDiscount = 0;

        

        $sessionPromoCodes = PromoCode::sessionCodes();

        foreach ($this->promoCodes as $promoCode) {
            if (array_has($sessionPromoCodes, $promoCode->code)) {
                $totalPromoCodesDiscount += calculateDiscount($this->monthly_enrollment_fee, $promoCode->discount_amount, $promoCode->discount_type);
            }
        }

        return $totalPromoCodesDiscount;
    }


    public function getPromoCodesDiscountByCode($code,$amount)
    {
        $totalPromoCodesDiscount = 0;
        $sessionPromoCodes[$code] = $code;

        foreach ($this->promoCodes as $promoCode) {
            if (array_has($sessionPromoCodes, $promoCode->code)) {
                $totalPromoCodesDiscount += calculateDiscount($amount, $promoCode->discount_amount, $promoCode->discount_type);
            }
        }

        return $amount-$totalPromoCodesDiscount;
    }

    public function getPromoCodesDiscountAmountByCode($code,$amount)
    {
        $totalPromoCodesDiscount = 0;
        $sessionPromoCodes[$code] = $code;

        foreach ($this->promoCodes as $promoCode) {
            if (array_has($sessionPromoCodes, $promoCode->code)) {
                $totalPromoCodesDiscount += calculateDiscount($amount, $promoCode->discount_amount, $promoCode->discount_type);
            }
        }

        return $totalPromoCodesDiscount;
    }

      /**
     * Get Discount
     *
     * @return int|mixed
     */
    public function getDiscountAttribute()
    {
        return  ($this->getPromoCodesDiscount());
    }

    /**
     * Discounted Pricing
     *
     * @return mixed
     */
    public function getDiscountedPriceAttribute()
    {
        return $this->monthly_enrollment_fee -  ($this->getPromoCodesDiscount());
    }

    public function getPromoCodesDiscountAnnual()
    {
        $totalPromoCodesDiscount = 0;

        

        $sessionPromoCodes = PromoCode::sessionCodes();

        foreach ($this->promoCodes as $promoCode) {
            if (array_has($sessionPromoCodes, $promoCode->code)) {
                $totalPromoCodesDiscount += calculateDiscount($this->yearly_enrollment_fee, $promoCode->discount_amount, $promoCode->discount_type);
            }
        }

        return $totalPromoCodesDiscount;
    }

    public function getPromoCodesDiscountDebit()
    {
        $totalPromoCodesDiscount = 0;

        

        $sessionPromoCodes = PromoCode::sessionCodes();

        foreach ($this->promoCodes as $promoCode) {
            if (array_has($sessionPromoCodes, $promoCode->code)) {
                $totalPromoCodesDiscount += calculateDiscount($this->no_of_debit_order, $promoCode->discount_amount, $promoCode->discount_type);
            }
        }

        return $totalPromoCodesDiscount;
    }

    public function getPromoCodesDiscountDebitByCode($code)
    {
        $totalPromoCodesDiscount = 0;
        $sessionPromoCodes[$code] = $code;

        foreach ($this->promoCodes as $promoCode) {
            if (array_has($sessionPromoCodes, $promoCode->code)) {
                if($promoCode->discount_type == 'percentage'){
                    $totalPromoCodesDiscount += calculateDiscount($this->no_of_debit_order, $promoCode->discount_amount, $promoCode->discount_type);
                }
            }
        }

        return (int) $totalPromoCodesDiscount;
    }

      /**
     * Get Discount
     *
     * @return int|mixed
     */
    public function getAnnualDiscountAttribute()
    {
        return  ($this->getPromoCodesDiscountAnnual());
    }

    /**
     * Discounted Pricing
     *
     * @return mixed
     */
    public function getAnnualDiscountedPriceAttribute()
    {
        return $this->yearly_enrollment_fee -  ($this->getPromoCodesDiscountAnnual());
    }

    public function getDiscountedDebitOrderAttribute()
    {
        return $this->no_of_debit_order - round($this->getPromoCodesDiscountDebit());
    }

    // For dynamic meta title
    public function checkMetaTitle()
    {
        $meta_title = '';
        if($this->getMetaTitle()!="") {
            $meta_title = $this->getMetaTitle();
        }
        else {
            $meta_title = $this->attributes['title']  .' | Course | '. config('app.name');
        }
        return $meta_title;
    }

    public function getDurationMonthsAttribute() {

        if($this->start_date && $this->end_date) {

            $start = new \DateTime($this->start_date->format('Y-m-d'));
            $end   = new \DateTime($this->end_date->format('Y-m-d'));
            $diff  = $start->diff($end);
            $month = $diff->format('%y') * 12 + $diff->format('%m');
            if($diff->d>1){
                $month++;
            }
            
            return $month;
        }
        return null;

    }

    public static function getCourseTypes() {
        return [
            'qualifications' => 'Qualifications and Programmes',
            'professional' => 'Professional Certificates',
            'short' => 'Short Courses'
        ];
    }

    public function getCourseTypeFormattedAttribute() {
        $types = $this->getCourseTypes();
        return isset($types[$this->course_type])?$types[$this->course_type]:null;
    }

}
