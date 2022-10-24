<?php

namespace App;

use App\Users\User;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $guarded = [];
	
	protected $hidden = ['token', 'created_at', 'updated_at', 'user_id'];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
