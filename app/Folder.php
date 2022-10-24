<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model implements SluggableInterface
{
    use SluggableTrait;
    protected $table = 'folders';
    protected $guarded = [];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    protected $sluggable = [
        'build_from' => 'title',
        'save_to' => 'slug',
    ];
}
