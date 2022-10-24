<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompanyUser extends Model
{
    protected $table = 'company_user';
    protected $fillable  = ['company_id', 'user_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
