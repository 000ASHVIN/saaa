<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use App\Traits\ActivityTrait;

class SponsorList extends Model implements SluggableInterface
{
    use SluggableTrait, ActivityTrait;
    protected $table = 'sponsor_list';
    protected $guarded = [];

    protected $sluggable = [
        'build_from' => 'name',
        'save_to' => 'slug',
    ];
}
