<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Subscriptions\Models\Feature;

class PracticePlanTabs extends Model
{

    /**
     * @var string
     */
    protected $table = 'practice_plan_tabs';

    /**
     * @var bool
     */
    public $timestamps = true;

    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function features()
    {
        return $this->belongsToMany(Feature::class,'practice_plan_tabs_features','practice_plan_tab_id', 'feature_id');
    }

}
