<?php

namespace App\AppEvents;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $guarded = [];

    const SESSION_KEY = 'saaa_promo_codes';

    static function sessionCodes()
    {
        return session()->get(static::SESSION_KEY, []);
    }

    static function findByCode($code)
    {
        return static::where('code', $code)->first();
    }

    static function clear()
    {
        return session()->forget(static::SESSION_KEY);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class);
    }

    public function addToSession()
    {
        return session()->put(static::SESSION_KEY . '.' . $this->code, $this->code);
    }
    static function GetPromoCode()
    {
        $keys = array_keys(PromoCode::sessionCodes());
        if(count($keys) > 0){
            return PromoCode::findByCode($keys[0])->id;
        }
        return 0;
    }

    public function removeFromSession()
    {
        return session()->forget(static::SESSION_KEY . '.' . $this->code);
    }
}
