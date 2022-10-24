<?php

namespace App;

use App\models\Cycle;
use App\Models\Designation;
use App\AppEvents\Pricing;
use App\Profession\Profession;
use App\Subscriptions\Models\Plan;
use App\Users\User;
use Illuminate\Database\Eloquent\Model;

class Body extends Model
{
    protected $table = 'bodies';
    protected $guarded = [];

    public function plans()
    {
        return $this->belongsToMany(Plan::class);
    }

    public function getPlanListAttribute()
    {
        return $this->plans->pluck('id')->toArray();
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function pricings()
    {
        return $this->belongsToMany(Pricing::class);
    }

    public function designations()
    {
        return $this->hasMany(Designation::class);
    }

    public function cycles()
    {
        return $this->belongsToMany(Cycle::class);
    }

    public function professions()
    {
        return $this->belongsToMany(Profession::class);
    }
}
