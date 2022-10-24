<?php

namespace App;

use App\PiUser;
use Illuminate\Database\Eloquent\Model;

class PiAssessment extends Model
{
	protected $guarded = [];
	
    public function piUser()
    {
    	return $this->belongsTo(PiUser::class);
    }
}
