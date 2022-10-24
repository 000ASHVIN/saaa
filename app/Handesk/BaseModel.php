<?php

namespace App\Handesk;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $guarded = [];

    protected $connection = 'handesk_mysql';

}
