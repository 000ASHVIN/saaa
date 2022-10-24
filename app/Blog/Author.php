<?php

namespace App\Blog;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $guarded = [];
    protected $table = 'authors';

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
