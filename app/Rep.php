<?php

namespace App;

use App\Users\User;
use Illuminate\Database\Eloquent\Model;

class Rep extends Model
{
    protected $table = 'reps';
    protected $guarded = [];

    public function scopeNextAvailable($query)
    {
        return $query->where('active', true)->orderBy('emailedLast', 'asc')->get()->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
