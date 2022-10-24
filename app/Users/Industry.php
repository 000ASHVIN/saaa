<?php

namespace App\Users;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model implements SluggableInterface
{
    use SluggableTrait;

    protected $table = 'industries';

    protected $guarded = [];

    protected $sluggable = [
        'build_from' => 'title',
        'save_to' => 'slug',
    ];
}
