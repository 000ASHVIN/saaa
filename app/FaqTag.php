<?php

namespace App;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;

class FaqTag extends Model implements SluggableInterface
{

    use SluggableTrait;

    protected $table = 'faq_tags';
    protected $fillable = ['title', 'slug', 'type'];

    protected $sluggable = [
        'build_from' => 'title',
        'save_to' => 'slug',
    ];

    public function questions()
    {
        return $this->hasMany(FaqQuestion::class, 'faq_tag_id');
    }
}
