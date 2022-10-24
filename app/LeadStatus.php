<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadStatus extends Model
{
    use SoftDeletes;
    protected $table = 'lead_status';

    protected $fillable = [
        'name'
    ];

    public static function rules($statusId=null) {
        return [
            'name' => 'unique:lead_status,name,' . $statusId
        ];
    }

}
