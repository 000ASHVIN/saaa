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

class CouponDiscount extends Model
{
    use SearchableTrait;

    protected $guarded = [];
    protected $table = 'coupon_discount';
   
    public function user()
    {
        return $this->belongsTo(User::class);
    }   

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function promocode()
    {
        return $this->belongsTo(PromoCode::class,'promo_code_id','id');
    }
}

