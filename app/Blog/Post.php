<?php

namespace App\Blog;

use App\Users\User;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;
use App\Traits\SEOTrait;

class Post extends Model implements SluggableInterface
{
    use SluggableTrait, SearchableTrait,SEOTrait;
    protected $Seoname = 'title';
    protected $dates = ['created_at', 'updated_at'];
    protected $table = 'posts';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'image',
        'draft',
        'keyword',
        'meta_description',
        'publish_date',
        'publish_time'
    ];

    protected $searchable = [
        'columns' => [
            'title' => 10,
            'description' => 8,
        ]
    ];

    protected $sluggable = [
        'build_from' => 'title',
        'save_to' => 'slug',
    ];

    public function author()
    {
        return $this->belongsToMany(Author::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('id', 'Desc');
    }

    public function getAuthorListAttribute()
    {
        return $this->author()->get()->pluck('id')->toArray();
    }

    public function getCategoryListAttribute()
    {
        return $this->categories()->get()->pluck('id')->toArray();
    }

    public function pendingComments()
    {
        return $this->comments()->where('approved', false)->get();
    }

    public function acceptedComments()
    {
        return $this->comments()->where('approved', true)->get();
    }

     // For dynamic meta title
     public function checkMetaTitle()
     {
         $meta_title = '';
         if($this->getMetaTitle()!="") {
             $meta_title = $this->getMetaTitle();
         }
         else {
             $meta_title = $this->attributes['title'];
         }
         return $meta_title;
     }
}
