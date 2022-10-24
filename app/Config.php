<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Config extends Model
{
    use SearchableTrait;
    protected $guarded = [];
    protected $table = 'settings';

}
