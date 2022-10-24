<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invite extends Model
{
    protected $table = 'invites';
    protected $fillable = ['email', 'token', 'first_name', 'last_name', 'id_number', 'completed', 'company_id', 'cell', 'alternative_cell'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function complete()
    {
        $this->completed = true;
        $this->save();
    }
}
