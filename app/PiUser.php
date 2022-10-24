<?php

namespace App;

use App\PiAddress;
use App\PiAssessment;
use Illuminate\Database\Eloquent\Model;

class PiUser extends Model
{
	protected $guarded = [];
	
	public function piAssessment()
    {
        return $this->hasOne(PiAssessment::class);
    }

    public function piAddresses()
    {
    	return $this->hasMany(PiAddress::class);
    }
}
