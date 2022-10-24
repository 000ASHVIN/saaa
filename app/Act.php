<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Nicolaslopezj\Searchable\SearchableTrait;

class Act extends Model
{
    use SearchableTrait;
    protected $guarded = [];
    protected $table = 'act_pages';

    protected $searchable = [
        'columns' => [
            'title' => 10,
            'description' => 8,
            'content' => 8,
        ]
    ];

    public function actList()
    {
        return $this->belongsTo(ActList::class);
    }

    public function children()
    {
        return $this->hasMany(Act::class, 'parent_id')->where('is_toc_item', 1);
    }
}
