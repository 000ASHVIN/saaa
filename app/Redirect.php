<?php

namespace App;

use App\Traits\ActivityTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;

class Redirect extends Model
{
    use SoftDeletes;
    protected $guarded = [];
    protected $table = 'redirect';

  

}
