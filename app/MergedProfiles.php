<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Users\User;

class MergedProfiles extends Model
{

    protected $fillable = ['user_id', 'merged_user_id', 'mergable_id', 'mergable_type'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function merged_user() {
        return $this->belongsTo(User::class, 'merged_user_id');
    }

    public function mergable() {
        return $this->morphTo('mergable');
    }

}
