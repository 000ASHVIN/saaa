<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UpgradeFeatures extends Model
{
    protected $table = 'upgrade_features';
    protected $guarded = [];

    public function subscription()
    {
        return $this->belongsTo(UpgradeSubscription::class);
    }
}
