<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Jobs extends Model implements SluggableInterface
{
    use SluggableTrait;

    protected $table = 'jobs';
    protected $fillable = [
        'title',
        'slug',
        'period',
        'location',
        'description',
        'personality',
        'skills',
        'departments_id',
        'available'
    ];

    protected $sluggable = [
        'build_from' => 'title',
        'save_to'    => 'slug',
    ];

    public function department()
    {
        return $this->belongsTo(Departments::class, 'departments_id');
    }
}
