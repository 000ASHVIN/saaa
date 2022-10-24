<?php

namespace App\Newsletters;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Subscriber
 * @package App\Newsletters
 */
class Subscriber extends Model
{
    protected $table = 'subscribers';
    protected $guarded = [];
}
